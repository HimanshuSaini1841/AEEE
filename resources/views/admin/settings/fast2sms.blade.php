@extends('layouts.admin')

@section('page_title', 'Fast2SMS Gateway Settings')

@section('content')
<div class="max-w-5xl space-y-8">
    <!-- Top Status Banner -->
    <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-2xl bg-rose-50 text-rose-600 flex items-center justify-center font-black text-xl border border-rose-100 shadow-sm">
                SMS
            </div>
            <div>
                <div class="flex items-center gap-2">
                    <h3 class="text-base font-extrabold text-slate-900">Fast2SMS OTP Service</h3>
                    @if($config['mock'])
                        <span class="px-2.5 py-0.5 rounded-full bg-amber-100 text-amber-800 text-[10px] font-bold">
                            Mock Mode Active (OTP: {{ $config['default_test_otp'] }})
                        </span>
                    @else
                        <span class="px-2.5 py-0.5 rounded-full bg-emerald-100 text-emerald-800 text-[10px] font-bold">
                            Live Gateway Active
                        </span>
                    @endif
                </div>
                <p class="text-xs text-slate-500 mt-0.5">Manage Fast2SMS API Authorization Keys, Routes, and SMS sender headers used by the Flutter mobile app.</p>
            </div>
        </div>
        <a href="https://www.fast2sms.com/dashboard/dev-api" target="_blank" rel="noopener noreferrer"
           class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold transition">
            <span>Fast2SMS Dashboard</span>
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Fast2SMS Credentials Form -->
        <div class="lg:col-span-2 bg-white p-7 rounded-2xl border border-slate-200/80 shadow-sm">
            <form action="{{ route('admin.settings.fast2sms.update') }}" method="POST" class="space-y-6">
                @csrf

                <!-- Section: API Credentials -->
                <div>
                    <h4 class="text-xs font-bold text-slate-900 uppercase tracking-wider mb-4 pb-2 border-b border-slate-100">
                        1. API Authorization & Endpoint
                    </h4>

                    <div class="space-y-4">
                        <!-- API Key -->
                        <div x-data="{ show: false }">
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                Fast2SMS API Authorization Key *
                            </label>
                            <div class="relative">
                                <input :type="show ? 'text' : 'password'"
                                       name="api_key"
                                       value="{{ old('api_key', $config['api_key']) }}"
                                       placeholder="Paste your Fast2SMS authorization key here"
                                       class="w-full pl-4 pr-24 py-2.5 text-xs rounded-xl border border-slate-200 focus:ring-2 focus:ring-indigo-600 outline-none font-mono">
                                <button type="button" @click="show = !show"
                                        class="absolute right-2.5 top-1/2 -translate-y-1/2 px-2.5 py-1 text-[11px] font-bold text-slate-500 hover:text-slate-800 bg-slate-100 rounded-lg">
                                    <span x-text="show ? 'Hide' : 'Reveal'"></span>
                                </button>
                            </div>
                            <span class="text-[11px] text-slate-400 mt-1 block">Found under Fast2SMS Dashboard → Dev API tab.</span>
                        </div>

                        <!-- Endpoint URL -->
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                API Endpoint URL *
                            </label>
                            <input type="url" name="endpoint"
                                   value="{{ old('endpoint', $config['endpoint'] === 'https://docs.fast2sms.com/reference/authorization' ? 'https://www.fast2sms.com/dev/bulkV2' : $config['endpoint']) }}"
                                   placeholder="https://www.fast2sms.com/dev/bulkV2"
                                   required
                                   class="w-full px-4 py-2.5 text-xs rounded-xl border border-slate-200 focus:ring-2 focus:ring-[#9d174d] outline-none font-mono">
                            <span class="text-[11px] text-slate-400 mt-1 block font-mono">Must be: https://www.fast2sms.com/dev/bulkV2 (Do NOT use documentation URLs)</span>
                        </div>
                    </div>
                </div>

                <!-- Section: Delivery Route & Sender Configuration -->
                <div>
                    <h4 class="text-xs font-bold text-slate-900 uppercase tracking-wider mb-4 pb-2 border-b border-slate-100">
                        2. Route & Header Configuration
                    </h4>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Route Selection -->
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">SMS Route *</label>
                            <select name="route" class="w-full px-3.5 py-2.5 text-xs rounded-xl border border-slate-200 focus:ring-2 focus:ring-[#9d174d] outline-none bg-white">
                                <option value="otp" {{ $config['route'] === 'otp' ? 'selected' : '' }}>OTP Route (Quick OTP - Instant, No Template Needed)</option>
                                <option value="dlt" {{ $config['route'] === 'dlt' ? 'selected' : '' }}>DLT Enterprise Route (Approved Template & Custom Header SARVGN)</option>
                                <option value="q" {{ $config['route'] === 'q' ? 'selected' : '' }}>Quick SMS (q)</option>
                            </select>
                            <div class="mt-2 p-2.5 rounded-lg bg-amber-50 border border-amber-200/80 text-[11px] text-amber-900 leading-relaxed">
                                <strong>⚠️ If you got "Invalid Message ID":</strong><br>
                                • <strong>Easiest fix</strong>: Switch route to <strong>OTP Route (Quick OTP)</strong>. It sends instant OTPs with zero DLT hassle!<br>
                                • If using <strong>DLT Enterprise Route</strong>: The template and header (<code>SARVGN</code>) must first be approved inside your Fast2SMS dashboard under <strong>DLT SMS → Add Template</strong>.
                            </div>
                        </div>

                        <!-- Sender ID -->
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Sender Header ID *</label>
                            <input type="text" name="sender_id"
                                   value="{{ old('sender_id', $config['sender_id']) }}"
                                   maxlength="10"
                                   placeholder="FSTSMS"
                                   class="w-full px-3.5 py-2.5 text-xs rounded-xl border border-slate-200 focus:ring-2 focus:ring-indigo-600 outline-none uppercase font-bold">
                        </div>
                    </div>

                    <!-- DLT Fields (Optional) -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">DLT Entity ID</label>
                            <input type="text" name="entity_id"
                                   value="{{ old('entity_id', $config['entity_id']) }}"
                                   placeholder="e.g. 1201159230494100941"
                                   class="w-full px-3.5 py-2.5 text-xs rounded-xl border border-slate-200 focus:ring-2 focus:ring-[#9d174d] outline-none font-mono">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">DLT Template / Message ID</label>
                            <input type="text" name="template_id"
                                   value="{{ old('template_id', $config['template_id']) }}"
                                   placeholder="e.g. 1307160831555151961"
                                   class="w-full px-3.5 py-2.5 text-xs rounded-xl border border-slate-200 focus:ring-2 focus:ring-[#9d174d] outline-none font-mono">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">2nd Variable Value (Site Name)</label>
                            <input type="text" name="variable_2"
                                   value="{{ old('variable_2', $config['variable_2'] ?? 'Crack AEEE') }}"
                                   placeholder="Crack AEEE"
                                   class="w-full px-3.5 py-2.5 text-xs rounded-xl border border-slate-200 focus:ring-2 focus:ring-[#9d174d] outline-none">
                            <span class="text-[10px] text-slate-400 mt-0.5 block">For templates with 2nd variable {#var#}</span>
                        </div>
                    </div>
                </div>

                <!-- Section: Security & Expiration -->
                <div>
                    <h4 class="text-xs font-bold text-slate-900 uppercase tracking-wider mb-4 pb-2 border-b border-slate-100">
                        3. Expiration & Rate Limiting
                    </h4>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">OTP Validity (Minutes)</label>
                            <input type="number" name="otp_expiry_minutes"
                                   value="{{ old('otp_expiry_minutes', $config['otp_expiry_minutes']) }}"
                                   min="1" max="60"
                                   class="w-full px-3.5 py-2.5 text-xs rounded-xl border border-slate-200 focus:ring-2 focus:ring-indigo-600 outline-none">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Max Verification Attempts</label>
                            <input type="number" name="max_attempts"
                                   value="{{ old('max_attempts', $config['max_attempts']) }}"
                                   min="1" max="10"
                                   class="w-full px-3.5 py-2.5 text-xs rounded-xl border border-slate-200 focus:ring-2 focus:ring-indigo-600 outline-none">
                        </div>
                    </div>
                </div>

                <!-- Section: Testing / Mock Mode Toggle -->
                <div class="p-4 rounded-xl bg-slate-50 border border-slate-200/80 space-y-3">
                    <div class="flex items-center justify-between">
                        <div>
                            <label class="text-xs font-bold text-slate-900 flex items-center gap-2">
                                <span>Mock Mode (Local Offline Testing)</span>
                            </label>
                            <p class="text-[11px] text-slate-500">When enabled, no real SMS credits are deducted; OTP is simulated for mobile testing.</p>
                        </div>
                        <input type="checkbox" name="mock_mode" value="1" {{ $config['mock'] ? 'checked' : '' }}
                               class="w-5 h-5 rounded text-indigo-600 focus:ring-indigo-500 border-slate-300">
                    </div>

                    <div>
                        <label class="block text-[11px] font-bold text-slate-700 uppercase tracking-wider mb-1">Default Simulated OTP</label>
                        <input type="text" name="default_test_otp"
                               value="{{ old('default_test_otp', $config['default_test_otp']) }}"
                               maxlength="6"
                               class="w-40 px-3 py-1.5 text-xs rounded-lg border border-slate-200 focus:ring-2 focus:ring-indigo-600 outline-none font-mono font-bold">
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="pt-2 flex justify-end">
                    <button type="submit"
                            class="px-6 py-3 bg-[#9d174d] hover:bg-[#831843] text-white text-xs font-bold rounded-xl shadow-sm transition">
                        Save Fast2SMS Settings
                    </button>
                </div>
            </form>
        </div>

        <!-- Live SMS Connection Tester Card -->
        <div class="space-y-6">
            <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm">
                <div class="flex items-center gap-2 mb-3">
                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
                    <h4 class="font-bold text-slate-900 text-sm">Test Fast2SMS Connection</h4>
                </div>
                <p class="text-xs text-slate-500 mb-4">
                    Send a test OTP directly to your physical phone to verify that the Fast2SMS API key and headers are working.
                </p>

                <form action="{{ route('admin.settings.fast2sms.test') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Recipient Mobile Number</label>
                        <div class="relative">
                            <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-xs font-bold text-slate-400">+91</span>
                            <input type="text" name="test_phone"
                                   placeholder="9876543210"
                                   maxlength="10"
                                   required
                                   class="w-full pl-12 pr-3.5 py-2.5 text-xs rounded-xl border border-slate-200 focus:ring-2 focus:ring-indigo-600 outline-none font-mono">
                        </div>
                    </div>

                    <button type="submit"
                            class="w-full py-2.5 bg-slate-900 hover:bg-slate-800 text-white text-xs font-bold rounded-xl shadow-sm transition flex items-center justify-center gap-2">
                        <svg class="w-4 h-4 text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                        Send Test OTP via Fast2SMS
                    </button>
                </form>
            </div>

            <!-- Quick Docs Card -->
            <div class="p-5 rounded-2xl bg-indigo-50/60 border border-indigo-100 text-xs text-indigo-900 space-y-2">
                <div class="font-bold text-indigo-950 flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-indigo-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/></svg>
                    Fast2SMS Setup Instructions
                </div>
                <p class="text-indigo-800/80 leading-relaxed">
                    1. Create an account on <a href="https://www.fast2sms.com" target="_blank" class="underline font-bold">fast2sms.com</a>.<br>
                    2. Add SMS wallet balance.<br>
                    3. Copy the API key from <strong>Dev API</strong> and paste it here.<br>
                    4. Set Route to <strong>OTP Route</strong> for instant DND delivery without template approval.
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
