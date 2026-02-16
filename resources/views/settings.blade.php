<!DOCTYPE html>
<html class="light" lang="en">
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Settings</title>
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
    @include('partials.dashboard-sidebar', ['user' => $user, 'activeNav' => 'settings'])

    <main class="flex-1 h-full overflow-y-auto bg-background-light dark:bg-background-dark">
        <div class="max-w-[1000px] mx-auto p-6 md:p-8 flex flex-col gap-6 pb-20">
            <h1 class="text-[#111518] dark:text-white tracking-tight text-3xl font-bold leading-tight">Settings</h1>

            @if (session('success'))
                <div class="rounded-lg border border-green-200 bg-green-50 p-3 text-green-700">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="rounded-lg border border-red-200 bg-red-50 p-3 text-red-700">{{ session('error') }}</div>
            @endif
            @if ($errors->any())
                <div class="rounded-lg border border-red-200 bg-red-50 p-3 text-red-700">{{ $errors->first() }}</div>
            @endif

            <section class="rounded-xl border border-[#dbe1e6] bg-white dark:bg-[#1a2632] dark:border-gray-700 p-6 shadow-sm">
                <h2 class="mb-4 text-xl font-bold text-[#111518] dark:text-white">Profile Information</h2>
                <form method="POST" action="/settings/profile" class="grid gap-4 md:grid-cols-2">
                    @csrf
                    <div class="md:col-span-2">
                        <label class="mb-1 block text-sm font-semibold">Full Name</label>
                        <input type="text" name="fullname" value="{{ old('fullname', $user->fullname) }}" class="w-full rounded-lg border border-slate-300 dark:border-gray-600 bg-white dark:bg-gray-800 px-3 py-2" required>
                    </div>

                    <div>
                        <label class="mb-1 block text-sm font-semibold">Email</label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}" class="w-full rounded-lg border border-slate-300 dark:border-gray-600 bg-white dark:bg-gray-800 px-3 py-2" required>
                    </div>

                    <div>
                        <label class="mb-1 block text-sm font-semibold">Phone</label>
                        <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" class="w-full rounded-lg border border-slate-300 dark:border-gray-600 bg-white dark:bg-gray-800 px-3 py-2" required>
                    </div>

                    <div class="md:col-span-2">
                        <label class="mb-1 block text-sm font-semibold">Detected City (IP)</label>
                        <input type="text" value="{{ $user->location ?: 'Unknown' }}" class="w-full rounded-lg border border-slate-300 dark:border-gray-600 bg-slate-50 dark:bg-gray-800 px-3 py-2" readonly>
                    </div>

                    <div class="md:col-span-2">
                        <button type="submit" class="rounded-lg bg-primary px-4 py-2 font-semibold text-white hover:bg-blue-600 transition-colors">Save Profile</button>
                    </div>
                </form>
            </section>

            <section class="rounded-xl border border-[#dbe1e6] bg-white dark:bg-[#1a2632] dark:border-gray-700 p-6 shadow-sm">
                <h2 class="mb-4 text-xl font-bold text-[#111518] dark:text-white">Change Password</h2>
                <form method="POST" action="/settings/password" class="grid gap-4 md:grid-cols-2">
                    @csrf
                    <div class="md:col-span-2">
                        <label class="mb-1 block text-sm font-semibold">Current Password</label>
                        <input type="password" name="current_password" class="w-full rounded-lg border border-slate-300 dark:border-gray-600 bg-white dark:bg-gray-800 px-3 py-2" required>
                    </div>

                    <div>
                        <label class="mb-1 block text-sm font-semibold">New Password</label>
                        <input type="password" name="new_password" class="w-full rounded-lg border border-slate-300 dark:border-gray-600 bg-white dark:bg-gray-800 px-3 py-2" required>
                    </div>

                    <div>
                        <label class="mb-1 block text-sm font-semibold">Confirm New Password</label>
                        <input type="password" name="new_password_confirmation" class="w-full rounded-lg border border-slate-300 dark:border-gray-600 bg-white dark:bg-gray-800 px-3 py-2" required>
                    </div>

                    <div class="md:col-span-2">
                        <button type="submit" class="rounded-lg bg-slate-900 dark:bg-gray-700 px-4 py-2 font-semibold text-white hover:bg-black dark:hover:bg-gray-600 transition-colors">Update Password</button>
                    </div>
                </form>
            </section>
        </div>
    </main>
</body>
</html>
