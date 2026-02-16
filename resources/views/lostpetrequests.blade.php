<!DOCTYPE html>
<html class="light" lang="en">
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Report Lost Pet</title>
    <link href="https://fonts.googleapis.com" rel="preconnect">
    <link crossorigin href="https://fonts.gstatic.com" rel="preconnect">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,200..800;1,200..800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <script>
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "primary": "#2b93ee",
                        "background-light": "#f6f7f8",
                        "background-dark": "#101a22",
                    },
                    fontFamily: {
                        "display": ["Plus Jakarta Sans", "sans-serif"]
                    },
                },
            },
        };
    </script>
    <style>
        body {
            font-family: "Plus Jakarta Sans", sans-serif;
        }
    </style>
</head>
<body class="bg-background-light dark:bg-background-dark text-[#111518] dark:text-gray-100 antialiased h-screen flex overflow-hidden">
    @include('partials.dashboard-sidebar', ['user' => $user, 'activeNav' => 'lostpet'])

    <main class="flex-1 h-full overflow-y-auto bg-background-light dark:bg-background-dark">
        <div class="max-w-[1200px] mx-auto p-6 md:p-8 flex flex-col gap-6 pb-20">
            <div>
                <h1 class="text-[#111518] dark:text-white tracking-tight text-3xl font-bold leading-tight">Report Lost Pet</h1>
                <p class="text-[#617789] dark:text-gray-400 mt-2">Create a city alert. Users in the same city will see this request and get email notification.</p>
            </div>

            @if (session('success'))
                <div class="rounded-lg border border-green-200 bg-green-50 p-3 text-green-700">{{ session('success') }}</div>
            @endif
            @if ($errors->any())
                <div class="rounded-lg border border-red-200 bg-red-50 p-3 text-red-700">{{ $errors->first() }}</div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <section class="rounded-xl border border-[#dbe1e6] dark:border-gray-700 bg-white dark:bg-[#1a2632] p-6 shadow-sm">
                    <h2 class="text-[#111518] dark:text-white text-xl font-bold leading-tight">New Lost Pet Request</h2>
                    <form action="/lost-pet-requests" method="POST" enctype="multipart/form-data" class="grid gap-3 mt-4">
                        @csrf
                        <div>
                            <label class="mb-1 block text-sm font-semibold">Pet Name</label>
                            <input type="text" name="pet_name" value="{{ old('pet_name') }}" class="w-full rounded-lg border border-slate-300 dark:border-gray-600 bg-white dark:bg-gray-800 px-3 py-2" required>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                            <div>
                                <label class="mb-1 block text-sm font-semibold">City</label>
                                <input type="text" name="city" value="{{ old('city', $user->location) }}" class="w-full rounded-lg border border-slate-300 dark:border-gray-600 bg-white dark:bg-gray-800 px-3 py-2" required>
                            </div>
                            <div>
                                <label class="mb-1 block text-sm font-semibold">Contact Phone</label>
                                <input type="text" name="contact_phone" value="{{ old('contact_phone', $user->phone) }}" class="w-full rounded-lg border border-slate-300 dark:border-gray-600 bg-white dark:bg-gray-800 px-3 py-2">
                            </div>
                        </div>

                        <div>
                            <label class="mb-1 block text-sm font-semibold">Last Seen Area</label>
                            <input type="text" name="last_seen_area" value="{{ old('last_seen_area') }}" class="w-full rounded-lg border border-slate-300 dark:border-gray-600 bg-white dark:bg-gray-800 px-3 py-2" placeholder="Street, landmark, or area">
                        </div>

                        <div>
                            <label class="mb-1 block text-sm font-semibold">Description</label>
                            <textarea name="description" rows="4" class="w-full rounded-lg border border-slate-300 dark:border-gray-600 bg-white dark:bg-gray-800 px-3 py-2" required>{{ old('description') }}</textarea>
                        </div>

                        <div>
                            <label class="mb-1 block text-sm font-semibold">Pet Photos (up to 5)</label>
                            <input type="file" name="photos[]" multiple accept="image/*" class="w-full rounded-lg border border-slate-300 dark:border-gray-600 bg-white dark:bg-gray-800 px-3 py-2">
                        </div>

                        <div>
                            <button type="submit" class="rounded-lg bg-primary px-4 py-2 font-semibold text-white hover:bg-blue-600 transition-colors">Submit Request</button>
                        </div>
                    </form>
                </section>

                <section class="rounded-xl border border-[#dbe1e6] dark:border-gray-700 bg-white dark:bg-[#1a2632] p-6 shadow-sm">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-[#111518] dark:text-white text-xl font-bold leading-tight">Lost Pet Alerts In Your City</h2>
                        <span class="text-xs text-[#617789] dark:text-gray-400">{{ $user->location ?: 'No city detected' }}</span>
                    </div>

                    <div class="space-y-3 max-h-[600px] overflow-y-auto pr-1">
                        @forelse ($localLostPetRequests as $alert)
                            <div class="rounded-lg border border-[#e6e8eb] dark:border-gray-700 p-3">
                                <div class="flex items-center justify-between gap-3">
                                    <h3 class="text-base font-bold text-[#111518] dark:text-white">{{ $alert->pet_name }}</h3>
                                    <span class="text-xs font-semibold uppercase text-red-600">Lost</span>
                                </div>
                                <p class="text-sm text-[#617789] dark:text-gray-400 mt-1">By {{ $alert->user?->fullname ?? 'Unknown user' }} • {{ $alert->created_at?->diffForHumans() }}</p>
                                @if ($alert->last_seen_area)
                                    <p class="text-sm mt-2"><span class="font-semibold">Last seen:</span> {{ $alert->last_seen_area }}</p>
                                @endif
                                <p class="text-sm mt-1">{{ $alert->description }}</p>
                                <p class="text-sm mt-1"><span class="font-semibold">Contact:</span> {{ $alert->contact_phone ?: ($alert->user?->phone ?? '-') }}</p>

                                @if (count($alert->photo_urls) > 0)
                                    <div class="mt-3 grid grid-cols-3 gap-2">
                                        @foreach ($alert->photo_urls as $photoUrl)
                                            <a href="{{ $photoUrl }}" target="_blank" rel="noopener noreferrer" class="block rounded-lg overflow-hidden border border-slate-200 dark:border-gray-700">
                                                <img src="{{ $photoUrl }}" alt="Lost pet photo" class="h-20 w-full object-cover" onerror="this.onerror=null;this.src='{{ asset('images/pet-placeholder.svg') }}';">
                                            </a>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        @empty
                            <div class="rounded-lg border border-dashed border-[#dbe1e6] dark:border-gray-700 p-6 text-center text-sm text-[#617789] dark:text-gray-400">
                                No lost pet alerts in your city yet.
                            </div>
                        @endforelse
                    </div>
                </section>
            </div>

            <section class="rounded-xl border border-[#dbe1e6] dark:border-gray-700 bg-white dark:bg-[#1a2632] p-6 shadow-sm">
                <h2 class="text-[#111518] dark:text-white text-xl font-bold leading-tight mb-4">My Lost Pet Requests</h2>
                <div class="space-y-3">
                    @forelse ($myLostPetRequests as $alert)
                        <div class="rounded-lg border border-[#e6e8eb] dark:border-gray-700 p-3">
                            <div class="flex items-center justify-between gap-3">
                                <h3 class="text-base font-bold text-[#111518] dark:text-white">{{ $alert->pet_name }}</h3>
                                <span class="text-xs font-semibold uppercase {{ $alert->status === 'open' ? 'text-red-600' : 'text-green-600' }}">{{ $alert->status }}</span>
                            </div>
                            <p class="text-sm text-[#617789] dark:text-gray-400 mt-1">City: {{ $alert->city }} • {{ $alert->created_at?->diffForHumans() }}</p>
                            <p class="text-sm mt-1">{{ $alert->description }}</p>
                        </div>
                    @empty
                        <div class="rounded-lg border border-dashed border-[#dbe1e6] dark:border-gray-700 p-6 text-center text-sm text-[#617789] dark:text-gray-400">
                            You have not created any lost pet request yet.
                        </div>
                    @endforelse
                </div>
            </section>
        </div>
    </main>
</body>
</html>
