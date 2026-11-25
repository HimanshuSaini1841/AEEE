<!DOCTYPE html>
<html lang="en" class="h-full scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Crack AEEE - Official PYQ Papers & Exam Preparation')</title>
    <meta name="description" content="@yield('meta_description', 'India\'s dedicated portal for Amrita Entrance Examination - Engineering (AEEE). Access official previous year question papers from 2005 to 2026, syllabus breakdowns, and secure offline study materials.')">
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('images/app-icon.png') }}">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: {
                            50: '#fdf2f8',
                            100: '#fce7f3',
                            200: '#fbcfe8',
                            300: '#f472b6',
                            400: '#e11d48',
                            500: '#be185d',
                            600: '#9d174d', // Brand Color: #9d174d
                            700: '#831843',
                            800: '#700c36',
                            900: '#500724',
                        }
                    },
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'system-ui', 'sans-serif'],
                    }
                }
            }
        }
    </script>

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- Heroicons / FontAwesome for crisp iconography -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #fafafa;
        }
        .hero-pattern {
            background-color: #ffffff;
            background-image: radial-gradient(#9d174d15 1px, transparent 1px);
            background-size: 24px 24px;
        }
        .glass-header {
            background: rgba(255, 255, 255, 0.92);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
        }
    </style>
</head>
<body class="flex flex-col min-h-screen text-slate-800 antialiased selection:bg-brand-600 selection:text-white" x-data="{ mobileMenuOpen: false }">

    <!-- Top Notice Bar -->
    <div class="bg-gradient-to-r from-brand-900 via-brand-700 to-brand-600 text-white text-xs py-2 px-4 text-center font-medium shadow-sm">
        <div class="max-w-7xl mx-auto flex items-center justify-center space-x-2">
            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-white/20 text-white">2026 EDITION</span>
            <span>AEEE Previous Years Papers (2005 - 2026) are now available with offline in-app reading!</span>
            <a href="{{ route('frontend.home') }}#pyqs" class="underline underline-offset-2 font-bold ml-1 hover:text-pink-200">View Papers &rarr;</a>
        </div>
    </div>

    <!-- Navigation Header -->
    <header class="sticky top-0 z-50 glass-header border-b border-slate-200/80 transition-all">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-20">
                <!-- Brand Logo -->
                <a href="{{ route('frontend.home') }}" class="flex items-center space-x-3 group">
                    <img src="{{ asset('images/logo.png') }}" alt="Crack AEEE Logo" class="w-11 h-11 object-contain drop-shadow-sm group-hover:scale-105 transition-transform duration-200">
                    <span class="text-xl font-black text-brand-600 tracking-tight leading-none group-hover:text-brand-700 transition">Crack AEEE</span>
                </a>

                <!-- Desktop Navigation Links -->
                <nav class="hidden md:flex items-center space-x-1 lg:space-x-2">
                    <a href="{{ route('frontend.home') }}" class="px-3.5 py-2 text-sm font-semibold rounded-lg transition {{ request()->routeIs('frontend.home') ? 'text-brand-600 bg-brand-50' : 'text-slate-600 hover:text-brand-600 hover:bg-slate-100/70' }}">
                        Home
                    </a>
                    <a href="{{ route('frontend.home') }}#features" class="px-3.5 py-2 text-sm font-semibold rounded-lg text-slate-600 hover:text-brand-600 hover:bg-slate-100/70 transition">
                        What We Provide
                    </a>
                    <a href="{{ route('frontend.home') }}#pyqs" class="px-3.5 py-2 text-sm font-semibold rounded-lg text-slate-600 hover:text-brand-600 hover:bg-slate-100/70 transition">
                        PYQ Papers
                    </a>
                    <a href="{{ route('frontend.about') }}" class="px-3.5 py-2 text-sm font-semibold rounded-lg transition {{ request()->routeIs('frontend.about') ? 'text-brand-600 bg-brand-50' : 'text-slate-600 hover:text-brand-600 hover:bg-slate-100/70' }}">
                        About Us
                    </a>
                    <a href="{{ route('frontend.contact') }}" class="px-3.5 py-2 text-sm font-semibold rounded-lg transition {{ request()->routeIs('frontend.contact') ? 'text-brand-600 bg-brand-50' : 'text-slate-600 hover:text-brand-600 hover:bg-slate-100/70' }}">
                        Contact Us
                    </a>
                </nav>

                <!-- Actions: App CTA -->
                <div class="hidden sm:flex items-center space-x-3">
                    <a href="https://play.google.com/store/apps/details?id=com.crackaeee.app" target="_blank" rel="noopener noreferrer" class="px-5 py-2.5 text-xs font-bold text-white bg-gradient-to-r from-brand-700 to-brand-600 hover:from-brand-800 hover:to-brand-700 rounded-xl shadow-md shadow-brand-600/25 hover:shadow-lg hover:shadow-brand-600/35 transition transform hover:-translate-y-0.5">
                        <i class="fa-brands fa-google-play mr-1.5"></i> Get Android App
                    </a>
                </div>

                <!-- Mobile Hamburger Button -->
                <div class="md:hidden flex items-center">
                    <button @click="mobileMenuOpen = !mobileMenuOpen" class="p-2 rounded-xl text-slate-600 hover:text-brand-600 hover:bg-slate-100 focus:outline-none">
                        <i class="fa-solid text-xl" :class="mobileMenuOpen ? 'fa-xmark' : 'fa-bars'"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Menu Dropdown -->
        <div x-show="mobileMenuOpen" x-transition.origin.top class="md:hidden border-b border-slate-200 bg-white px-4 pt-3 pb-6 space-y-2 shadow-xl">
            <a href="{{ route('frontend.home') }}" @click="mobileMenuOpen = false" class="block px-3 py-2.5 rounded-lg text-base font-semibold {{ request()->routeIs('frontend.home') ? 'bg-brand-50 text-brand-600' : 'text-slate-700 hover:bg-slate-50' }}">
                Home
            </a>
            <a href="{{ route('frontend.home') }}#features" @click="mobileMenuOpen = false" class="block px-3 py-2.5 rounded-lg text-base font-semibold text-slate-700 hover:bg-slate-50">
                What We Provide
            </a>
            <a href="{{ route('frontend.home') }}#pyqs" @click="mobileMenuOpen = false" class="block px-3 py-2.5 rounded-lg text-base font-semibold text-slate-700 hover:bg-slate-50">
                Previous Year Papers
            </a>
            <a href="{{ route('frontend.about') }}" @click="mobileMenuOpen = false" class="block px-3 py-2.5 rounded-lg text-base font-semibold {{ request()->routeIs('frontend.about') ? 'bg-brand-50 text-brand-600' : 'text-slate-700 hover:bg-slate-50' }}">
                About Us
            </a>
            <a href="{{ route('frontend.contact') }}" @click="mobileMenuOpen = false" class="block px-3 py-2.5 rounded-lg text-base font-semibold {{ request()->routeIs('frontend.contact') ? 'bg-brand-50 text-brand-600' : 'text-slate-700 hover:bg-slate-50' }}">
                Contact Us
            </a>
            <a href="{{ route('frontend.privacy') }}" @click="mobileMenuOpen = false" class="block px-3 py-2.5 rounded-lg text-sm font-medium text-slate-600 hover:bg-slate-50">
                Privacy Policy
            </a>
            <a href="{{ route('frontend.terms') }}" @click="mobileMenuOpen = false" class="block px-3 py-2.5 rounded-lg text-sm font-medium text-slate-600 hover:bg-slate-50">
                Terms & Conditions
            </a>
            <div class="pt-4 border-t border-slate-100 flex flex-col space-y-2">
                <a href="https://play.google.com/store/apps/details?id=com.crackaeee.app" target="_blank" rel="noopener noreferrer" @click="mobileMenuOpen = false" class="w-full text-center py-3 text-sm font-bold text-white bg-brand-600 hover:bg-brand-700 rounded-xl shadow-md shadow-brand-600/20">
                    <i class="fa-brands fa-google-play mr-2"></i> Download Android App
                </a>
            </div>
        </div>
    </header>

    <!-- Main Page Content -->
    <main class="flex-grow">
        @yield('content')
    </main>

    <!-- Global Modern Footer -->
    <footer class="bg-slate-900 text-slate-300 border-t border-slate-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-16 pb-12">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-10">
                <!-- Column 1: Brand Info -->
                <div class="lg:col-span-2 space-y-4">
                    <div class="flex items-center space-x-3">
                        <div class="w-11 h-11 rounded-xl bg-white flex items-center justify-center shadow-lg shadow-brand-600/20 p-1 border border-white/10">
                            <img src="{{ asset('images/logo.png') }}" alt="Crack AEEE Logo" class="w-full h-full object-contain">
                        </div>
                        <span class="text-2xl font-black text-white tracking-tight">Crack AEEE</span>
                    </div>
                    <p class="text-sm text-slate-400 leading-relaxed pr-6">
                        India's dedicated learning and preparation platform for the Amrita Entrance Examination - Engineering (AEEE). We empower aspirants with authentic year-wise question papers (2005 - 2027), structured syllabus breakdowns, and secure offline study materials.
                    </p>
                </div>

                <!-- Column 2: Quick Links -->
                <div>
                    <h3 class="text-xs font-extrabold uppercase tracking-widest text-white mb-4">Quick Links</h3>
                    <ul class="space-y-2.5 text-sm">
                        <li><a href="{{ route('frontend.home') }}" class="hover:text-pink-400 transition">Home</a></li>
                        <li><a href="{{ route('frontend.about') }}" class="hover:text-pink-400 transition">About Us</a></li>
                        <li><a href="{{ route('frontend.home') }}#features" class="hover:text-pink-400 transition">What We Provide</a></li>
                        <li><a href="{{ route('frontend.home') }}#pyqs" class="hover:text-pink-400 transition">PYQ Question Papers</a></li>
                        <li><a href="https://play.google.com/store/apps/details?id=com.crackaeee.app" target="_blank" rel="noopener noreferrer" class="text-emerald-400 hover:text-emerald-300 font-semibold transition"><i class="fa-brands fa-google-play mr-1"></i> Google Play App</a></li>
                        <li><a href="{{ route('frontend.contact') }}" class="hover:text-pink-400 transition">Contact Us</a></li>
                    </ul>
                </div>

                <!-- Column 3: Legal & Privacy -->
                <div>
                    <h3 class="text-xs font-extrabold uppercase tracking-widest text-white mb-4">Legal & Privacy</h3>
                    <ul class="space-y-2.5 text-sm">
                        <li><a href="{{ route('frontend.privacy') }}" class="hover:text-pink-400 transition">Privacy Policy</a></li>
                        <li><a href="{{ route('frontend.terms') }}" class="hover:text-pink-400 transition">Terms & Conditions</a></li>
                        <li><a href="{{ route('frontend.contact') }}" class="hover:text-pink-400 transition">Support & Grievance</a></li>
                    </ul>
                </div>

                <!-- Column 4: Contact & Support -->
                <div>
                    <h3 class="text-xs font-extrabold uppercase tracking-widest text-white mb-4">Support & Inquiries</h3>
                    <ul class="space-y-3 text-sm">
                        <li class="flex items-start space-x-3">
                            <i class="fa-solid fa-envelope text-brand-400 mt-1"></i>
                            <div>
                                <span class="text-xs text-slate-400 block">Direct Email</span>
                                <a href="mailto:hostcob.doon@gmail.com" class="text-white font-semibold hover:text-pink-400 transition break-all">
                                    hostcob.doon@gmail.com
                                </a>
                            </div>
                        </li>
                        <li class="flex items-start space-x-3">
                            <i class="fa-solid fa-shield-halved text-brand-400 mt-1"></i>
                            <div>
                                <span class="text-xs text-slate-400 block">DRM Security</span>
                                <span class="text-xs text-slate-300">In-App Offline Sandboxing</span>
                            </div>
                        </li>
                        <li class="flex items-start space-x-3">
                            <i class="fa-solid fa-clock text-brand-400 mt-1"></i>
                            <div>
                                <span class="text-xs text-slate-400 block">Support Hours</span>
                                <span class="text-xs text-slate-300">Mon - Sat: 9:30 AM - 6:30 PM IST</span>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Bottom Disclaimer & Copyright Bar -->
            <div class="mt-12 pt-8 border-t border-slate-800 text-xs text-slate-400 flex flex-col sm:flex-row items-center justify-between gap-4">
                <p>
                    &copy; {{ date('Y') }} <strong>Crack AEEE</strong>. All rights reserved.
                </p>
                <div class="flex items-center space-x-4 text-xs">
                    <a href="{{ route('frontend.privacy') }}" class="hover:text-white transition">Privacy</a>
                    <span>&bull;</span>
                    <a href="{{ route('frontend.terms') }}" class="hover:text-white transition">Terms</a>
                    <span>&bull;</span>
                    <a href="mailto:hostcob.doon@gmail.com" class="hover:text-white transition">hostcob.doon@gmail.com</a>
                </div>
            </div>
            <div class="mt-4 text-[11px] text-slate-500 text-center sm:text-left">
                Disclaimer: Crack AEEE is an independent educational portal designed to help engineering candidates prepare for AEEE. Amrita Vishwa Vidyapeetham is the trademark holder of "AEEE".
            </div>
        </div>
    </footer>

    @yield('scripts')
</body>
</html>
