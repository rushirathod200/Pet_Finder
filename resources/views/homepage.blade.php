<!DOCTYPE html>

<html class="light" lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Pet Finder - Find Your New Best Friend</title>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com" rel="preconnect" />
    <link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect" />
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;700;800&amp;family=Noto+Sans:wght@400;500;700&amp;display=swap" rel="stylesheet" />
    <!-- Material Icons -->
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet" />
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <!-- Theme Configuration -->
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
                        "display": ["Plus Jakarta Sans", "sans-serif"],
                        "body": ["Noto Sans", "sans-serif"],
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
        /* Custom scrollbar hiding for clean UI */
        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }

        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
    </style>
</head>

<body class="bg-background-light dark:bg-background-dark text-[#111518] dark:text-white font-display overflow-x-hidden antialiased">
<div class="relative flex h-auto min-h-screen w-full flex-col group/design-root">
        <!-- Top Navigation -->
        <div class="layout-container flex w-full flex-col bg-white dark:bg-background-dark border-b border-[#f0f2f4] dark:border-slate-800 sticky top-0 z-50">
            <div class="px-4 md:px-10 lg:px-40 flex justify-center">
                <div class="layout-content-container flex flex-col max-w-[1200px] flex-1">
                    <header class="flex items-center justify-between whitespace-nowrap py-4">
                        <a href="/" class="flex items-center gap-3 font-bold text-lg text-[#111518] dark:text-white">
                            <span class="material-symbols-outlined text-[#2b93ee]">pets</span>
                            <span>Pet Finder</span>
                        </a>
                        <div class="hidden lg:flex flex-1 justify-end gap-8 items-center">
                            <div class="flex items-center gap-9">
                                <a class="text-sm font-medium hover:text-primary transition-colors" href="/browse">Browse Pets</a>
                                <a class="text-sm font-medium hover:text-primary transition-colors" href="/how-it-works">How it Works</a>
                                <a class="text-sm font-medium hover:text-primary transition-colors" href="/listapet">Volunteer</a>
                            </div>
                            <div class="flex gap-2">
                                <a href="/login" class="flex min-w-[84px] cursor-pointer items-center justify-center overflow-hidden rounded-full h-10 px-6 bg-primary hover:bg-blue-600 transition-colors text-white text-sm font-bold leading-normal tracking-[0.015em]">
                                    <span class="truncate">Sign Up</span>
                                </a>
                                <a href="/login" class="flex min-w-[84px] cursor-pointer items-center justify-center overflow-hidden rounded-full h-10 px-6 bg-[#f0f2f4] dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 transition-colors text-[#111518] dark:text-white text-sm font-bold leading-normal tracking-[0.015em]">
                                    <span class="truncate">Log In</span>
                                </a>
                            </div>
                        </div>
                        <!-- Mobile Menu Icon (Placeholder) -->
                        <div class="lg:hidden text-[#111518] dark:text-white">
                            <span class="material-symbols-outlined cursor-pointer">menu</span>
                        </div>
                    </header>
                </div>
            </div>
        </div>
        <!-- Hero Section -->
        <div class="px-4 md:px-10 lg:px-40 flex flex-1 justify-center py-5 bg-white dark:bg-background-dark">
            <div class="layout-content-container flex flex-col max-w-[1200px] flex-1">
                <div class="@container">
                    <div class="flex flex-col gap-6 py-10 @[480px]:gap-8 @[864px]:flex-row-reverse items-center">
                        <div class="w-full bg-center bg-no-repeat aspect-video bg-cover rounded-xl @[480px]:h-auto @[864px]:w-1/2 shadow-lg" data-alt="Happy golden retriever dog smiling at camera in a park" style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuBBVrNNjrRVvRjTOstftuCN0WNah8dGZ5PitksJ2KnV_BbdaKBOB4DqpJ8VdyPlyQaQm2SSTBsoEKHGkeFPYyfkSl_WJ-yUL_i4icG9ndShIqLRQa4egNXRvznFJSFOtXph9q_T2mP_C3yJ5OPESBeDaMHtCetoR5M-joP_A3NDhQBVtcHOrD5USUSxGkK1o4vxEIJa_Fh8uCNaEz5WEa9C-m8-_aHC--1522m69ZUodc_Ui9s0P5Bw8e4fLZWOJcG3WgurbzxrFZGq");'>
                        </div>
                        <div class="flex flex-col gap-6 @[864px]:w-1/2 @[864px]:pr-10">
                            <div class="flex flex-col gap-4 text-left">
                                <h1 class="text-[#111518] dark:text-white text-4xl font-extrabold leading-tight tracking-[-0.033em] @[480px]:text-5xl lg:text-6xl">
                                    Find Your New <span class="text-primary">Best Friend</span> Today
                                </h1>
                                <h2 class="text-[#617789] dark:text-slate-300 text-lg font-normal leading-relaxed">
                                    We connect loving families with rescue dogs who need a forever home. Start your journey of companionship with a furry friend.
                                </h2>
                            </div>
                            <div class="flex flex-wrap gap-3">
                                <a href="/browse" class="flex min-w-[84px] cursor-pointer items-center justify-center overflow-hidden rounded-full h-12 px-6 bg-primary hover:bg-blue-600 text-white text-base font-bold leading-normal tracking-[0.015em] transition-all shadow-md hover:shadow-lg">
                                    <span class="truncate">Browse Dogs</span>
                                </a>
                                <a href="/how-it-works" class="flex min-w-[84px] cursor-pointer items-center justify-center overflow-hidden rounded-full h-12 px-6 bg-white dark:bg-slate-800 border border-[#dbe1e6] dark:border-slate-700 text-[#111518] dark:text-white text-base font-bold leading-normal tracking-[0.015em] hover:bg-gray-50 dark:hover:bg-slate-700 transition-all">
                                    <span class="truncate">Learn More</span>
                                </a>
                            </div>
                            <!-- Trust Indicators -->
                            <div class="flex gap-6 mt-4 opacity-80">
                                <div class="flex flex-col">
                                    <span class="text-xl font-bold text-[#111518] dark:text-white">2k+</span>
                                    <span class="text-xs text-[#617789] dark:text-slate-400">Happy Adoptions</span>
                                </div>
                                <div class="flex flex-col">
                                    <span class="text-xl font-bold text-[#111518] dark:text-white">500+</span>
                                    <span class="text-xs text-[#617789] dark:text-slate-400">Waiting Dogs</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Features / Mission Section -->
        <div class="px-4 md:px-10 lg:px-40 flex flex-1 justify-center py-5 bg-[#f8fafc] dark:bg-[#15202b]">
            <div class="layout-content-container flex flex-col max-w-[1200px] flex-1">
                <div class="flex flex-col gap-10 py-10 @container">
                    <div class="flex flex-col md:flex-row justify-between items-start md:items-end gap-6">
                        <div class="flex flex-col gap-4 max-w-[720px]">
                            <h1 class="text-[#111518] dark:text-white tracking-tight text-3xl font-bold leading-tight @[480px]:text-4xl">
                                Our Mission
                            </h1>
                            <p class="text-[#617789] dark:text-slate-300 text-lg font-normal leading-relaxed">
                                Every dog deserves a loving home. We work tirelessly to rescue, rehabilitate, and rehome dogs of all breeds and ages.
                            </p>
                        </div>
                        <button class="flex min-w-[84px] cursor-pointer items-center justify-center overflow-hidden rounded-full h-10 px-5 bg-primary/10 dark:bg-primary/20 text-primary text-sm font-bold leading-normal hover:bg-primary/20 dark:hover:bg-primary/30 transition-colors whitespace-nowrap">
                            <span class="truncate">Read Our Story</span>
                        </button>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 p-0">
                        <!-- Feature 1 -->
                        <div class="flex flex-1 gap-4 rounded-xl border border-[#dbe1e6] dark:border-slate-700 bg-white dark:bg-slate-900 p-6 flex-col shadow-sm hover:shadow-md transition-shadow">
                            <div class="text-primary bg-primary/10 w-12 h-12 rounded-full flex items-center justify-center">
                                <span class="material-symbols-outlined text-[28px]">pets</span>
                            </div>
                            <div class="flex flex-col gap-2">
                                <h2 class="text-[#111518] dark:text-white text-lg font-bold leading-tight">Rescue</h2>
                                <p class="text-[#617789] dark:text-slate-400 text-sm font-normal leading-relaxed">We save dogs from high-kill shelters and unsafe situations, giving them a second chance at life.</p>
                            </div>
                        </div>
                        <!-- Feature 2 -->
                        <div class="flex flex-1 gap-4 rounded-xl border border-[#dbe1e6] dark:border-slate-700 bg-white dark:bg-slate-900 p-6 flex-col shadow-sm hover:shadow-md transition-shadow">
                            <div class="text-primary bg-primary/10 w-12 h-12 rounded-full flex items-center justify-center">
                                <span class="material-symbols-outlined text-[28px]">healing</span>
                            </div>
                            <div class="flex flex-col gap-2">
                                <h2 class="text-[#111518] dark:text-white text-lg font-bold leading-tight">Rehabilitate</h2>
                                <p class="text-[#617789] dark:text-slate-400 text-sm font-normal leading-relaxed">We provide comprehensive medical care, behavioral training, and love to prepare them for families.</p>
                            </div>
                        </div>
                        <!-- Feature 3 -->
                        <div class="flex flex-1 gap-4 rounded-xl border border-[#dbe1e6] dark:border-slate-700 bg-white dark:bg-slate-900 p-6 flex-col shadow-sm hover:shadow-md transition-shadow">
                            <div class="text-primary bg-primary/10 w-12 h-12 rounded-full flex items-center justify-center">
                                <span class="material-symbols-outlined text-[28px]">home</span>
                            </div>
                            <div class="flex flex-col gap-2">
                                <h2 class="text-[#111518] dark:text-white text-lg font-bold leading-tight">Rehome</h2>
                                <p class="text-[#617789] dark:text-slate-400 text-sm font-normal leading-relaxed">We carefully match dogs with the perfect forever families through a thoughtful vetting process.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Carousel / Featured Section -->
        <div class="px-4 md:px-10 lg:px-40 flex flex-1 justify-center py-5 bg-white dark:bg-background-dark">
            <div class="layout-content-container flex flex-col max-w-[1200px] flex-1">
                <div class="flex justify-between items-center px-4 pb-6 pt-5">
                    <h2 class="text-[#111518] dark:text-white text-[28px] font-bold leading-tight tracking-[-0.015em]">Meet Our Happy Pups</h2>
                    <a class="text-primary font-medium text-sm flex items-center gap-1 hover:gap-2 transition-all" href="/browse">
                        View All <span class="material-symbols-outlined text-sm">arrow_forward</span>
                    </a>
                </div>
                <div class="relative">
                    <button id="happyPupsPrev" type="button" aria-label="Scroll left" class="hidden md:flex absolute left-0 top-1/2 -translate-y-1/2 z-10 h-10 w-10 items-center justify-center rounded-full bg-white/90 dark:bg-slate-800/90 border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-200 shadow hover:bg-white dark:hover:bg-slate-800 transition-colors">
                        <span class="material-symbols-outlined text-[20px]">chevron_left</span>
                    </button>
                    <button id="happyPupsNext" type="button" aria-label="Scroll right" class="hidden md:flex absolute right-0 top-1/2 -translate-y-1/2 z-10 h-10 w-10 items-center justify-center rounded-full bg-white/90 dark:bg-slate-800/90 border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-200 shadow hover:bg-white dark:hover:bg-slate-800 transition-colors">
                        <span class="material-symbols-outlined text-[20px]">chevron_right</span>
                    </button>
                    <div id="happyPupsScroller" class="flex overflow-x-auto no-scrollbar pb-8 -mx-4 px-4 snap-x snap-mandatory scroll-smooth">
                    <div class="flex items-stretch gap-6 pr-2">
                        @forelse (($featuredPets ?? collect()) as $pet)
                            <a href="/petdetails/{{ $pet->id }}" class="flex h-full flex-col gap-4 rounded-xl min-w-[280px] w-[280px] snap-center group cursor-pointer">
                                <div class="w-full bg-center bg-no-repeat aspect-[3/4] bg-cover rounded-xl flex flex-col relative overflow-hidden shadow-md">
                                    <img
                                        src="{{ $pet->image_display_url ?? asset('images/pet-placeholder.svg') }}"
                                        alt="{{ $pet->name }}"
                                        class="absolute inset-0 h-full w-full object-cover transition-transform duration-500 group-hover:scale-105"
                                        onerror="this.onerror=null;this.src='{{ asset('images/pet-placeholder.svg') }}';"
                                    >
                                    <div class="absolute inset-0 bg-black/0 group-hover:bg-black/10 transition-colors"></div>
                                    <div class="absolute bottom-3 right-3 bg-white/90 dark:bg-black/70 backdrop-blur-sm rounded-full p-2 text-primary">
                                        <span class="material-symbols-outlined text-sm block">favorite</span>
                                    </div>
                                </div>
                                <div>
                                    <div class="flex justify-between items-start">
                                        <p class="text-[#111518] dark:text-white text-lg font-bold leading-normal">{{ $pet->name }}</p>
                                        <span class="bg-green-100 dark:bg-green-900 text-green-700 dark:text-green-300 text-xs px-2 py-1 rounded-md font-medium">
                                            {{ $pet->adopted ? 'Adopted' : 'Available' }}
                                        </span>
                                    </div>
                                    <p class="text-[#617789] dark:text-slate-400 text-sm font-normal leading-normal mt-1">{{ $pet->breed }} • {{ $pet->age }} yrs • {{ $pet->city }}</p>
                                </div>
                            </a>
                        @empty
                            <div class="rounded-xl border border-dashed border-[#dbe1e6] dark:border-slate-700 p-8 min-w-[320px] bg-white dark:bg-slate-900 text-center">
                                <p class="text-[#617789] dark:text-slate-400 text-sm">No featured pups right now. Check back soon.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
                </div>
            </div>
        </div>
        <!-- Call to Action Banner -->
        <div class="px-4 md:px-10 lg:px-40 flex flex-1 justify-center py-10 bg-white dark:bg-background-dark">
            <div class="layout-content-container flex flex-col max-w-[1200px] flex-1">
                <div class="rounded-2xl bg-primary p-8 md:p-12 flex flex-col md:flex-row items-center justify-between gap-8 text-white relative overflow-hidden">
                    <div class="absolute top-0 right-0 opacity-10 transform translate-x-10 -translate-y-10">
                        <span class="material-symbols-outlined text-[200px]">pets</span>
                    </div>
                    <div class="flex flex-col gap-3 max-w-[600px] z-10">
                        <h2 class="text-3xl md:text-4xl font-bold leading-tight">Ready to meet your new friend?</h2>
                        <p class="text-blue-100 text-lg">Join our community today to save favorites, apply for adoption, and get updates on new rescues.</p>
                    </div>
                    <div class="flex gap-4 z-10 w-full md:w-auto flex-col sm:flex-row">
                        <a href="/login" class="flex cursor-pointer items-center justify-center overflow-hidden rounded-full h-12 px-8 bg-white text-primary text-base font-bold leading-normal hover:bg-blue-50 transition-colors shadow-lg">
                            <span class="truncate">Create Account</span>
</a>
                    </div>
                </div>
            </div>
        </div>
        <!-- Footer -->
        <footer class="bg-white dark:bg-background-dark border-t border-[#f0f2f4] dark:border-slate-800">
            <div class="px-4 md:px-10 lg:px-40 py-10 flex justify-center">
                <div class="layout-content-container flex flex-col max-w-[1200px] flex-1">
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-8 mb-10">
                        <div class="col-span-2 md:col-span-1 flex flex-col gap-4">
                            <a href="/" class="flex items-center gap-3 font-bold text-lg text-[#111518] dark:text-white">
                                <span class="material-symbols-outlined text-[#2b93ee]">pets</span>
                                <span>Pet Finder</span>
                            </a>
                            <p class="text-[#617789] dark:text-slate-400 text-sm">Connecting loving families with rescue dogs since 2010.</p>
                        </div>
                        <div class="flex flex-col gap-3">
                            <h4 class="text-[#111518] dark:text-white font-bold text-base">Adopt</h4>
                            <a class="text-[#617789] dark:text-slate-400 text-sm hover:text-primary" href="/browse">Available Dogs</a>
                            <a class="text-[#617789] dark:text-slate-400 text-sm hover:text-primary" href="/how-it-works">Adoption Process</a>
                        </div>
                        <div class="flex flex-col gap-3">
                            <h4 class="text-[#111518] dark:text-white font-bold text-base">Get Involved</h4>
                            <a class="text-[#617789] dark:text-slate-400 text-sm hover:text-primary" href="/listapet">Volunteer</a>
           
                        </div>
                        <div class="flex flex-col gap-3">
                            <h4 class="text-[#111518] dark:text-white font-bold text-base">Contact</h4>
                            <a class="text-[#617789] dark:text-slate-400 text-sm hover:text-primary" href="mailto:ume956962@gmail.com">Email Us</a>
                       
                        </div>
                    </div>
                    <div class="pt-8 border-t border-[#f0f2f4] dark:border-slate-800 flex flex-col md:flex-row justify-between items-center gap-4">
                        <p class="text-[#617789] dark:text-slate-400 text-sm">© 2026 Pet Finder Adoption. All rights reserved.</p>
                        
                    </div>
                </div>
            </div>
        </footer>
    </div>
    <script>
        (function () {
            const scroller = document.getElementById('happyPupsScroller');
            const prev = document.getElementById('happyPupsPrev');
            const next = document.getElementById('happyPupsNext');

            if (!scroller || !prev || !next) {
                return;
            }

            const firstCard = scroller.querySelector('a');
            const cardWidth = firstCard ? firstCard.getBoundingClientRect().width : 280;
            const gap = 24;
            const distance = cardWidth + gap;

            prev.addEventListener('click', function () {
                scroller.scrollBy({ left: -distance, behavior: 'smooth' });
            });

            next.addEventListener('click', function () {
                scroller.scrollBy({ left: distance, behavior: 'smooth' });
            });
        })();
    </script>
</body>

</html>
