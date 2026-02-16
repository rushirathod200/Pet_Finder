<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $pet->name }} - Pet Details</title>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com?plugins=forms"></script>
</head>
<body class="bg-slate-50 text-slate-900">
<header class="border-b bg-white">
        <div class="mx-auto flex max-w-5xl items-center justify-between px-4 py-4">
            <a href="/browse" class="flex items-center gap-2 text-xl font-bold">
                <span class="material-symbols-outlined text-[#2b93ee]">pets</span>
                <span>Pet Finder</span>
            </a>
            <nav class="flex items-center gap-4 text-sm">
                <a href="/browse">Browse</a>
                @if (session()->has('useremail'))
                    <a href="/myapplication">My Applications</a>
                    <a href="/logout" class="text-red-600">Logout</a>
                @else
                    <a href="/login" class="rounded bg-blue-600 px-3 py-1 text-white">Login</a>
                @endif
            </nav>
        </div>
    </header>

    <main class="mx-auto max-w-5xl px-4 py-8">
        @if (session('success'))
            <div class="mb-4 rounded border border-green-200 bg-green-50 p-3 text-green-700">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="mb-4 rounded border border-red-200 bg-red-50 p-3 text-red-700">{{ session('error') }}</div>
        @endif

        <div class="grid gap-6 lg:grid-cols-2">
            <div class="overflow-hidden rounded-xl border bg-white">
                <div class="h-80 bg-slate-100">
                    <img
                        src="{{ $pet->image_display_url ?? asset('images/pet-placeholder.svg') }}"
                        alt="{{ $pet->name }}"
                        class="h-full w-full object-cover"
                        onerror="this.onerror=null;this.src='{{ asset('images/pet-placeholder.svg') }}';"
                    >
                </div>
            </div>

            <section class="rounded-xl border bg-white p-6">
                <h1 class="text-3xl font-bold">{{ $pet->name }}</h1>
                <p class="mt-2 text-slate-600">{{ $pet->breed }} · {{ $pet->gender }} · {{ $pet->age }} years</p>
                <p class="text-slate-500">{{ $pet->city }}</p>

                <dl class="mt-6 grid grid-cols-2 gap-3 text-sm">
                    <div class="rounded bg-slate-50 p-3">
                        <dt class="text-slate-500">Status</dt>
                        <dd class="font-semibold">{{ $pet->adopted ? 'Adopted' : 'Available' }}</dd>
                    </div>
                    <div class="rounded bg-slate-50 p-3">
                        <dt class="text-slate-500">Size</dt>
                        <dd class="font-semibold">{{ $pet->size ?? 'N/A' }}</dd>
                    </div>
                    <div class="col-span-2 rounded bg-slate-50 p-3">
                        <dt class="text-slate-500">Listed By</dt>
                        <dd class="font-semibold">{{ $pet->owner?->fullname ?? 'Unknown' }}</dd>
                    </div>
                </dl>

                <div class="mt-6">
                    <h2 class="mb-2 text-lg font-bold">About</h2>
                    <p class="text-slate-700">{{ $pet->description ?: 'No description provided.' }}</p>
                </div>

                <div class="mt-6">
                    @if (!session()->has('useremail'))
                        <a href="/login" class="inline-flex rounded bg-blue-600 px-4 py-2 font-semibold text-white">Login to Apply</a>
                    @elseif ($pet->adopted)
                        <button class="rounded bg-slate-300 px-4 py-2 font-semibold text-slate-600" disabled>Already Adopted</button>
                    @elseif ($pet->owner && session('useremail') === $pet->owner->email)
                        <button class="rounded bg-slate-300 px-4 py-2 font-semibold text-slate-600" disabled>Your Listing</button>
                    @else
                        <form method="POST" action="/petdetails/{{ $pet->id }}/apply" class="space-y-3">
                            @csrf
                            <textarea name="description" rows="3" class="w-full rounded border border-slate-300 px-3 py-2" placeholder="Optional note for the owner"></textarea>
                            <button type="submit" class="rounded bg-blue-600 px-4 py-2 font-semibold text-white">Apply for Adoption</button>
                        </form>
                    @endif
                </div>
            </section>
        </div>
    </main>
</body>
</html>
