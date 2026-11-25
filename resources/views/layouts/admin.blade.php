<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Portal') - Crack AEEE</title>
    <!-- Favicon -->
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon.png') }}">
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
                            DEFAULT: '#9d174d',
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
                        },
                        primary: {
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
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="h-full flex flex-col antialiased text-slate-800">
    <div class="min-h-full flex">
        <!-- Sidebar Navigation -->
        <aside class="w-64 bg-slate-900 text-slate-300 flex flex-col shrink-0 border-r border-slate-800">
            <!-- Brand Logo -->
            <div class="h-16 flex items-center px-6 bg-slate-950 border-b border-slate-800/80 gap-3">
                <div class="w-9 h-9 rounded-xl bg-white flex items-center justify-center p-1 shadow-lg shadow-[#9d174d]/30">
                    <img src="{{ asset('images/logo.png') }}" alt="Crack AEEE" class="w-full h-full object-contain">
                </div>
                <div>
                    <span class="font-bold text-white tracking-tight text-lg">Crack AEEE</span>
                    <span class="block text-[10px] text-emerald-400 font-semibold tracking-wider uppercase">High Scale v1.0</span>
                </div>
            </div>

            <!-- Navigation Links -->
            <nav class="flex-1 px-3 py-5 space-y-1 overflow-y-auto">
                <div class="px-3 pb-2 text-[11px] font-bold text-slate-400 uppercase tracking-wider">Main</div>
                
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('admin.dashboard') ? 'bg-primary-600 text-white shadow-sm' : 'hover:bg-slate-800 hover:text-white' }}">
                    <svg class="w-5 h-5 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                    Dashboard
                </a>

                <a href="{{ route('admin.students.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('admin.students.*') ? 'bg-primary-600 text-white shadow-sm' : 'hover:bg-slate-800 hover:text-white' }}">
                    <svg class="w-5 h-5 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                    Students Directory
                </a>

                <div class="pt-6 px-3 pb-2 text-[11px] font-bold text-slate-400 uppercase tracking-wider">Academics & Study Material</div>

                <a href="{{ route('admin.pyqs.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('admin.pyqs.*') ? 'bg-primary-600 text-white shadow-sm' : 'hover:bg-slate-800 hover:text-white' }}">
                    <svg class="w-5 h-5 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                    PYQs & Question Papers
                </a>

                <a href="{{ route('admin.mock-tests.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('admin.mock-tests.*') ? 'bg-primary-600 text-white shadow-sm' : 'hover:bg-slate-800 hover:text-white' }}">
                    <svg class="w-5 h-5 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                    Mock Tests Engine
                </a>

                <div class="pt-6 px-3 pb-2 text-[11px] font-bold text-slate-400 uppercase tracking-wider">App Profile Management</div>

                <a href="{{ route('admin.fields.classes') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('admin.fields.classes') ? 'bg-primary-600 text-white shadow-sm' : 'hover:bg-slate-800 hover:text-white' }}">
                    <svg class="w-5 h-5 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                    Class Options
                </a>

                <a href="{{ route('admin.fields.states') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('admin.fields.states') ? 'bg-primary-600 text-white shadow-sm' : 'hover:bg-slate-800 hover:text-white' }}">
                    <svg class="w-5 h-5 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    State Options
                </a>

                <a href="{{ route('admin.fields.preparing_for') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('admin.fields.preparing_for') ? 'bg-primary-600 text-white shadow-sm' : 'hover:bg-slate-800 hover:text-white' }}">
                    <svg class="w-5 h-5 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Preparing For (Exams)
                </a>

                <div class="pt-6 px-3 pb-2 text-[11px] font-bold text-slate-400 uppercase tracking-wider">Gateway Settings</div>

                <a href="{{ route('admin.settings.fast2sms') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('admin.settings.fast2sms*') ? 'bg-primary-600 text-white shadow-sm' : 'hover:bg-slate-800 hover:text-white' }}">
                    <svg class="w-5 h-5 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                    Fast2SMS Keys & OTP
                </a>

                <a href="{{ route('admin.settings.firebase') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('admin.settings.firebase*') ? 'bg-primary-600 text-white shadow-sm' : 'hover:bg-slate-800 hover:text-white' }}">
                    <svg class="w-5 h-5 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                    Firebase & Push Keys
                </a>

                <a href="{{ route('admin.settings.app-controls') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('admin.settings.app-controls*') ? 'bg-primary-600 text-white shadow-sm' : 'hover:bg-slate-800 hover:text-white' }}">
                    <svg class="w-5 h-5 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/></svg>
                    App Controls & Sidebar Button
                </a>

                <div class="pt-6 px-3 pb-2 text-[11px] font-bold text-slate-400 uppercase tracking-wider">Push Notifications</div>

                <a href="{{ route('admin.notifications.create') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('admin.notifications.create') ? 'bg-primary-600 text-white shadow-sm' : 'hover:bg-slate-800 hover:text-white' }}">
                    <svg class="w-5 h-5 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/></svg>
                    Send Push Notification
                </a>

                <a href="{{ route('admin.notifications.history') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('admin.notifications.history') ? 'bg-primary-600 text-white shadow-sm' : 'hover:bg-slate-800 hover:text-white' }}">
                    <svg class="w-5 h-5 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Delivery History
                </a>
            </nav>

            <!-- Current Admin User Footer -->
            <div class="p-4 border-t border-slate-800 bg-slate-950/60 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-full bg-slate-800 text-white flex items-center justify-center font-bold text-sm border border-slate-700">
                        {{ strtoupper(substr(auth()->guard('web')->user()->name ?? 'A', 0, 1)) }}
                    </div>
                    <div class="overflow-hidden">
                        <div class="text-xs font-semibold text-white truncate">{{ auth()->guard('web')->user()->name ?? 'Administrator' }}</div>
                        <div class="text-[11px] text-slate-400 truncate">{{ auth()->guard('web')->user()->email ?? 'admin@aeee.edu' }}</div>
                    </div>
                </div>
                <form action="{{ route('admin.logout') }}" method="POST">
                    @csrf
                    <button type="submit" title="Logout" class="p-1.5 text-slate-400 hover:text-rose-400 rounded-lg hover:bg-slate-800 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col min-w-0 overflow-y-auto">
            <!-- Topbar Header -->
            <header class="h-16 bg-white border-b border-slate-200 px-8 flex items-center justify-between sticky top-0 z-20">
                <div class="flex items-center gap-3">
                    <h1 class="text-xl font-bold text-slate-900 tracking-tight">@yield('page_title', 'Dashboard')</h1>
                </div>
                <div class="flex items-center gap-4 text-xs font-medium text-slate-500">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200">
                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                        High Scale Cache & Queues Active
                    </span>
                    <span class="text-slate-300">|</span>
                    <span>Fast2SMS: <strong class="text-slate-700">Connected</strong></span>
                    <span class="text-slate-300">|</span>
                    <span>FCM: <strong class="text-slate-700">Ready</strong></span>
                </div>
            </header>

            <!-- Alerts Banner -->
            <div class="px-8 pt-6">
                @if(session('success'))
                    <div class="mb-4 p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 flex items-center gap-3 text-sm">
                        <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                        <span>{{ session('success') }}</span>
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-4 p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 flex items-center gap-3 text-sm">
                        <svg class="w-5 h-5 text-rose-600 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                        <span>{{ session('error') }}</span>
                    </div>
                @endif
            </div>

            <!-- Page Body -->
            <main class="flex-1 px-8 pb-12">
                @yield('content')
            </main>
        </div>
    </div>
</body>
</html>
