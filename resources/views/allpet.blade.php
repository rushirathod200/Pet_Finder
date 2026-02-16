<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Browse Pets</title>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com?plugins=forms"></script>
</head>
<body class="bg-slate-50 text-slate-900">
<header class="border-b bg-white">
        <div class="mx-auto flex max-w-6xl items-center justify-between px-4 py-4">
            <a href="/" class="flex items-center gap-2 text-xl font-bold">
                <span class="material-symbols-outlined text-[#2b93ee]">pets</span>
                <span>Pet Finder</span>
            </a>
            <nav class="flex items-center gap-4 text-sm">
                <a href="/browse" class="text-blue-600">Browse</a>
                @if (session()->has('useremail'))
                    <a href="/profile">Profile</a>
                    <a href="/listapet">List a Pet</a>
                    <a href="/logout" class="text-red-600">Logout</a>
                @else
                    <a href="/login" class="rounded bg-blue-600 px-3 py-1 text-white">Login</a>
                @endif
            </nav>
        </div>
    </header>

    <main class="mx-auto max-w-6xl px-4 py-8">
        <div class="mb-6 flex flex-col gap-4 md:flex-row md:items-end md:justify-between">
            <div>
                <h1 class="text-3xl font-bold">Available Pets</h1>
                <p class="text-slate-600">Find your next companion.</p>
            </div>
            <form method="GET" action="/browse" class="flex w-full gap-2 md:w-auto">
                <input
                    type="text"
                    name="q"
                    value="{{ request('q') }}"
                    placeholder="Search name, breed, city"
                    class="w-full rounded border border-slate-300 px-3 py-2 md:w-80"
                >
                <button type="submit" class="rounded bg-blue-600 px-4 py-2 font-semibold text-white">Search</button>
            </form>
        </div>

        @if ($pets->isEmpty())
            <div class="rounded border border-dashed border-slate-300 bg-white p-8 text-center text-slate-600">
                No pets found.
            </div>
        @else
            <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($pets as $pet)
                    <article class="overflow-hidden rounded-xl border bg-white shadow-sm">
                        <div class="h-48 bg-slate-100">
                            <img
                                src="{{ $pet->image_display_url ?? asset('images/pet-placeholder.svg') }}"
                                alt="{{ $pet->name }}"
                                class="h-full w-full object-cover"
                                onerror="this.onerror=null;this.src='{{ asset('images/pet-placeholder.svg') }}';"
                            >
                        </div>
                        <div class="space-y-2 p-4">
                            <div class="flex items-start justify-between gap-2">
                                <h2 class="text-lg font-bold">{{ $pet->name }}</h2>
                                <span class="rounded bg-slate-100 px-2 py-1 text-xs font-semibold uppercase">{{ $pet->gender }}</span>
                            </div>
                            <p class="text-sm text-slate-600">{{ $pet->breed }} · {{ $pet->age }} years · {{ $pet->size ?? 'N/A' }}</p>
                            <p class="text-sm text-slate-500">{{ $pet->city }}</p>
                            <a href="/petdetails/{{ $pet->id }}" class="inline-flex rounded bg-blue-600 px-3 py-2 text-sm font-semibold text-white">View Details</a>
                        </div>
                    </article>
                @endforeach
            </div>

            <div class="mt-8">
                {{ $pets->links() }}
            </div>
        @endif
    </main>
</body>
</html>
