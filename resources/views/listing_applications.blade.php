<!DOCTYPE html>
<html class="light" lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Applications - {{ $pet->name }}</title>
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
            <div>
                <a href="/mylisting" class="text-sm font-semibold text-primary hover:underline">Back to My Listings</a>
                <h1 class="mt-2 text-3xl font-bold text-[#111518] dark:text-white">Applications for {{ $pet->name }}</h1>
                <p class="text-[#617789] dark:text-gray-400">Review and respond to adoption requests.</p>
            </div>

            @if (session('success'))
                <div class="rounded-lg border border-green-200 bg-green-50 p-3 text-green-700">{{ session('success') }}</div>
            @endif

            @if ($applications->isEmpty())
                <div class="rounded-xl border border-dashed border-[#dbe1e6] bg-white dark:bg-[#1a2632] dark:border-gray-700 p-8 text-center text-[#617789] dark:text-gray-300">
                    No applications received yet.
                </div>
            @else
                <div class="space-y-4">
                    @foreach ($applications as $application)
                        <article class="rounded-xl border border-[#dbe1e6] bg-white dark:bg-[#1a2632] dark:border-gray-700 p-5 shadow-sm">
                            <div class="mb-3 flex flex-col gap-2 md:flex-row md:items-center md:justify-between">
                                <div>
                                    <h2 class="text-lg font-bold text-[#111518] dark:text-white">{{ $application->user?->fullname ?? 'Unknown applicant' }}</h2>
                                    <p class="text-sm text-[#617789] dark:text-gray-400">{{ $application->user?->email }} · {{ $application->user?->phone }}</p>
                                </div>
                                <span class="inline-flex rounded px-2 py-1 text-xs font-semibold capitalize {{ $application->status === 'approved' ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-300' : ($application->status === 'rejected' ? 'bg-red-100 text-red-700 dark:bg-red-900/40 dark:text-red-300' : 'bg-amber-100 text-amber-700 dark:bg-amber-900/40 dark:text-amber-300') }}">{{ $application->status }}</span>
                            </div>

                            <p class="mb-4 text-sm text-[#111518] dark:text-gray-200">{{ $application->description ?: 'No applicant note provided.' }}</p>

                            <div class="mb-4">
                                <a href="/messages?thread={{ urlencode($application->user_id.'|'.$pet->id) }}" class="inline-flex items-center rounded border border-blue-300 px-3 py-2 text-sm font-semibold text-blue-700 dark:text-blue-300 dark:border-blue-700 hover:bg-blue-50 dark:hover:bg-blue-900/30">
                                    Chat Applicant
                                </a>
                            </div>

                            <form method="POST" action="/mylisting/{{ $pet->id }}/applications/{{ $application->id }}/status" class="grid gap-3 md:grid-cols-[160px,1fr,auto] md:items-center">
                                @csrf
                                <select name="status" class="rounded border border-slate-300 dark:border-gray-600 bg-white dark:bg-gray-800 px-3 py-2">
                                    <option value="pending" @selected($application->status === 'pending')>Pending</option>
                                    <option value="approved" @selected($application->status === 'approved')>Approve</option>
                                    <option value="rejected" @selected($application->status === 'rejected')>Reject</option>
                                </select>
                                <input type="text" name="note" placeholder="Optional message to applicant" class="rounded border border-slate-300 dark:border-gray-600 bg-white dark:bg-gray-800 px-3 py-2">
                                <button type="submit" class="rounded bg-blue-600 px-4 py-2 font-semibold text-white">Update</button>
                            </form>
                        </article>
                    @endforeach
                </div>
            @endif
        </div>
    </main>
</body>
</html>
