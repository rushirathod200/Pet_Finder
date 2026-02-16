<!DOCTYPE html>
<html class="light" lang="en">
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Edit Listing</title>
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
        <div class="max-w-[900px] mx-auto p-6 md:p-8 flex flex-col gap-6 pb-20">
            <div class="flex items-center justify-between gap-4">
                <div>
                    <h1 class="text-[#111518] dark:text-white tracking-tight text-3xl font-bold leading-tight">Edit Listing</h1>
                    <p class="text-[#617789] dark:text-gray-400 mt-2">Update your pet information.</p>
                </div>
                <a href="/mylisting" class="rounded-lg border border-slate-300 dark:border-gray-600 px-4 py-2.5 text-sm font-semibold text-[#111518] dark:text-white hover:bg-slate-50 dark:hover:bg-gray-700">Back</a>
            </div>

            @if ($errors->any())
                <div class="rounded-lg border border-red-200 bg-red-50 p-3 text-red-700">{{ $errors->first() }}</div>
            @endif

            <section class="rounded-xl border border-[#dbe1e6] bg-white dark:bg-[#1a2632] dark:border-gray-700 p-6 shadow-sm">
                <form method="POST" action="/mylisting/{{ $pet->id }}/edit" enctype="multipart/form-data" class="grid gap-4 md:grid-cols-2">
                    @csrf

                    <div class="md:col-span-2">
                        <label class="mb-1 block text-sm font-semibold">Pet Name</label>
                        <input type="text" name="petname" value="{{ old('petname', $pet->name) }}" class="w-full rounded-lg border border-slate-300 dark:border-gray-600 bg-white dark:bg-gray-800 px-3 py-2" required>
                    </div>

                    <div>
                        <label class="mb-1 block text-sm font-semibold">Breed</label>
                        <input type="text" name="breed" value="{{ old('breed', $pet->breed) }}" class="w-full rounded-lg border border-slate-300 dark:border-gray-600 bg-white dark:bg-gray-800 px-3 py-2" required>
                    </div>

                    <div>
                        <label class="mb-1 block text-sm font-semibold">Age (years)</label>
                        <input type="number" name="age" min="0" max="40" value="{{ old('age', $pet->age) }}" class="w-full rounded-lg border border-slate-300 dark:border-gray-600 bg-white dark:bg-gray-800 px-3 py-2" required>
                    </div>

                    <div>
                        <label class="mb-1 block text-sm font-semibold">Gender</label>
                        <select name="gender" class="w-full rounded-lg border border-slate-300 dark:border-gray-600 bg-white dark:bg-gray-800 px-3 py-2" required>
                            <option value="Male" {{ old('gender', $pet->gender) === 'Male' ? 'selected' : '' }}>Male</option>
                            <option value="Female" {{ old('gender', $pet->gender) === 'Female' ? 'selected' : '' }}>Female</option>
                        </select>
                    </div>

                    <div>
                        <label class="mb-1 block text-sm font-semibold">Size</label>
                        <select name="size" class="w-full rounded-lg border border-slate-300 dark:border-gray-600 bg-white dark:bg-gray-800 px-3 py-2" required>
                            <option value="small" {{ old('size', $pet->size) === 'small' ? 'selected' : '' }}>Small</option>
                            <option value="medium" {{ old('size', $pet->size) === 'medium' ? 'selected' : '' }}>Medium</option>
                            <option value="large" {{ old('size', $pet->size) === 'large' ? 'selected' : '' }}>Large</option>
                            <option value="xlarge" {{ old('size', $pet->size) === 'xlarge' ? 'selected' : '' }}>Extra Large</option>
                        </select>
                    </div>

                    <div class="md:col-span-2">
                        <label class="mb-1 block text-sm font-semibold">Location</label>
                        <input type="text" name="Location" value="{{ old('Location', $pet->city) }}" class="w-full rounded-lg border border-slate-300 dark:border-gray-600 bg-white dark:bg-gray-800 px-3 py-2" required>
                    </div>

                    <div class="md:col-span-2">
                        <label class="mb-1 block text-sm font-semibold">Description</label>
                        <textarea name="Description" rows="4" class="w-full rounded-lg border border-slate-300 dark:border-gray-600 bg-white dark:bg-gray-800 px-3 py-2">{{ old('Description', $pet->description) }}</textarea>
                    </div>

                    <div class="md:col-span-2">
                        <label class="mb-1 block text-sm font-semibold">Current Photo</label>
                        <div class="mb-3 h-36 w-36 overflow-hidden rounded-lg bg-slate-100 dark:bg-gray-700">
                            <img
                                src="{{ $pet->image_display_url ?? asset('images/pet-placeholder.svg') }}"
                                alt="{{ $pet->name }}"
                                class="h-full w-full object-cover"
                                onerror="this.onerror=null;this.src='{{ asset('images/pet-placeholder.svg') }}';"
                            >
                        </div>
                        <label class="mb-1 block text-sm font-semibold">Replace Photo (optional)</label>
                        <input type="file" name="Photos" accept="image/*" class="w-full rounded-lg border border-slate-300 dark:border-gray-600 bg-white dark:bg-gray-800 px-3 py-2">
                    </div>

                    <div class="md:col-span-2 flex items-center gap-3 pt-2">
                        <button type="submit" class="rounded-lg bg-primary px-4 py-2.5 text-sm font-bold text-white hover:bg-blue-600 transition-colors">Save Changes</button>
                        <a href="/mylisting" class="rounded-lg border border-slate-300 dark:border-gray-600 px-4 py-2.5 text-sm font-semibold text-[#111518] dark:text-white hover:bg-slate-50 dark:hover:bg-gray-700">Cancel</a>
                    </div>
                </form>
            </section>
        </div>
    </main>
</body>
</html>
