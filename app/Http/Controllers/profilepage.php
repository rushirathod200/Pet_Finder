<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Mail\LostPetAlertMail;
use App\Models\usersdata;
use App\Models\application;
use App\Models\LostPetRequest;
use App\Models\Message;
use App\Models\pets;
use Illuminate\Support\Facades\Mail;

class profilepage extends Controller
{
    function index(Request $request)
    {
        $user = usersdata::where('email', $request->session()->get('useremail'))->first();
        if (!$user) {
            $request->session()->forget('useremail');
            return redirect('/login');
        }

        $applications = application::where('user_id', $user->id)
            ->with('pet')
            ->latest('id')
            ->take(5)
            ->get();

        $totalapplications = application::where('user_id', $user->id)->count();
        $savedPetIds = application::where('user_id', $user->id)
            ->where('status', '!=', 'rejected')
            ->whereNotNull('pet_id')
            ->distinct()
            ->pluck('pet_id');

        $savedPetsCount = $savedPetIds->count();
        $savedPets = pets::whereIn('id', $savedPetIds)->latest('id')->take(4)->get();
        $browseDogs = pets::where('adopted', false)
            ->latest('id')
            ->take(8)
            ->get();

        $unreadMessagesCount = Message::where('receiver_id', $user->id)
            ->whereNull('read_at')
            ->count();

        return view('profilepage')
            ->with('user', $user)
            ->with('totalapplications', $totalapplications)
            ->with('applications', $applications)
            ->with('savedPetsCount', $savedPetsCount)
            ->with('savedPets', $savedPets)
            ->with('browseDogs', $browseDogs)
            ->with('unreadMessagesCount', $unreadMessagesCount);
    }

    function lostPetRequests(Request $request)
    {
        $user = usersdata::where('email', $request->session()->get('useremail'))->first();
        if (!$user) {
            $request->session()->forget('useremail');
            return redirect('/login');
        }

        $localLostPetRequests = collect();
        if (filled($user->location)) {
            $localLostPetRequests = LostPetRequest::with('user')
                ->where('status', 'open')
                ->whereRaw('LOWER(city) = ?', [mb_strtolower((string) $user->location)])
                ->latest('id')
                ->take(20)
                ->get();
        }

        $myLostPetRequests = LostPetRequest::where('user_id', $user->id)
            ->latest('id')
            ->take(20)
            ->get();

        return view('lostpetrequests')
            ->with('user', $user)
            ->with('localLostPetRequests', $localLostPetRequests)
            ->with('myLostPetRequests', $myLostPetRequests);
    }

    function storeLostPetRequest(Request $request)
    {
        $user = usersdata::where('email', $request->session()->get('useremail'))->first();
        if (!$user) {
            $request->session()->forget('useremail');
            return redirect('/login');
        }

        $data = $request->validate([
            'pet_name' => ['required', 'string', 'max:120'],
            'city' => ['required', 'string', 'max:120'],
            'last_seen_area' => ['nullable', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:2000'],
            'contact_phone' => ['nullable', 'string', 'max:25'],
            'photos' => ['nullable', 'array', 'max:5'],
            'photos.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ]);

        $normalizedCity = $this->normalizeCity($data['city']);
        $photoPaths = [];
        foreach ($request->file('photos', []) as $photoFile) {
            $photoPaths[] = $photoFile->store('lost-pet-photos', 'public');
        }

        $lostPetRequest = LostPetRequest::create([
            'user_id' => $user->id,
            'pet_name' => trim((string) $data['pet_name']),
            'city' => $normalizedCity,
            'last_seen_area' => filled($data['last_seen_area'] ?? null) ? trim((string) $data['last_seen_area']) : null,
            'description' => trim((string) $data['description']),
            'contact_phone' => filled($data['contact_phone'] ?? null) ? trim((string) $data['contact_phone']) : $user->phone,
            'photos' => $photoPaths === [] ? null : $photoPaths,
            'status' => 'open',
        ]);

        if (!filled($user->location)) {
            $user->location = $normalizedCity;
            $user->save();
        }

        $this->sendLostPetRequestEmails($lostPetRequest, $user);

        return redirect('/lost-pet-requests')->with('success', 'Lost pet request submitted. Same-city users have been notified by email.');
    }

    private function sendLostPetRequestEmails(LostPetRequest $lostPetRequest, usersdata $requester): void
    {
        $cityKey = mb_strtolower((string) $lostPetRequest->city);

        $recipients = usersdata::where('id', '!=', $requester->id)
            ->whereNotNull('email')
            ->whereRaw('LOWER(location) = ?', [$cityKey])
            ->get();

        foreach ($recipients as $recipient) {
            try {
                Mail::to($recipient->email)->send(new LostPetAlertMail($lostPetRequest, $requester));
            } catch (\Throwable $th) {
                // Keep feature non-blocking even if one recipient mail fails.
            }
        }
    }

    private function normalizeCity(string $city): string
    {
        $trimmed = trim($city);
        if ($trimmed === '') {
            return $trimmed;
        }

        return mb_convert_case($trimmed, MB_CASE_TITLE, 'UTF-8');
    }

}
