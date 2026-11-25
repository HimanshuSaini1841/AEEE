@extends('layouts.admin')

@section('title', 'App Controls & Sidebar Button')

@section('content')
<div class="max-w-4xl space-y-8">
    
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-900 tracking-tight">App Controls & Dynamic Features</h1>
            <p class="text-xs text-slate-500 mt-1">Manage remote app flags (Play Store review credentials) and dynamic sidebar drawer action buttons in real time.</p>
        </div>
    </div>

    @if(session('success'))
        <div class="p-4 bg-emerald-50 border border-emerald-200 rounded-xl flex items-center gap-3 text-emerald-800 text-sm font-medium">
            <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="p-4 bg-rose-50 border border-rose-200 rounded-xl text-rose-800 text-sm space-y-1">
            <div class="font-bold">Please correct the following:</div>
            <ul class="list-disc list-inside text-xs">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.settings.app-controls.update') }}" method="POST" class="space-y-8">
        @csrf

        <!-- 1. Play Store Reviewer Login Flag -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 space-y-6">
            <div class="flex items-center gap-3 pb-4 border-b border-slate-100">
                <div class="w-9 h-9 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center font-bold">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/></svg>
                </div>
                <div>
                    <h2 class="text-base font-bold text-slate-900">Google Play Store Reviewer Credentials Flag</h2>
                    <p class="text-xs text-slate-500">Toggle whether email & password input fields appear below "Continue with Google" on the mobile app.</p>
                </div>
            </div>

            <div class="flex items-center justify-between p-4 bg-slate-50 rounded-xl border border-slate-200">
                <div class="space-y-1">
                    <label for="show_demo_login" class="text-sm font-bold text-slate-900 cursor-pointer">
                        Enable Email & Password Sign-In Fields
                    </label>
                    <p class="text-xs text-slate-500 max-w-xl">
                        <strong>Turn ON</strong> when submitting the app to Google Play Store so testers can sign in without OAuth using reviewer credentials. 
                        <strong>Turn OFF</strong> after app approval to show only Google Sign-In to students.
                    </p>
                </div>
                <div class="relative inline-block w-12 mr-2 align-middle select-none">
                    <input type="checkbox" name="show_demo_login" id="show_demo_login" value="1" {{ $showDemoLogin ? 'checked' : '' }}
                           class="toggle-checkbox absolute block w-6 h-6 rounded-full bg-white border-4 appearance-none cursor-pointer border-slate-300 checked:right-0 checked:border-primary-600 transition-all duration-200"/>
                    <label for="show_demo_login" class="toggle-label block overflow-hidden h-6 rounded-full bg-slate-300 cursor-pointer"></label>
                </div>
            </div>

            <div class="p-4 bg-blue-50/70 rounded-xl border border-blue-100 text-xs text-blue-800 space-y-1">
                <div class="font-bold flex items-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Test Credentials to provide in Google Play Console:
                </div>
                <div class="font-mono bg-white px-3 py-2 rounded-lg border border-blue-200 text-slate-700 flex flex-col sm:flex-row gap-4 mt-2">
                    <div>Email: <span class="text-blue-600 font-bold">reviewer@crackaeee.app</span></div>
                    <div>Password: <span class="text-blue-600 font-bold">Reviewer@2026</span></div>
                </div>
            </div>
        </div>

        <!-- 2. Dynamic Sidebar Action Button -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 space-y-6" x-data="{
            enabled: {{ $sidebarBannerEnabled ? 'true' : 'false' }},
            title: '{{ addslashes($sidebarBannerTitle) }}',
            subtitle: '{{ addslashes($sidebarBannerSubtitle) }}',
            link: '{{ addslashes($sidebarBannerLink) }}',
            color: '{{ $sidebarBannerColor }}',
            icon: '{{ $sidebarBannerIcon }}'
        }">
            <div class="flex items-center gap-3 pb-4 border-b border-slate-100">
                <div class="w-9 h-9 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
                </div>
                <div>
                    <h2 class="text-base font-bold text-slate-900">Sidebar Dynamic Action Button</h2>
                    <p class="text-xs text-slate-500">Rendered in the mobile app sidebar between Main Navigation and Exam Essentials.</p>
                </div>
            </div>

            <!-- Toggle ON/OFF -->
            <div class="flex items-center justify-between p-4 bg-slate-50 rounded-xl border border-slate-200">
                <div class="space-y-1">
                    <label for="sidebar_banner_enabled" class="text-sm font-bold text-slate-900 cursor-pointer">
                        Display Dynamic Button in Sidebar
                    </label>
                    <p class="text-xs text-slate-500 max-w-xl">
                        Turn this ON to show an interactive CTA button in every student's sidebar drawer. Turn OFF to completely hide it.
                    </p>
                </div>
                <div class="relative inline-block w-12 mr-2 align-middle select-none">
                    <input type="checkbox" name="sidebar_banner_enabled" id="sidebar_banner_enabled" value="1" x-model="enabled"
                           class="toggle-checkbox absolute block w-6 h-6 rounded-full bg-white border-4 appearance-none cursor-pointer border-slate-300 checked:right-0 checked:border-emerald-600 transition-all duration-200"/>
                    <label for="sidebar_banner_enabled" class="toggle-label block overflow-hidden h-6 rounded-full bg-slate-300 cursor-pointer"></label>
                </div>
            </div>

            <div x-show="enabled" x-transition class="space-y-4 pt-2">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <!-- Button Title -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                            Button Title <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" name="sidebar_banner_title" x-model="title" placeholder="e.g. Join WhatsApp Community"
                               class="w-full text-sm rounded-xl border-slate-200 focus:border-primary-600 focus:ring-primary-600 px-3 py-2.5 border">
                    </div>

                    <!-- Button Subtitle -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                            Button Subtitle / Tagline
                        </label>
                        <input type="text" name="sidebar_banner_subtitle" x-model="subtitle" placeholder="e.g. Daily notes, updates & doubt clearing"
                               class="w-full text-sm rounded-xl border-slate-200 focus:border-primary-600 focus:ring-primary-600 px-3 py-2.5 border">
                    </div>
                </div>

                <!-- Target Link / URL -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                        Target Link URL <span class="text-rose-500">*</span>
                    </label>
                    <input type="url" name="sidebar_banner_link" x-model="link" placeholder="https://chat.whatsapp.com/... or https://amrita.edu/aeee"
                           class="w-full text-sm rounded-xl border-slate-200 focus:border-primary-600 focus:ring-primary-600 px-3 py-2.5 border">
                    <p class="text-[11px] text-slate-400 mt-1">When tapped by a student, this link will be opened externally in Chrome / Safari / WhatsApp.</p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <!-- Icon Type -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                            Icon
                        </label>
                        <select name="sidebar_banner_icon" x-model="icon" class="w-full text-sm rounded-xl border-slate-200 focus:border-primary-600 focus:ring-primary-600 px-3 py-2.5 border bg-white">
                            <option value="whatsapp">WhatsApp Community</option>
                            <option value="telegram">Telegram Channel</option>
                            <option value="website">External Website Link</option>
                            <option value="announcement">Announcement / Notice</option>
                            <option value="star">Special Offer / Star</option>
                        </select>
                    </div>

                    <!-- Theme Color -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                            Theme Accent Color
                        </label>
                        <select name="sidebar_banner_color" x-model="color" class="w-full text-sm rounded-xl border-slate-200 focus:border-primary-600 focus:ring-primary-600 px-3 py-2.5 border bg-white">
                            <option value="#10B981">Emerald Green (#10B981 - WhatsApp)</option>
                            <option value="#9D174D">Brand Rose/Maroon (#9D174D - Default)</option>
                            <option value="#2563EB">Royal Blue (#2563EB - Official Portal)</option>
                            <option value="#0284C7">Sky Blue (#0284C7 - Telegram)</option>
                            <option value="#7C3AED">Purple (#7C3AED - Premium)</option>
                            <option value="#F59E0B">Amber Gold (#F59E0B - Urgent Notice)</option>
                        </select>
                    </div>
                </div>

                <!-- Live Preview Box -->
                <div class="mt-6 pt-4 border-t border-slate-100">
                    <div class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Live Mobile App Drawer Preview</div>
                    <div class="max-w-xs p-3.5 rounded-2xl border shadow-sm flex items-center justify-between cursor-pointer transition-transform hover:scale-[1.01]"
                         :style="`background-color: ${color}15; border-color: ${color}40;`">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl flex items-center justify-center font-bold text-white shadow-sm"
                                 :style="`background-color: ${color};`">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
                            </div>
                            <div>
                                <div class="text-xs font-black text-slate-900" x-text="title || 'Button Title'"></div>
                                <div class="text-[10.5px] text-slate-500" x-text="subtitle || 'Tagline / Description'"></div>
                            </div>
                        </div>
                        <div class="w-7 h-7 rounded-full flex items-center justify-center" :style="`background-color: ${color}25; color: ${color};`">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- 3. Google Play Store App Version & In-App Update Control -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 space-y-6">
            <div class="flex items-center gap-3 pb-4 border-b border-slate-100">
                <div class="w-9 h-9 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                </div>
                <div>
                    <h2 class="text-base font-bold text-slate-900">Google Play Store Version & In-App Update Popup</h2>
                    <p class="text-xs text-slate-500">Control when students on older versions receive the in-app update prompt or mandatory upgrade modal.</p>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Latest Version Code</label>
                    <input type="number" name="latest_version_code" value="{{ old('latest_version_code', $latestVersionCode) }}" required min="1"
                           class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm font-bold text-slate-900 focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                    <p class="text-[11px] text-slate-500 mt-1">e.g. 5 (from <code>version: 1.0.0+5</code>). Any user with lower build code gets the update popup.</p>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Latest Version Name</label>
                    <input type="text" name="latest_version_name" value="{{ old('latest_version_name', $latestVersionName) }}" required
                           class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm font-bold text-slate-900 focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                    <p class="text-[11px] text-slate-500 mt-1">e.g. 1.0.0 or 1.0.1 (displayed in popup title).</p>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Min Supported Version Code</label>
                    <input type="number" name="min_version_code" value="{{ old('min_version_code', $minVersionCode) }}" required min="1"
                           class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm font-bold text-slate-900 focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                    <p class="text-[11px] text-slate-500 mt-1">Users below this code are strictly forced to update.</p>
                </div>
            </div>

            <!-- Force Update Toggle -->
            <div class="flex items-center justify-between p-4 bg-amber-50/60 rounded-xl border border-amber-200">
                <div class="space-y-1">
                    <label for="force_update" class="text-sm font-bold text-amber-950 cursor-pointer">
                        Force Immediate Update (Non-Dismissible)
                    </label>
                    <p class="text-xs text-amber-800/80 max-w-xl">
                        When enabled, older versions cannot skip or dismiss the update modal. They must tap "Update on Play Store" to continue.
                    </p>
                </div>
                <div class="relative inline-block w-12 mr-2 align-middle select-none">
                    <input type="checkbox" name="force_update" id="force_update" value="1" {{ $forceUpdate ? 'checked' : '' }}
                           class="toggle-checkbox absolute block w-6 h-6 rounded-full bg-white border-4 appearance-none cursor-pointer border-slate-300 checked:right-0 checked:border-amber-600 transition-all duration-200"/>
                    <label for="force_update" class="toggle-label block overflow-hidden h-6 rounded-full bg-slate-300 cursor-pointer"></label>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Update Modal Title</label>
                    <input type="text" name="update_title" value="{{ old('update_title', $updateTitle) }}" required
                           class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm font-medium text-slate-900 focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Play Store Direct URL</label>
                    <input type="text" name="play_store_url" value="{{ old('play_store_url', $playStoreUrl) }}" required
                           class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm font-mono text-slate-900 focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                    <p class="text-[11px] text-slate-500 mt-1"><code>market://details?id=com.crackaeee.app</code></p>
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Update Release Notes / Description</label>
                <textarea name="update_message" rows="3" required
                          class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm font-medium text-slate-900 focus:ring-2 focus:ring-primary-500 focus:border-primary-500">{{ old('update_message', $updateMessage) }}</textarea>
                <p class="text-[11px] text-slate-500 mt-1">This message is shown to students inside the update popup.</p>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="flex justify-end">
            <button type="submit" class="px-6 py-3 rounded-xl bg-primary-600 hover:bg-primary-700 text-white font-bold text-sm shadow-lg shadow-primary-600/25 transition-all">
                Save & Update App Controls
            </button>
        </div>
    </form>
</div>

<style>
/* Pure CSS toggle switch */
.toggle-checkbox:checked {
    right: 0;
    border-color: #9d174d;
}
.toggle-checkbox:checked + .toggle-label {
    background-color: #9d174d;
}
</style>
@endsection
