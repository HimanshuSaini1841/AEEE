@extends('layouts.frontend')

@section('title', 'Crack AEEE - Official PYQ Question Papers & Complete Exam Preparation')

@section('content')
<!-- Hero Section -->
<section class="relative hero-pattern pt-16 pb-20 lg:pt-24 lg:pb-32 overflow-hidden border-b border-slate-200/70">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-8 items-center">
            
            <!-- Left Column: Copy & CTAs -->
            <div class="lg:col-span-7 space-y-6 text-center lg:text-left">
                <!-- Highlight Pill -->
                <div class="inline-flex items-center space-x-2 px-3.5 py-1.5 rounded-full bg-brand-50 border border-brand-200/80 text-brand-700 text-xs font-bold tracking-wide shadow-sm">
                    <span class="w-2 h-2 rounded-full bg-brand-600 animate-ping"></span>
                    <span>OFFICIAL 2005 - 2026 AEEE PYQ ARCHIVE</span>
                </div>

                <h1 class="text-4xl sm:text-5xl lg:text-6xl font-black text-slate-900 tracking-tight leading-[1.12]">
                    Prepare & Score High in <span class="bg-gradient-to-r from-brand-700 via-brand-600 to-brand-500 bg-clip-text text-transparent">Amrita (AEEE)</span>
                </h1>

                <p class="text-base sm:text-lg text-slate-600 max-w-2xl mx-auto lg:mx-0 font-medium leading-relaxed">
                    Crack AEEE is India's dedicated preparation hub designed specifically for Amrita Vishwa Vidyapeetham engineering aspirants. Access authentic previous years' question papers, syllabus analytics, and study offline securely inside our app.
                </p>

                <!-- Value Proposition Highlights -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-2 text-left max-w-xl mx-auto lg:mx-0">
                    <div class="flex items-center space-x-3 text-sm text-slate-700 font-semibold bg-white p-3 rounded-xl border border-slate-200/70 shadow-sm">
                        <div class="w-8 h-8 rounded-lg bg-brand-50 text-brand-600 flex items-center justify-center font-bold">
                            <i class="fa-solid fa-file-pdf"></i>
                        </div>
                        <span>Authentic Question Papers</span>
                    </div>
                    <div class="flex items-center space-x-3 text-sm text-slate-700 font-semibold bg-white p-3 rounded-xl border border-slate-200/70 shadow-sm">
                        <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold">
                            <i class="fa-solid fa-cloud-arrow-down"></i>
                        </div>
                        <span>DRM-Protected Offline Study</span>
                    </div>
                    <div class="flex items-center space-x-3 text-sm text-slate-700 font-semibold bg-white p-3 rounded-xl border border-slate-200/70 shadow-sm">
                        <div class="w-8 h-8 rounded-lg bg-purple-50 text-purple-600 flex items-center justify-center font-bold">
                            <i class="fa-solid fa-sliders"></i>
                        </div>
                        <span>2005 - 2026 Year Filters</span>
                    </div>
                    <div class="flex items-center space-x-3 text-sm text-slate-700 font-semibold bg-white p-3 rounded-xl border border-slate-200/70 shadow-sm">
                        <div class="w-8 h-8 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center font-bold">
                            <i class="fa-solid fa-ban"></i>
                        </div>
                        <span>100% Ad-Free Learning</span>
                    </div>
                </div>

                <!-- CTA Actions -->
                <div class="flex flex-col sm:flex-row items-center justify-center lg:justify-start gap-4 pt-4">
                    <a href="#pyqs" class="w-full sm:w-auto px-8 py-3.5 text-sm font-bold text-white bg-gradient-to-r from-brand-700 to-brand-600 hover:from-brand-800 hover:to-brand-700 rounded-xl shadow-lg shadow-brand-600/30 hover:shadow-xl transition transform hover:-translate-y-0.5 text-center flex items-center justify-center space-x-2">
                        <span>Explore Question Papers</span>
                        <i class="fa-solid fa-arrow-right text-xs"></i>
                    </a>
                    <a href="https://play.google.com/store/apps/details?id=com.crackaeee.app" target="_blank" rel="noopener noreferrer" class="w-full sm:w-auto px-7 py-3.5 text-sm font-bold text-slate-700 bg-white hover:bg-slate-50 border border-slate-200/80 rounded-xl shadow-sm transition text-center flex items-center justify-center space-x-2">
                        <i class="fa-brands fa-google-play text-brand-600 text-base"></i>
                        <span>Get on Google Play</span>
                    </a>
                </div>

                <!-- Trust Badges -->
                <div class="pt-4 flex items-center justify-center lg:justify-start space-x-4 text-xs text-slate-500 font-medium">
                    <span class="flex items-center"><i class="fa-solid fa-circle-check text-emerald-500 mr-1.5"></i> Verified Question Papers</span>
                    <span>&bull;</span>
                    <span class="flex items-center"><i class="fa-solid fa-lock text-brand-600 mr-1.5"></i> DRM Secure In-App Reading</span>
                </div>
            </div>

            <!-- Right Column: Interactive App Mockup Preview -->
            <div class="lg:col-span-5 relative flex justify-center">
                <div class="relative w-full max-w-[340px] sm:max-w-[380px]">
                    <!-- Outer Phone Frame -->
                    <div class="relative rounded-[40px] p-3.5 bg-gradient-to-b from-slate-800 via-slate-900 to-black shadow-2xl shadow-brand-900/40 border border-slate-700">
                        <div class="relative bg-white rounded-[32px] overflow-hidden border border-slate-100 flex flex-col min-h-[580px]">
                            
                            <!-- Phone Status Bar Mock -->
                            <div class="h-6 bg-slate-900 text-white text-[10px] px-6 flex items-center justify-between font-mono font-medium">
                                <span>9:41</span>
                                <div class="flex items-center space-x-1.5 text-[9px]">
                                    <i class="fa-solid fa-wifi"></i>
                                    <i class="fa-solid fa-signal"></i>
                                    <i class="fa-solid fa-battery-full"></i>
                                </div>
                            </div>

                            <!-- Phone App Header -->
                            <div class="p-4 bg-white border-b border-slate-100 flex items-center justify-between">
                                <div class="flex items-center space-x-2">
                                    <img src="{{ asset('images/logo.png') }}" alt="Crack AEEE Logo" class="w-7 h-7 object-contain">
                                    <span class="font-bold text-brand-700 text-sm">Crack AEEE</span>
                                </div>
                                <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200">
                                    <i class="fa-solid fa-shield-check mr-1"></i> DRM SECURE
                                </span>
                            </div>

                            <!-- Phone App Welcome Hero Card -->
                            <div class="p-4 bg-slate-50 space-y-3 flex-1">
                                <div class="rounded-2xl p-4 bg-gradient-to-br from-brand-800 to-brand-600 text-white shadow-md shadow-brand-600/20">
                                    <div class="flex justify-between items-center mb-2">
                                        <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-white/20">AEEE Aspirant</span>
                                        <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-white text-brand-700">Target 2026</span>
                                    </div>
                                    <h4 class="text-base font-extrabold text-white">Official PYQ Hub</h4>
                                    <p class="text-xs text-white/80 mt-1">Download once, practice anytime offline with zero data consumption.</p>
                                    
                                    <!-- City Chip Inside Welcome Card -->
                                    <div class="mt-3 inline-flex items-center space-x-1.5 px-2.5 py-1 rounded-lg bg-white/15 border border-white/25 text-[10px] font-semibold text-white">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-300"></span>
                                        <span>Enrolled Aspirant &bull; Offline Ready</span>
                                    </div>
                                </div>

                                <!-- Sample PYQ Cards in App View -->
                                <div class="space-y-2 pt-1">
                                    <div class="flex items-center justify-between text-xs font-bold text-slate-600 px-1">
                                        <span>Recent Question Papers</span>
                                        <span class="text-brand-600 text-[11px]">View All</span>
                                    </div>

                                    <div class="bg-white p-3 rounded-xl border border-slate-200/80 shadow-xs flex items-center justify-between">
                                        <div class="flex items-center space-x-3">
                                            <div class="w-8 h-8 rounded-lg bg-brand-50 text-brand-600 flex items-center justify-center text-xs">
                                                <i class="fa-solid fa-file-pdf"></i>
                                            </div>
                                            <div>
                                                <h5 class="text-xs font-bold text-slate-800">AEEE PYQ 2024</h5>
                                                <span class="text-[10px] text-slate-400">Full Length &bull; 842 KB</span>
                                            </div>
                                        </div>
                                        <span class="text-[10px] font-bold px-2 py-1 rounded-md bg-emerald-50 text-emerald-700">
                                            <i class="fa-solid fa-check text-[9px] mr-1"></i> Saved Offline
                                        </span>
                                    </div>

                                    <div class="bg-white p-3 rounded-xl border border-slate-200/80 shadow-xs flex items-center justify-between">
                                        <div class="flex items-center space-x-3">
                                            <div class="w-8 h-8 rounded-lg bg-brand-50 text-brand-600 flex items-center justify-center text-xs">
                                                <i class="fa-solid fa-file-pdf"></i>
                                            </div>
                                            <div>
                                                <h5 class="text-xs font-bold text-slate-800">AEEE PYQ 2023</h5>
                                                <span class="text-[10px] text-slate-400">Full Length &bull; 1.2 MB</span>
                                            </div>
                                        </div>
                                        <button class="text-[10px] font-bold px-2.5 py-1 rounded-md bg-brand-50 text-brand-700 hover:bg-brand-100 transition">
                                            Download
                                        </button>
                                    </div>

                                    <div class="bg-white p-3 rounded-xl border border-slate-200/80 shadow-xs flex items-center justify-between">
                                        <div class="flex items-center space-x-3">
                                            <div class="w-8 h-8 rounded-lg bg-brand-50 text-brand-600 flex items-center justify-center text-xs">
                                                <i class="fa-solid fa-file-pdf"></i>
                                            </div>
                                            <div>
                                                <h5 class="text-xs font-bold text-slate-800">AEEE PYQ 2022</h5>
                                                <span class="text-[10px] text-slate-400">Full Length &bull; 960 KB</span>
                                            </div>
                                        </div>
                                        <button class="text-[10px] font-bold px-2.5 py-1 rounded-md bg-brand-50 text-brand-700 hover:bg-brand-100 transition">
                                            Download
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Phone Bottom Nav Bar -->
                            <div class="h-14 bg-white border-t border-slate-100 px-6 flex items-center justify-around text-slate-400 text-xs">
                                <span class="text-brand-600 flex flex-col items-center"><i class="fa-solid fa-house text-sm"></i><span class="text-[9px] font-bold mt-0.5">Home</span></span>
                                <span class="flex flex-col items-center"><i class="fa-solid fa-book-open text-sm"></i><span class="text-[9px] mt-0.5">PYQs</span></span>
                                <span class="flex flex-col items-center"><i class="fa-solid fa-circle-check text-sm"></i><span class="text-[9px] mt-0.5">Offline</span></span>
                                <span class="flex flex-col items-center"><i class="fa-solid fa-user text-sm"></i><span class="text-[9px] mt-0.5">Profile</span></span>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- What We Provide Section -->
<section id="features" class="py-20 bg-white border-b border-slate-200/70">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="text-center max-w-3xl mx-auto space-y-4">
            <span class="text-xs font-extrabold text-brand-600 uppercase tracking-widest bg-brand-50 px-3 py-1 rounded-full border border-brand-200/60">
                WHAT WE PROVIDE
            </span>
            <h2 class="text-3xl sm:text-4xl font-extrabold text-slate-900 tracking-tight">
                Everything You Need to Crack Amrita Entrance Exam
            </h2>
            <p class="text-base text-slate-600 font-medium">
                We empower engineering aspirants with authentic, curated resources designed to eliminate guesswork and give you an edge in AEEE.
            </p>
        </div>

        <div class="mt-16 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            
            <!-- Feature 1: Authentic PYQs -->
            <div class="p-8 rounded-2xl bg-slate-50 border border-slate-200/80 hover:border-brand-300 transition duration-300 hover:shadow-xl hover:shadow-brand-600/5 group">
                <div class="w-14 h-14 rounded-2xl bg-brand-600 text-white flex items-center justify-center text-2xl shadow-md shadow-brand-600/25 group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-book-journal-whills"></i>
                </div>
                <h3 class="text-xl font-bold text-slate-900 mt-6 mb-2">
                    Official PYQs from 2005 to 2026
                </h3>
                <p class="text-sm text-slate-600 leading-relaxed">
                    Access over 20 years of authentic previous years question papers. Filter year-by-year from 2005 onwards to analyze evolving question patterns and difficulty levels.
                </p>
            </div>

            <!-- Feature 2: In-App Offline PDF Reader -->
            <div class="p-8 rounded-2xl bg-slate-50 border border-slate-200/80 hover:border-brand-300 transition duration-300 hover:shadow-xl hover:shadow-brand-600/5 group">
                <div class="w-14 h-14 rounded-2xl bg-emerald-600 text-white flex items-center justify-center text-2xl shadow-md shadow-emerald-600/25 group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-shield-halved"></i>
                </div>
                <h3 class="text-xl font-bold text-slate-900 mt-6 mb-2">
                    DRM-Protected Offline Study
                </h3>
                <p class="text-sm text-slate-600 leading-relaxed">
                    Save papers to your device once and study without an active internet connection. Fully protected inside the app sandbox, preventing spam, distractions, or corrupted files.
                </p>
            </div>

            <!-- Feature 3: Subject & Syllabus Insights -->
            <div class="p-8 rounded-2xl bg-slate-50 border border-slate-200/80 hover:border-brand-300 transition duration-300 hover:shadow-xl hover:shadow-brand-600/5 group">
                <div class="w-14 h-14 rounded-2xl bg-purple-600 text-white flex items-center justify-center text-2xl shadow-md shadow-purple-600/25 group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-chart-pie"></i>
                </div>
                <h3 class="text-xl font-bold text-slate-900 mt-6 mb-2">
                    Syllabus & Weightage Breakdown
                </h3>
                <p class="text-sm text-slate-600 leading-relaxed">
                    Understand the exact marks distribution across Mathematics (40 Qs), Physics (30 Qs), Chemistry (25 Qs), and English (5 Qs). Focus on the chapters that carry maximum marks.
                </p>
            </div>

            <!-- Feature 4: Fast & Verified Mobile Verification -->
            <div class="p-8 rounded-2xl bg-slate-50 border border-slate-200/80 hover:border-brand-300 transition duration-300 hover:shadow-xl hover:shadow-brand-600/5 group">
                <div class="w-14 h-14 rounded-2xl bg-amber-600 text-white flex items-center justify-center text-2xl shadow-md shadow-amber-600/25 group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-mobile-screen-button"></i>
                </div>
                <h3 class="text-xl font-bold text-slate-900 mt-6 mb-2">
                    Verified Candidate Profiles
                </h3>
                <p class="text-sm text-slate-600 leading-relaxed">
                    High-speed SMS authentication ensures legitimate, verified candidate access. Personalize target campus preferences (Coimbatore, Amritapuri, Bengaluru, Chennai).
                </p>
            </div>

            <!-- Feature 5: Real-time Slot & Exam Notifications -->
            <div class="p-8 rounded-2xl bg-slate-50 border border-slate-200/80 hover:border-brand-300 transition duration-300 hover:shadow-xl hover:shadow-brand-600/5 group">
                <div class="w-14 h-14 rounded-2xl bg-pink-600 text-white flex items-center justify-center text-2xl shadow-md shadow-pink-600/25 group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-bell"></i>
                </div>
                <h3 class="text-xl font-bold text-slate-900 mt-6 mb-2">
                    Instant Exam Notifications
                </h3>
                <p class="text-sm text-slate-600 leading-relaxed">
                    Never miss a critical date. Receive instant alerts on Phase 1 & Phase 2 registration deadlines, slot booking release, admit cards, and Amrita counselling schedules.
                </p>
            </div>

            <!-- Feature 6: Zero Ads, Pure Learning -->
            <div class="p-8 rounded-2xl bg-slate-50 border border-slate-200/80 hover:border-brand-300 transition duration-300 hover:shadow-xl hover:shadow-brand-600/5 group">
                <div class="w-14 h-14 rounded-2xl bg-indigo-600 text-white flex items-center justify-center text-2xl shadow-md shadow-indigo-600/25 group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-graduation-cap"></i>
                </div>
                <h3 class="text-xl font-bold text-slate-900 mt-6 mb-2">
                    Distraction-Free Environment
                </h3>
                <p class="text-sm text-slate-600 leading-relaxed">
                    We believe focused study requires zero distractions. No intrusive banners, no pop-up advertisements, and no irrelevant promotional bloat.
                </p>
            </div>

        </div>

    </div>
</section>

<!-- Year-wise PYQ Showcase Section -->
<section id="pyqs" class="py-20 bg-slate-50/70 border-b border-slate-200/70">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="flex flex-col md:flex-row md:items-end justify-between mb-12">
            <div>
                <span class="text-xs font-extrabold text-brand-600 uppercase tracking-widest bg-brand-50 px-3 py-1 rounded-full border border-brand-200/60">
                    REPOSITORY OVERVIEW
                </span>
                <h2 class="text-3xl font-extrabold text-slate-900 tracking-tight mt-2">
                    AEEE Previous Year Papers Archive
                </h2>
                <p class="text-sm text-slate-600 mt-1">
                    Showing available question papers from 2005 to 2026. Download the Crack AEEE app to open and study offline.
                </p>
            </div>
            
            <div class="mt-4 md:mt-0 flex items-center space-x-2">
                <span class="text-xs font-bold text-slate-500">Total Available:</span>
                <span class="text-xs font-extrabold px-3 py-1 bg-brand-600 text-white rounded-full">{{ $totalPyqs }} Papers</span>
            </div>
        </div>

        @if($pyqs->isNotEmpty())
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($pyqs as $pyq)
            <div class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-sm hover:shadow-md hover:border-brand-300 transition duration-200 flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between mb-3">
                        <span class="px-2.5 py-1 rounded-md text-xs font-black bg-brand-50 text-brand-700 border border-brand-200/50">
                            AEEE {{ $pyq->year }}
                        </span>
                        <span class="text-[11px] font-semibold text-slate-400">
                            {{ $pyq->file_size_formatted ?? 'PDF Document' }}
                        </span>
                    </div>

                    <h4 class="font-bold text-slate-900 text-base leading-snug">
                        {{ $pyq->title }}
                    </h4>
                    <p class="text-xs text-slate-500 mt-1">
                        {{ $pyq->subject ?? 'Full Length Question Paper' }}
                    </p>
                </div>

                <div class="pt-6 border-t border-slate-100 mt-6 flex items-center justify-between">
                    <span class="text-[11px] font-semibold text-emerald-600 flex items-center">
                        <i class="fa-solid fa-check-circle mr-1"></i> Verified
                    </span>
                    <a href="#download-app" class="text-xs font-bold text-brand-600 hover:text-brand-700 inline-flex items-center space-x-1">
                        <span>Read in App</span>
                        <i class="fa-solid fa-chevron-right text-[10px]"></i>
                    </a>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="bg-white rounded-2xl p-12 text-center border border-slate-200/80">
            <div class="w-16 h-16 rounded-full bg-brand-50 text-brand-600 flex items-center justify-center text-2xl mx-auto mb-4">
                <i class="fa-solid fa-book"></i>
            </div>
            <h3 class="text-lg font-bold text-slate-800">Catalog Updating</h3>
            <p class="text-sm text-slate-500 max-w-md mx-auto mt-1">
                New question papers from 2005 to 2026 are being processed by our academic team. Download the mobile app for real-time notifications when new papers go live!
            </p>
        </div>
        @endif

        <!-- Year Pills Bar -->
        <div class="mt-12 bg-white rounded-2xl p-6 border border-slate-200/80 shadow-sm">
            <h4 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-3">All Supported Examination Years:</h4>
            <div class="flex flex-wrap gap-2">
                @for($y = 2026; $y >= 2005; $y--)
                <span class="px-3 py-1.5 rounded-lg text-xs font-bold {{ in_array($y, $availableYears->toArray() ?? []) ? 'bg-brand-600 text-white' : 'bg-slate-100 text-slate-600 border border-slate-200/60' }}">
                    {{ $y }}
                </span>
                @endfor
            </div>
        </div>

    </div>
</section>

<!-- Why Previous Year Papers Matter Section -->
<section class="py-20 bg-white border-b border-slate-200/70">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
            
            <div class="lg:col-span-6 space-y-6">
                <span class="text-xs font-extrabold text-brand-600 uppercase tracking-widest bg-brand-50 px-3 py-1 rounded-full border border-brand-200/60">
                    EXAMINATION STRATEGY
                </span>
                <h2 class="text-3xl sm:text-4xl font-extrabold text-slate-900 tracking-tight">
                    Why Solving Past AEEE Papers Is Your Best Strategy
                </h2>
                <p class="text-base text-slate-600 leading-relaxed font-medium">
                    The Amrita Entrance Examination tests speed, conceptual mastery, and question selection across 100 questions in 150 minutes. Practicing authentic past papers is proven to boost accuracy, time management, and examination confidence.
                </p>

                <div class="space-y-4 pt-2">
                    <div class="flex items-start space-x-3">
                        <div class="w-6 h-6 rounded-full bg-brand-100 text-brand-700 flex items-center justify-center text-xs mt-0.5">
                            <i class="fa-solid fa-check"></i>
                        </div>
                        <div>
                            <h4 class="text-sm font-bold text-slate-900">Pattern Familiarity</h4>
                            <p class="text-xs text-slate-500 mt-0.5">Understand repeated question types and the exact weightage across Mathematics, Physics, and Chemistry.</p>
                        </div>
                    </div>

                    <div class="flex items-start space-x-3">
                        <div class="w-6 h-6 rounded-full bg-brand-100 text-brand-700 flex items-center justify-center text-xs mt-0.5">
                            <i class="fa-solid fa-check"></i>
                        </div>
                        <div>
                            <h4 class="text-sm font-bold text-slate-900">Speed & Negative Marking Control</h4>
                            <p class="text-xs text-slate-500 mt-0.5">Simulate real exam timing to minimize negative marking risks and maximize your net score.</p>
                        </div>
                    </div>
                </div>

                <div class="pt-4">
                    <a href="{{ route('frontend.about') }}" class="inline-flex items-center text-sm font-bold text-brand-600 hover:text-brand-700">
                        <span>Learn more about Crack AEEE</span>
                        <i class="fa-solid fa-arrow-right ml-2 text-xs"></i>
                    </a>
                </div>
            </div>

            <div class="lg:col-span-6 grid grid-cols-2 gap-4">
                <div class="p-6 rounded-2xl bg-slate-50 border border-slate-200/80 space-y-2">
                    <span class="text-3xl font-black text-brand-600">20+</span>
                    <h5 class="text-sm font-bold text-slate-800">Years of Papers</h5>
                    <p class="text-xs text-slate-500">Comprehensive paper archive ranging from 2005 through 2026.</p>
                </div>

                <div class="p-6 rounded-2xl bg-slate-50 border border-slate-200/80 space-y-2">
                    <span class="text-3xl font-black text-emerald-600">100%</span>
                    <h5 class="text-sm font-bold text-slate-800">Offline Access</h5>
                    <p class="text-xs text-slate-500">Download question papers once and study anywhere, anytime.</p>
                </div>

                <div class="p-6 rounded-2xl bg-slate-50 border border-slate-200/80 space-y-2">
                    <span class="text-3xl font-black text-purple-600">0</span>
                    <h5 class="text-sm font-bold text-slate-800">Advertisements</h5>
                    <p class="text-xs text-slate-500">Clean, pure educational focus with zero third-party banners.</p>
                </div>

                <div class="p-6 rounded-2xl bg-slate-50 border border-slate-200/80 space-y-2">
                    <span class="text-3xl font-black text-amber-600">24/7</span>
                    <h5 class="text-sm font-bold text-slate-800">Student Support</h5>
                    <p class="text-xs text-slate-500">Direct academic and technical assistance at hostcob.doon@gmail.com.</p>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- Download App CTA Banner -->
<section id="download-app" class="py-20 bg-gradient-to-br from-brand-900 via-brand-700 to-brand-600 text-white relative overflow-hidden">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center">
        <div class="max-w-3xl mx-auto space-y-6">
            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-white/20 text-white">
                ANDROID MOBILE APP
            </span>
            <h2 class="text-3xl sm:text-5xl font-black tracking-tight leading-tight">
                Start Your AEEE 2027 Preparation on Mobile
            </h2>
            <p class="text-base sm:text-lg text-pink-100 font-medium">
                Install Crack AEEE on your Android device to unlock official question papers, DRM-protected offline reading, and real-time slot booking announcements.
            </p>

            <div class="pt-4 flex flex-col sm:flex-row items-center justify-center gap-4">
                <a href="https://play.google.com/store/apps/details?id=com.crackaeee.app" target="_blank" rel="noopener noreferrer" class="px-8 py-4 bg-white text-brand-700 hover:bg-slate-100 font-bold rounded-xl shadow-xl transition transform hover:-translate-y-0.5 flex items-center space-x-3">
                    <i class="fa-brands fa-google-play text-2xl text-brand-600"></i>
                    <div class="text-left">
                        <span class="block text-[10px] text-slate-500 uppercase font-semibold">GET IT ON</span>
                        <span class="block text-sm font-extrabold text-slate-900">Google Play Store</span>
                    </div>
                </a>

                <a href="{{ route('frontend.contact') }}" class="px-7 py-4 bg-white/10 hover:bg-white/20 border border-white/30 text-white font-bold rounded-xl transition flex items-center space-x-2">
                    <i class="fa-solid fa-headset"></i>
                    <span>Contact Team</span>
                </a>
            </div>

            <p class="text-xs text-pink-200 pt-2">
                Questions or issues? Reach out to our technical support team at <strong>hostcob.doon@gmail.com</strong>
            </p>
        </div>
    </div>
</section>

@endsection
