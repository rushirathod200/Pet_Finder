@php
    $activeNav = $activeNav ?? '';
    $linkClass = function (string $key) use ($activeNav): string {
        if ($activeNav === $key) {
            return 'flex items-center gap-3 px-3 py-2.5 rounded-lg bg-primary/10 dark:bg-primary/20 text-primary group transition-colors';
        }

        return 'flex items-center gap-3 px-3 py-2.5 rounded-lg hover:bg-[#f0f2f4] dark:hover:bg-gray-700 text-[#111518] dark:text-gray-200 group transition-colors';
    };
    $iconClass = function (string $key) use ($activeNav): string {
        if ($activeNav === $key) {
            return 'material-symbols-outlined text-primary group-hover:scale-105 transition-transform';
        }

        return 'material-symbols-outlined text-[#617789] dark:text-gray-400 group-hover:text-[#111518] dark:group-hover:text-white';
    };
    $labelClass = function (string $key) use ($activeNav): string {
        return $activeNav === $key ? 'text-sm font-bold leading-normal' : 'text-sm font-medium leading-normal';
    };
@endphp

<aside class="w-72 bg-white dark:bg-[#1a2632] border-r border-[#e6e8eb] dark:border-gray-700 flex flex-col h-full flex-shrink-0 transition-colors z-20">
    <div class="p-6 flex flex-col h-full">
        <div class="flex items-center gap-3 mb-8">
            <img
                src="{{ $user->profile_picture_display_url ?? asset('images/avatar-placeholder.svg') }}"
                alt="{{ $user->fullname ?? 'User' }}"
                class="rounded-full size-12 object-cover"
                onerror="this.onerror=null;this.src='{{ asset('images/avatar-placeholder.svg') }}';"
            >
            <div class="flex flex-col">
                <h1 class="text-[#111518] dark:text-white text-base font-bold leading-normal">{{ $user->fullname ?? 'User' }}</h1>
            </div>
        </div>

        <nav class="flex flex-col gap-2 flex-1">
            <a class="{{ $linkClass('profile') }}" href="/profile">
                <span class="{{ $iconClass('profile') }}">dashboard</span>
                <p class="{{ $labelClass('profile') }}">Dashboard</p>
            </a>
            <a class="{{ $linkClass('home') }}" href="/">
                <span class="{{ $iconClass('home') }}">home</span>
                <p class="{{ $labelClass('home') }}">Home</p>
            </a>
            <a class="{{ $linkClass('myapplication') }}" href="/myapplication">
                <span class="{{ $iconClass('myapplication') }}">description</span>
                <p class="{{ $labelClass('myapplication') }}">My Applications</p>
            </a>
            <a class="{{ $linkClass('listapet') }}" href="/listapet">
                <span class="{{ $iconClass('listapet') }}">favorite</span>
                <p class="{{ $labelClass('listapet') }}">List a pet</p>
            </a>
            <a class="{{ $linkClass('mylisting') }}" href="/mylisting">
                <span class="{{ $iconClass('mylisting') }}">pets</span>
                <p class="{{ $labelClass('mylisting') }}">My Listings</p>
            </a>
            <a class="{{ $linkClass('lostpet') }}" href="/lost-pet-requests">
                <span class="{{ $iconClass('lostpet') }}">crisis_alert</span>
                <p class="{{ $labelClass('lostpet') }}">Report Lost Pet</p>
            </a>
            <a class="{{ $linkClass('messages') }}" href="/messages">
                <div class="relative">
                    <span class="{{ $iconClass('messages') }}">chat_bubble</span>
                    <span class="absolute top-0 right-0 size-2 bg-red-500 rounded-full border-2 border-white dark:border-[#1a2632]"></span>
                </div>
                <p class="{{ $labelClass('messages') }}">Messages</p>
            </a>
            <a class="{{ $linkClass('settings') }}" href="/settings">
                <span class="{{ $iconClass('settings') }}">settings</span>
                <p class="{{ $labelClass('settings') }}">Settings</p>
            </a>
        </nav>

        <a href="/logout" class="flex w-full cursor-pointer items-center justify-center gap-2 overflow-hidden rounded-lg h-10 px-4 bg-[#f0f2f4] dark:bg-gray-700 hover:bg-[#e1e4e8] dark:hover:bg-gray-600 text-[#111518] dark:text-white text-sm font-bold leading-normal transition-colors mt-auto">
            <span class="material-symbols-outlined text-lg">logout</span>
            <span class="truncate">Log Out</span>
        </a>
    </div>
</aside>
