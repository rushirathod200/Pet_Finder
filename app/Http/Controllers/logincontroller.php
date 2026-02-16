<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\usersdata;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class logincontroller extends Controller
{
    function index(Request $request)
    {
        if ($request->session()->has('useremail')) {
            return redirect('/profile');
        }
        return view('login');
    }

    function loginuser(Request $request)
    {
        if ($request->filled('fullname')) {
            $signupEmail = trim((string) ($request->input('email') ?? $request->input('identifier') ?? ''));
            if ($signupEmail !== '') {
                $request->merge(['email' => $signupEmail]);
            }

            $data = $request->validate([
                'fullname' => ['required', 'string', 'max:255'],
                'email' => ['required', 'email', 'max:255', 'unique:usersdatas,email'],
                'phone_country' => ['required', 'in:IN,US,AU,DE'],
                'phone' => ['required', 'string'],
                'signup_password' => ['required', 'string', 'min:6', 'confirmed'],
            ]);

            $normalizedPhone = $this->normalizePhone($data['phone']);
            if (!preg_match('/^\d{6,15}$/', $normalizedPhone)) {
                throw ValidationException::withMessages([
                    'phone' => 'Enter a valid phone number.',
                ]);
            }

            $storedPhone = $this->formatPhoneWithCountryCode($data['phone_country'], $normalizedPhone);

            if (usersdata::where('phone', $storedPhone)->exists()) {
                throw ValidationException::withMessages([
                    'phone' => 'Phone number is already registered.',
                ]);
            }

            $user = usersdata::create([
                'fullname' => $data['fullname'],
                'email' => $data['email'],
                'phone' => $storedPhone,
                'location' => $this->resolveCityFromRequest($request),
                'password' => Hash::make($data['signup_password']),
            ]);

            $request->session()->put('useremail', $user->email);
            $request->session()->regenerate();

            return redirect('/profile');
        }

        $identifier = trim((string) ($request->input('identifier') ?? $request->input('email') ?? ''));
        $data = $request->validate([
            'identifier' => ['nullable', 'string', 'max:255'],
            'email' => ['nullable', 'string', 'max:255'],
            'login_password' => ['required', 'string'],
        ]);

        if ($identifier === '') {
            throw ValidationException::withMessages([
                'identifier' => 'Email or phone number is required.',
            ]);
        }

        $user = null;
        if (filter_var($identifier, FILTER_VALIDATE_EMAIL)) {
            $user = usersdata::where('email', $identifier)->first();
        } else {
            $normalizedPhone = $this->normalizePhone($identifier);
            if (!preg_match('/^\d{6,15}$/', $normalizedPhone)) {
                throw ValidationException::withMessages([
                    'identifier' => 'Enter a valid email or phone number.',
                ]);
            }

            $trimmedIdentifier = trim($identifier);
            $phoneCandidates = [
                $trimmedIdentifier,
                $normalizedPhone,
                '+'.$normalizedPhone,
            ];

            if (strlen($normalizedPhone) <= 12) {
                foreach ($this->countryDialCodeMap() as $dialCode) {
                    $phoneCandidates[] = $dialCode.$normalizedPhone;
                }
            }

            $phoneCandidates = array_values(array_unique(array_filter($phoneCandidates, fn ($value) => $value !== '')));
            $user = usersdata::whereIn('phone', $phoneCandidates)->first();
        }

        if (!$user || !Hash::check($data['login_password'], $user->password)) {
            throw ValidationException::withMessages([
                'identifier' => 'Invalid credentials.',
            ]);
        }

        $this->updateUserCityFromRequest($request, $user);
        $request->session()->put('useremail', $user->email);
        $request->session()->regenerate();

        return redirect('/profile');
    }

    function googleAuth(Request $request)
    {
        $data = $request->validate([
            'credential' => ['required', 'string'],
        ]);

        $googleClientId = (string) config('services.google.client_id');
        if ($googleClientId === '') {
            return response()->json([
                'message' => 'Google sign-in is not configured. Contact admin.',
            ], 422);
        }

        try {
            $response = Http::timeout(10)->get('https://oauth2.googleapis.com/tokeninfo', [
                'id_token' => $data['credential'],
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Unable to verify Google login right now. Please try again.',
            ], 502);
        }

        if (!$response->successful()) {
            return response()->json([
                'message' => 'Invalid Google credential. Please try again.',
            ], 422);
        }

        $payload = $response->json();
        $audience = (string) data_get($payload, 'aud', '');
        $email = trim((string) data_get($payload, 'email', ''));
        $emailVerifiedRaw = data_get($payload, 'email_verified');
        $fullName = trim((string) data_get($payload, 'name', 'Google User'));
        $profilePicture = trim((string) data_get($payload, 'picture', ''));

        if ($audience !== $googleClientId) {
            return response()->json([
                'message' => 'Google token audience mismatch.',
            ], 422);
        }

        $isEmailVerified = $emailVerifiedRaw === true || $emailVerifiedRaw === 'true' || $emailVerifiedRaw === 1 || $emailVerifiedRaw === '1';
        if ($email === '' || !$isEmailVerified) {
            return response()->json([
                'message' => 'Google account email is missing or not verified.',
            ], 422);
        }

        $user = usersdata::where('email', $email)->first();

        if (!$user) {
            $user = usersdata::create([
                'fullname' => $fullName !== '' ? $fullName : 'Google User',
                'email' => $email,
                'phone' => $this->generatePlaceholderPhone(),
                'location' => $this->resolveCityFromRequest($request),
                'password' => Hash::make(Str::random(40)),
                'profile_picture' => $profilePicture !== '' ? $profilePicture : null,
            ]);
        } else {
            $updates = [];
            if (!$user->profile_picture && $profilePicture !== '') {
                $updates['profile_picture'] = $profilePicture;
            }
            if ($user->fullname === '' && $fullName !== '') {
                $updates['fullname'] = $fullName;
            }
            if ($updates !== []) {
                $user->fill($updates)->save();
            }
        }

        $this->updateUserCityFromRequest($request, $user);
        $request->session()->put('useremail', $user->email);
        $request->session()->regenerate();

        return response()->json([
            'redirect' => '/profile',
        ]);
    }

    private function normalizePhone(string $phone): string
    {
        return preg_replace('/\D+/', '', $phone) ?? '';
    }

    private function generatePlaceholderPhone(): string
    {
        do {
            $phone = '9'.str_pad((string) random_int(0, 999999999), 9, '0', STR_PAD_LEFT);
        } while (usersdata::where('phone', $phone)->exists());

        return $phone;
    }

    private function formatPhoneWithCountryCode(string $countryCode, string $phone): string
    {
        $dialCode = $this->countryDialCodeMap()[$countryCode] ?? '+91';
        return $dialCode.$phone;
    }

    private function updateUserCityFromRequest(Request $request, usersdata $user): void
    {
        if (filled($user->location)) {
            return;
        }

        $city = $this->resolveCityFromRequest($request);
        if ($city === null || $city === $user->location) {
            return;
        }

        $user->location = $city;
        $user->save();
    }

    private function resolveCityFromRequest(Request $request): ?string
    {
        if (app()->environment('testing')) {
            return null;
        }

        $ipCandidates = [];
        $requestIp = trim((string) $request->ip());
        if ($requestIp !== '') {
            $ipCandidates[] = $requestIp;
        }

        $forwardedFor = trim((string) $request->server('HTTP_X_FORWARDED_FOR', ''));
        if ($forwardedFor !== '') {
            $firstForwardedIp = trim(explode(',', $forwardedFor)[0] ?? '');
            if ($firstForwardedIp !== '') {
                $ipCandidates[] = $firstForwardedIp;
            }
        }

        $ipCandidates = array_values(array_unique(array_filter($ipCandidates, fn ($ip) => $ip !== '')));

        foreach ($ipCandidates as $ip) {
            $city = $this->fetchCityByIp($ip);
            if ($city !== null) {
                return $city;
            }
        }

        return $this->fetchCityByIp(null);
    }

    private function fetchCityByIp(?string $ip): ?string
    {
        if ($ip !== null) {
            $ipIsPublic = filter_var(
                $ip,
                FILTER_VALIDATE_IP,
                FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE
            ) !== false;

            if (!$ipIsPublic) {
                $ip = null;
            }
        }

        $ipApiUrl = $ip !== null ? 'https://ipapi.co/'.$ip.'/json/' : 'https://ipapi.co/json/';

        try {
            $ipApiResponse = Http::timeout(4)->acceptJson()->get($ipApiUrl);
            if ($ipApiResponse->successful()) {
                $city = trim((string) data_get($ipApiResponse->json(), 'city', ''));
                if ($city !== '') {
                    return $city;
                }
            }
        } catch (\Throwable $th) {
        }

        $ipWhoIsUrl = $ip !== null ? 'https://ipwho.is/'.$ip : 'https://ipwho.is';
        try {
            $ipWhoIsResponse = Http::timeout(4)->acceptJson()->get($ipWhoIsUrl);
            if ($ipWhoIsResponse->successful() && (bool) data_get($ipWhoIsResponse->json(), 'success', false)) {
                $city = trim((string) data_get($ipWhoIsResponse->json(), 'city', ''));
                if ($city !== '') {
                    return $city;
                }
            }
        } catch (\Throwable $th) {
        }

        return null;
    }

    private function countryDialCodeMap(): array
    {
        return [
            'IN' => '+91',
            'US' => '+1',
            'AU' => '+61',
            'DE' => '+49',
        ];
    }
}
