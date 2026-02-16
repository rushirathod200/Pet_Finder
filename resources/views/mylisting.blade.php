<!DOCTYPE html>
<html class="light" lang="en">
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>My Listings</title>
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
                    borderRadius: {
                        "DEFAULT": "0.25rem",
                        "lg": "0.5rem",
                        "xl": "0.75rem",
                        "full": "9999px"
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
    @include('partials.dashboard-sidebar', ['user' => $user, 'activeNav' => 'mylisting'])

    <main class="flex-1 h-full overflow-y-auto bg-background-light dark:bg-background-dark">
        <div class="max-w-[1200px] mx-auto p-6 md:p-8 flex flex-col gap-6 pb-20">
            <div class="flex items-end justify-between gap-4">
                <div>
                    <h1 class="text-[#111518] dark:text-white tracking-tight text-3xl font-bold leading-tight">My Listings</h1>
                    <p class="text-[#617789] dark:text-gray-400 mt-2">Manage pets you listed for adoption.</p>
                </div>
                <a href="/listapet" class="rounded-lg bg-primary px-4 py-2.5 text-sm font-bold text-white shadow-sm hover:bg-blue-600 transition-colors">Add New Pet</a>
            </div>

            @if (session('success'))
                <div class="rounded-lg border border-green-200 bg-green-50 p-3 text-green-700">{{ session('success') }}</div>
            @endif

            @if ($pets->isEmpty())
                <div class="rounded-xl border border-dashed border-[#dbe1e6] bg-white dark:bg-[#1a2632] dark:border-gray-700 p-8 text-center text-[#617789] dark:text-gray-300">
                    You have not listed any pets yet.
                </div>
            @else
                <div class="space-y-4">
                    @foreach ($pets as $pet)
                        <article class="grid gap-4 rounded-xl border border-[#dbe1e6] bg-white dark:bg-[#1a2632] dark:border-gray-700 p-4 shadow-sm md:grid-cols-[100px,1fr,auto] md:items-center">
                            <div class="h-24 w-24 overflow-hidden rounded bg-slate-100 dark:bg-gray-700">
                                <img
                                    src="{{ $pet->image_display_url ?? asset('images/pet-placeholder.svg') }}"
                                    alt="{{ $pet->name }}"
                                    class="h-full w-full object-cover"
                                    onerror="this.onerror=null;this.src='{{ asset('images/pet-placeholder.svg') }}';"
                                >
                            </div>

                            <div>
                                <h2 class="text-lg font-bold text-[#111518] dark:text-white">{{ $pet->name }}</h2>
                                <p class="text-sm text-[#617789] dark:text-gray-400">{{ $pet->breed }} · {{ $pet->age }} years · {{ $pet->city }}</p>
                                <p class="text-sm text-[#617789] dark:text-gray-400">Applications: {{ $pet->applications_count }}</p>
                                <span class="mt-2 inline-flex rounded px-2 py-1 text-xs font-semibold {{ $pet->adopted ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-300' : 'bg-amber-100 text-amber-700 dark:bg-amber-900/40 dark:text-amber-300' }}">
                                    {{ $pet->adopted ? 'Adopted' : 'Active' }}
                                </span>
                            </div>

                            <div class="flex flex-wrap items-center gap-2">
                                <a href="/petdetails/{{ $pet->id }}" class="rounded-lg border border-slate-300 dark:border-gray-600 px-3 py-2 text-sm font-semibold text-[#111518] dark:text-white hover:bg-slate-50 dark:hover:bg-gray-700">View</a>
                                <a href="/mylisting/{{ $pet->id }}/edit" class="rounded-lg border border-amber-300 px-3 py-2 text-sm font-semibold text-amber-700 dark:text-amber-300 dark:border-amber-700 hover:bg-amber-50 dark:hover:bg-amber-900/30">Edit</a>
                                <a href="/mylisting/{{ $pet->id }}/applications" class="rounded-lg border border-blue-300 px-3 py-2 text-sm font-semibold text-blue-700 dark:text-blue-300 dark:border-blue-700 hover:bg-blue-50 dark:hover:bg-blue-900/30">Applications ({{ $pet->applications_count }})</a>
                                @if (!$pet->adopted)
                                    <form method="POST" action="/mylisting/{{ $pet->id }}/adopt">
                                        @csrf
                                        <button type="submit" class="rounded-lg bg-emerald-600 px-3 py-2 text-sm font-semibold text-white hover:bg-emerald-700">Mark Adopted</button>
                                    </form>
                                @endif
                            </div>
                        </article>
                    @endforeach
                </div>
            @endif
        </div>
    </main>
</body>
</html>
