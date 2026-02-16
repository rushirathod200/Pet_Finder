<!DOCTYPE html>
<html class="light" lang="en"><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>User Dashboard - Dog Adoption</title>
<link href="https://fonts.googleapis.com" rel="preconnect"/>
<link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect"/>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,200..800;1,200..800&amp;display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<script id="tailwind-config">
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
        }
    </script>
<style>
        body {
            font-family: "Plus Jakarta Sans", sans-serif;
        }
        .scrollbar-hide::-webkit-scrollbar {
            display: none;
        }
        .scrollbar-hide {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
    </style>
</head>
<body class="bg-background-light dark:bg-background-dark text-[#111518] dark:text-gray-100 antialiased h-screen flex overflow-hidden">
@include('partials.dashboard-sidebar', ['user' => $user, 'activeNav' => 'profile'])
<main class="flex-1 h-full overflow-y-auto bg-background-light dark:bg-background-dark">
<div class="max-w-[1200px] mx-auto p-8 flex flex-col gap-8 pb-20">
<div class="flex flex-wrap gap-2 items-center">

</div>
<div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
<div>
    
<h1 class="text-[#111518] dark:text-white tracking-tight text-3xl lg:text-4xl font-bold leading-tight">Welcome back, {{$user->fullname}}!</h1>
<p class="text-[#617789] dark:text-gray-400 mt-2 text-base">Here's what's happening with your furry friends today.</p>
@if ($user->location)
<p class="text-[#617789] dark:text-gray-400 mt-1 text-sm">Detected city: {{ $user->location }}</p>
@endif
</div>

</div>
@if (session('success'))
<div class="rounded-lg border border-green-200 bg-green-50 p-3 text-green-700">{{ session('success') }}</div>
@endif
@if ($errors->any())
<div class="rounded-lg border border-red-200 bg-red-50 p-3 text-red-700">{{ $errors->first() }}</div>
@endif
<div class="grid grid-cols-1 md:grid-cols-3 gap-4">
<div class="flex flex-col gap-2 rounded-xl p-6 bg-white dark:bg-[#1a2632] border border-[#dbe1e6] dark:border-gray-700 shadow-sm hover:shadow-md transition-shadow">
<div class="flex justify-between items-start">
<p class="text-[#617789] dark:text-gray-400 text-sm font-semibold uppercase tracking-wider">Active Applications</p>
<span class="material-symbols-outlined text-primary">description</span>
</div>
<p class="text-[#111518] dark:text-white tracking-tight text-3xl font-bold leading-tight mt-2">{{$totalapplications ?? 0}}</p>
</div>
<div class="flex flex-col gap-2 rounded-xl p-6 bg-white dark:bg-[#1a2632] border border-[#dbe1e6] dark:border-gray-700 shadow-sm hover:shadow-md transition-shadow">
<div class="flex justify-between items-start">
<p class="text-[#617789] dark:text-gray-400 text-sm font-semibold uppercase tracking-wider">Saved Dogs</p>
<span class="material-symbols-outlined text-red-500 fill-current">favorite</span>
</div>
<p class="text-[#111518] dark:text-white tracking-tight text-3xl font-bold leading-tight mt-2">{{ $savedPetsCount ?? 0 }}</p>
</div>
<div class="flex flex-col gap-2 rounded-xl p-6 bg-white dark:bg-[#1a2632] border border-[#dbe1e6] dark:border-gray-700 shadow-sm hover:shadow-md transition-shadow">
<div class="flex justify-between items-start">
<p class="text-[#617789] dark:text-gray-400 text-sm font-semibold uppercase tracking-wider">Unread Messages</p>
<div class="relative">
<span class="material-symbols-outlined text-green-500">mail</span>
</div>
</div>
<p class="text-[#111518] dark:text-white tracking-tight text-3xl font-bold leading-tight mt-2">{{ $unreadMessagesCount ?? 0 }}</p>
</div>
</div>
<div class="bg-gradient-to-r from-[#2b93ee] to-[#1e7bc8] rounded-xl p-6 md:p-8 text-white shadow-md relative overflow-hidden group">
<div class="absolute right-0 top-0 h-full w-1/3 bg-white/10 skew-x-12 translate-x-10"></div>
<div class="flex flex-col md:flex-row items-center justify-between gap-6 relative z-10">
<div class="flex items-start gap-5">
<div class="hidden sm:flex size-14 rounded-full bg-white/20 items-center justify-center backdrop-blur-sm border border-white/20 shrink-0">
<span class="material-symbols-outlined text-3xl">pets</span>
</div>
<div class="flex flex-col gap-1">
<h3 class="text-xl font-bold">Have a dog looking for a home?</h3>
<p class="text-blue-100 text-sm max-w-xl leading-relaxed">Create a profile for a dog you wish to put up for adoption. Include photos, personality traits, and medical history to help them find a loving family.</p>
</div>
</div>
<button class="w-full md:w-auto px-6 py-3 bg-white text-primary hover:bg-gray-50 font-bold rounded-lg transition-colors whitespace-nowrap shadow-sm flex items-center justify-center gap-2">
<span class="material-symbols-outlined text-[20px]">edit_square</span>
                    List a Pet
                </button>
</div>
</div>
<div class="flex flex-col gap-4">
<div class="flex justify-between items-end">
<h2 class="text-[#111518] dark:text-white text-xl font-bold leading-tight">Application Status</h2>
<a class="text-primary text-sm font-bold hover:underline" href="/myapplication">View All</a>
</div>
<div class="bg-white dark:bg-[#1a2632] rounded-xl border border-[#dbe1e6] dark:border-gray-700 overflow-hidden shadow-sm">
@forelse ($applications as $application)
<div class="flex flex-col md:flex-row gap-6 p-6 items-start md:items-center {{ !$loop->last ? 'border-b border-[#e6e8eb] dark:border-gray-700' : '' }}">
<div class="size-20 rounded-lg bg-gray-200 bg-center bg-cover flex-shrink-0">
<img
    src="{{ $application->pet?->image_display_url ?? asset('images/pet-placeholder.svg') }}"
    alt="{{ $application->pet?->name ?? 'Pet image' }}"
    class="size-20 rounded-lg object-cover"
    onerror="this.onerror=null;this.src='{{ asset('images/pet-placeholder.svg') }}';"
>
</div>
<div class="flex-1">
<div class="flex items-center gap-2 mb-1">
<h3 class="text-[#111518] dark:text-white text-lg font-bold">{{ $application->pet?->name ?? 'Pet not available' }}</h3>
<span class="bg-[#f0f2f4] dark:bg-gray-700 text-[#617789] dark:text-gray-300 text-xs px-2 py-0.5 rounded font-medium">{{ $application->pet?->breed ?? 'N/A' }}</span>
</div>
<p class="text-[#617789] dark:text-gray-400 text-sm">Applied {{ $application->created_at?->diffForHumans() }} • ID: #{{ $application->id }}</p>
</div>
<div class="flex flex-col items-start md:items-end gap-3 min-w-[200px]">
<span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold capitalize {{ $application->status === 'approved' ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-200' : ($application->status === 'rejected' ? 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-200' : 'bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-200') }}">
<span class="size-2 rounded-full {{ $application->status === 'approved' ? 'bg-emerald-500' : ($application->status === 'rejected' ? 'bg-red-500' : 'bg-amber-500') }}"></span>
                            {{ $application->status ?? 'pending' }}
                        </span>
@if ($application->pet)
<a href="/petdetails/{{ $application->pet->id }}" class="flex items-center justify-center px-4 py-2 bg-white dark:bg-transparent border border-[#dbe1e6] dark:border-gray-600 text-[#111518] dark:text-white hover:bg-gray-50 dark:hover:bg-gray-700 text-sm font-bold rounded-lg transition-colors w-full md:w-auto">
                            View Details
                        </a>
@endif
</div>
</div>
@empty
<div class="p-8 text-center text-[#617789] dark:text-gray-400">No applications yet.</div>
@endforelse
</div>
</div>
<div class="flex flex-col gap-4">
<div class="flex justify-between items-end">
<h2 class="text-[#111518] dark:text-white text-xl font-bold leading-tight">Browse Dogs</h2>
<a class="text-primary text-sm font-bold hover:underline" href="/browse">See All</a>
</div>
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
@forelse ($browseDogs as $browseDog)
<div class="group flex flex-col bg-white dark:bg-[#1a2632] border border-[#dbe1e6] dark:border-gray-700 rounded-xl overflow-hidden shadow-sm hover:shadow-md transition-all hover:-translate-y-1">
<div class="relative h-48 bg-gray-200 overflow-hidden">
<img
    src="{{ $browseDog->image_display_url ?? asset('images/pet-placeholder.svg') }}"
    alt="{{ $browseDog->name }}"
    class="absolute inset-0 h-full w-full object-cover transition-transform duration-500 group-hover:scale-110"
    onerror="this.onerror=null;this.src='{{ asset('images/pet-placeholder.svg') }}';"
>
</div>
<div class="p-4 flex flex-col gap-3">
<div>
<h3 class="text-[#111518] dark:text-white text-lg font-bold">{{ $browseDog->name }}</h3>
<p class="text-[#617789] dark:text-gray-400 text-sm">{{ $browseDog->breed }} • {{ $browseDog->age }} yrs • {{ $browseDog->city }}</p>
</div>
<a href="/petdetails/{{ $browseDog->id }}" class="w-full mt-auto py-2 px-4 rounded-lg bg-primary/10 dark:bg-primary/20 text-primary font-bold text-sm hover:bg-primary hover:text-white transition-all text-center">
                            View Details
                        </a>
</div>
</div>
@empty
<div class="col-span-1 sm:col-span-2 lg:col-span-4 rounded-lg border border-dashed border-[#dbe1e6] dark:border-gray-700 p-8 text-center text-[#617789] dark:text-gray-400">
No active dogs available right now.
</div>
@endforelse
</div>
</div>
<div class="flex flex-col gap-4">
<div class="flex justify-between items-end">
<h2 class="text-[#111518] dark:text-white text-xl font-bold leading-tight">Saved Pups</h2>
<a class="text-primary text-sm font-bold hover:underline" href="/browse">Browse More</a>
</div>
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
@forelse ($savedPets as $savedPet)
<div class="group flex flex-col bg-white dark:bg-[#1a2632] border border-[#dbe1e6] dark:border-gray-700 rounded-xl overflow-hidden shadow-sm hover:shadow-md transition-all hover:-translate-y-1">
<div class="relative h-48 bg-gray-200 overflow-hidden">
<img
    src="{{ $savedPet->image_display_url ?? asset('images/pet-placeholder.svg') }}"
    alt="{{ $savedPet->name }}"
    class="absolute inset-0 h-full w-full object-cover transition-transform duration-500 group-hover:scale-110"
    onerror="this.onerror=null;this.src='{{ asset('images/pet-placeholder.svg') }}';"
>
</div>
<div class="p-4 flex flex-col gap-3">
<div>
<h3 class="text-[#111518] dark:text-white text-lg font-bold">{{ $savedPet->name }}</h3>
<p class="text-[#617789] dark:text-gray-400 text-sm">{{ $savedPet->breed }} • {{ $savedPet->age }} yrs • {{ $savedPet->gender }}</p>
</div>
<a href="/petdetails/{{ $savedPet->id }}" class="w-full mt-auto py-2 px-4 rounded-lg bg-primary/10 dark:bg-primary/20 text-primary font-bold text-sm hover:bg-primary hover:text-white transition-all text-center">
                            View Details
                        </a>
</div>
</div>
@empty
<div class="col-span-1 sm:col-span-2 lg:col-span-4 rounded-lg border border-dashed border-[#dbe1e6] dark:border-gray-700 p-8 text-center text-[#617789] dark:text-gray-400">
No saved dogs yet. Start by applying for a pet from the browse page.
</div>
@endforelse
</div>
</div>
</div>
</main>

</body></html>
