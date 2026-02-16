<!DOCTYPE html>
<html class="light" lang="en">
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>How It Works - Pet Finder</title>
    <link href="https://fonts.googleapis.com" rel="preconnect">
    <link crossorigin href="https://fonts.gstatic.com" rel="preconnect">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;700;800&family=Noto+Sans:wght@400;500;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <script>
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        primary: "#2b93ee",
                        ink: "#0f172a",
                        mist: "#eef4ff",
                        mint: "#ecfdf3",
                        amber: "#fff7ed",
                    },
                    fontFamily: {
                        display: ["Plus Jakarta Sans", "Noto Sans", "sans-serif"],
                    },
                },
            },
        };
    </script>
</head>
<body class="bg-slate-50 text-ink font-display antialiased">
    <div class="min-h-screen">
        <header class="sticky top-0 z-40 bg-white/90 backdrop-blur border-b border-slate-200">
            <div class="max-w-6xl mx-auto px-4 md:px-8 py-4 flex items-center justify-between">
                <a href="/" class="flex items-center gap-3 font-bold text-lg">
                    <span class="material-symbols-outlined text-[#2b93ee]">pets</span>
                    <span>Pet Finder</span>
                </a>
                <div class="flex items-center gap-3">
                    <a href="/browse" class="text-sm font-semibold text-slate-600 hover:text-primary transition-colors">Browse Dogs</a>
                    <a href="/login" class="inline-flex items-center justify-center rounded-full bg-primary px-5 h-10 text-white text-sm font-bold hover:bg-blue-600 transition-colors">Get Started</a>
                </div>
            </div>
        </header>

        <main>
            <section class="relative overflow-hidden">
                <div class="absolute -top-24 -left-20 h-64 w-64 rounded-full bg-primary/20 blur-3xl"></div>
                <div class="absolute -bottom-20 -right-20 h-64 w-64 rounded-full bg-emerald-200/60 blur-3xl"></div>
                <div class="max-w-6xl mx-auto px-4 md:px-8 py-16 md:py-24 relative">
                    <div class="max-w-3xl">
                        <p class="inline-flex items-center gap-2 rounded-full bg-white border border-slate-200 px-3 py-1 text-xs font-semibold uppercase tracking-wide text-primary">
                            <span class="material-symbols-outlined text-sm">info</span>
                            Platform Guide
                        </p>
                        <h1 class="mt-4 text-4xl md:text-6xl font-extrabold leading-tight tracking-tight">
                            How Pet Finder Works
                        </h1>
                        <p class="mt-5 text-lg text-slate-600 leading-relaxed">
                            Adopt dogs, list pets for adoption, chat with owners/applicants in real-time, and raise city-wide lost-pet alerts with automatic email notifications.
                        </p>
                        <div class="mt-8 flex flex-wrap gap-3">
                            <a href="/browse" class="inline-flex items-center gap-2 rounded-xl bg-primary text-white px-5 py-3 font-bold hover:bg-blue-600 transition-colors">
                                <span class="material-symbols-outlined text-lg">search</span>
                                Explore Available Dogs
                            </a>
                            <a href="/login" class="inline-flex items-center gap-2 rounded-xl bg-white border border-slate-200 px-5 py-3 font-bold hover:bg-slate-100 transition-colors">
                                <span class="material-symbols-outlined text-lg">person_add</span>
                                Create Account
                            </a>
                        </div>
                    </div>
                </div>
            </section>

            <section class="max-w-6xl mx-auto px-4 md:px-8 pb-12">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="rounded-2xl bg-white border border-slate-200 p-5 shadow-sm">
                        <p class="text-sm font-semibold text-slate-500">Step 1</p>
                        <h3 class="mt-1 text-lg font-bold">Create Profile</h3>
                        <p class="mt-2 text-sm text-slate-600">Sign up using email/phone or Google. Your city is used to personalize alerts and recommendations.</p>
                    </div>
                    <div class="rounded-2xl bg-white border border-slate-200 p-5 shadow-sm">
                        <p class="text-sm font-semibold text-slate-500">Step 2</p>
                        <h3 class="mt-1 text-lg font-bold">Connect &amp; Apply</h3>
                        <p class="mt-2 text-sm text-slate-600">Browse active listings, open a pet detail page, and submit your adoption application with notes.</p>
                    </div>
                    <div class="rounded-2xl bg-white border border-slate-200 p-5 shadow-sm">
                        <p class="text-sm font-semibold text-slate-500">Step 3</p>
                        <h3 class="mt-1 text-lg font-bold">Chat &amp; Finalize</h3>
                        <p class="mt-2 text-sm text-slate-600">Use built-in messaging to coordinate visits, approvals, and handover without leaving the platform.</p>
                    </div>
                </div>
            </section>

            <section class="max-w-6xl mx-auto px-4 md:px-8 py-6">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <article class="rounded-2xl bg-white border border-slate-200 p-6 shadow-sm">
                        <div class="inline-flex size-11 items-center justify-center rounded-xl bg-mist text-primary">
                            <span class="material-symbols-outlined">favorite</span>
                        </div>
                        <h2 class="mt-4 text-2xl font-bold">Adoption Flow</h2>
                        <ol class="mt-4 space-y-3 text-sm text-slate-700">
                            <li class="flex gap-3"><span class="font-bold text-primary">1.</span> Open <a href="/browse" class="text-primary hover:underline">Browse Dogs</a> and filter by your preference.</li>
                            <li class="flex gap-3"><span class="font-bold text-primary">2.</span> View pet details and submit an application.</li>
                            <li class="flex gap-3"><span class="font-bold text-primary">3.</span> Track status on <strong>My Applications</strong> (pending/approved/rejected).</li>
                            <li class="flex gap-3"><span class="font-bold text-primary">4.</span> If approved, start direct chat with listing owner in <strong>Messages</strong>.</li>
                        </ol>
                    </article>

                    <article class="rounded-2xl bg-white border border-slate-200 p-6 shadow-sm">
                        <div class="inline-flex size-11 items-center justify-center rounded-xl bg-mint text-emerald-600">
                            <span class="material-symbols-outlined">pets</span>
                        </div>
                        <h2 class="mt-4 text-2xl font-bold">Listing Flow</h2>
                        <ol class="mt-4 space-y-3 text-sm text-slate-700">
                            <li class="flex gap-3"><span class="font-bold text-emerald-600">1.</span> Use <strong>List a pet</strong> to add dog info and image.</li>
                            <li class="flex gap-3"><span class="font-bold text-emerald-600">2.</span> Manage requests in <strong>My Listings</strong> and review applicants.</li>
                            <li class="flex gap-3"><span class="font-bold text-emerald-600">3.</span> Approve/reject applications and start direct conversation.</li>
                            <li class="flex gap-3"><span class="font-bold text-emerald-600">4.</span> Mark pet adopted when finalized.</li>
                        </ol>
                    </article>
                </div>
            </section>

            <section class="max-w-6xl mx-auto px-4 md:px-8 py-6">
                <div class="rounded-2xl bg-gradient-to-r from-amber-50 to-rose-50 border border-amber-200 p-6 md:p-8 shadow-sm">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 items-start">
                        <div>
                            <div class="inline-flex size-11 items-center justify-center rounded-xl bg-amber text-amber-700">
                                <span class="material-symbols-outlined">crisis_alert</span>
                            </div>
                            <h2 class="mt-4 text-2xl font-bold">Lost Pet Alert Flow</h2>
                            <p class="mt-3 text-sm text-slate-700 leading-relaxed">
                                If a pet is missing, go to <strong>Report Lost Pet</strong> from sidebar. Add city, last seen area, details, and photos.
                            </p>
                            <ul class="mt-4 space-y-2 text-sm text-slate-700">
                                <li class="flex gap-2"><span class="text-amber-700 font-bold">•</span> Request appears to users from the same city.</li>
                                <li class="flex gap-2"><span class="text-amber-700 font-bold">•</span> Same-city users receive automatic email alert.</li>
                                <li class="flex gap-2"><span class="text-amber-700 font-bold">•</span> Email includes details, photos, and direct call button.</li>
                            </ul>
                        </div>
                        <div class="rounded-xl bg-white/80 border border-amber-200 p-5">
                            <h3 class="font-bold text-lg">Best Practices</h3>
                            <ul class="mt-3 space-y-2 text-sm text-slate-700">
                                <li>Use clear pet photos with good lighting.</li>
                                <li>Add accurate last-seen area and timing.</li>
                                <li>Provide reachable phone number.</li>
                                <li>Update status quickly when pet is found.</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </section>

            <section class="max-w-6xl mx-auto px-4 md:px-8 py-12">
                <div class="rounded-2xl bg-ink text-white p-8 md:p-10 flex flex-col md:flex-row md:items-center md:justify-between gap-6">
                    <div>
                        <h2 class="text-2xl md:text-3xl font-bold">Ready to Start?</h2>
                        <p class="mt-2 text-slate-300 text-sm">Find your companion or help a family reunite with their pet.</p>
                    </div>
                    <div class="flex flex-wrap gap-3">
                        <a href="/browse" class="inline-flex items-center justify-center rounded-xl bg-primary px-5 py-3 font-bold hover:bg-blue-600 transition-colors">Browse Dogs</a>
                        <a href="/login" class="inline-flex items-center justify-center rounded-xl bg-white text-ink px-5 py-3 font-bold hover:bg-slate-200 transition-colors">Sign Up</a>
                    </div>
                </div>
            </section>
        </main>
    </div>
</body>
</html>
