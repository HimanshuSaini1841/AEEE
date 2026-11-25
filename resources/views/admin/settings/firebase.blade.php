@extends('layouts.admin')

@section('title', 'Firebase Push Notification Settings')

@section('content')
<div class="max-w-4xl space-y-8">
    
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-900 tracking-tight">Firebase Cloud Messaging (FCM)</h1>
            <p class="text-xs text-slate-500 mt-1">Configure credentials to broadcast push notifications directly to students' physical devices.</p>
        </div>

        <div>
            @if($isConfigured)
                <span class="inline-flex items-center px-3.5 py-1.5 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 mr-2 animate-pulse"></span>
                    Firebase Connected & Ready
                </span>
            @else
                <span class="inline-flex items-center px-3.5 py-1.5 rounded-full text-xs font-bold bg-amber-50 text-amber-700 border border-amber-200">
                    <span class="w-2 h-2 rounded-full bg-amber-500 mr-2"></span>
                    Credentials Missing (Mock Mode)
                </span>
            @endif
        </div>
    </div>

    @if(session('success'))
    <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm flex items-start space-x-3">
        <svg class="w-5 h-5 text-emerald-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
        <div>
            <span class="font-bold block">Success</span>
            <span>{{ session('success') }}</span>
        </div>
    </div>
    @endif

    @if(session('error'))
    <div class="p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-sm flex items-start space-x-3">
        <svg class="w-5 h-5 text-rose-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        <div>
            <span class="font-bold block">Error</span>
            <span>{{ session('error') }}</span>
        </div>
    </div>
    @endif

    <!-- Instructions Card -->
    <div class="bg-gradient-to-r from-brand-50 to-slate-50 p-6 rounded-2xl border border-brand-200/60 text-xs text-slate-700 space-y-3">
        <h4 class="font-bold text-slate-900 text-sm flex items-center">
            <svg class="w-4 h-4 text-brand-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            How to get your Firebase Service Account JSON (Recommended HTTP v1 API)
        </h4>
        <ol class="list-decimal list-inside space-y-1.5 pl-2 text-slate-600">
            <li>Open the <a href="https://console.firebase.google.com/" target="_blank" class="text-brand-600 font-bold underline">Firebase Console</a> and select your project (<strong>crack-aeee</strong>).</li>
            <li>Click the <strong>Settings Gear</strong> <i class="fa-solid fa-gear"></i> next to <em>Project Overview</em> &rarr; <strong>Project settings</strong>.</li>
            <li>Navigate to the <strong>Service accounts</strong> tab.</li>
            <li>Click the blue <strong>"Generate new private key"</strong> button &rarr; click <strong>Generate key</strong> to download the <code class="bg-white px-1 py-0.5 rounded border border-slate-200">.json</code> file.</li>
            <li>Upload or paste the downloaded JSON file below and click <strong>Save Firebase Settings</strong>.</li>
        </ol>
    </div>

    <!-- Main Settings Form -->
    <form action="{{ route('admin.settings.firebase.update') }}" method="POST" enctype="multipart/form-data" class="bg-white p-8 rounded-2xl border border-slate-200/80 shadow-sm space-y-6">
        @csrf

        <div class="border-b border-slate-100 pb-4">
            <h3 class="text-base font-bold text-slate-900">Firebase Credentials</h3>
            <p class="text-xs text-slate-500">Google Cloud & Firebase authorization settings for Crack AEEE push messaging.</p>
        </div>

        <div class="space-y-5">
            <!-- Project ID -->
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Firebase Project ID *</label>
                <input type="text" name="project_id" value="{{ old('project_id', $projectId) }}" required placeholder="crack-aeee" class="w-full px-4 py-2.5 text-sm rounded-xl border border-slate-200 focus:border-brand-600 focus:ring-2 focus:ring-brand-600/20 outline-none">
                <span class="text-[11px] text-slate-400 mt-1 block">From your <code class="text-slate-600">google-services.json</code> (e.g. crack-aeee).</span>
            </div>

            <!-- Service Account File Upload -->
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Upload Service Account JSON File (Recommended)</label>
                <input type="file" name="service_account_file" accept=".json,application/json" class="w-full px-4 py-2 text-xs rounded-xl border border-slate-200 focus:border-brand-600 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-brand-50 file:text-brand-700 hover:file:bg-brand-100 outline-none">
                <span class="text-[11px] text-slate-400 mt-1 block">Upload the <code class="text-slate-600">*.json</code> file downloaded from Firebase Console Service Accounts.</span>
            </div>

            <!-- Or Paste JSON -->
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Or Paste Service Account JSON Text</label>
                <textarea name="service_account_json" rows="4" placeholder='{"type": "service_account", "project_id": "crack-aeee", "private_key": "-----BEGIN PRIVATE KEY-----...", "client_email": "firebase-adminsdk@..."}' class="w-full px-4 py-3 text-xs font-mono rounded-xl border border-slate-200 focus:border-brand-600 focus:ring-2 focus:ring-brand-600/20 outline-none">{{ old('service_account_json', $serviceAccountJson) }}</textarea>
            </div>

            <!-- Legacy Server Key (Optional) -->
            <div class="pt-2 border-t border-slate-100">
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Legacy Cloud Messaging Server Key (Optional Fallback)</label>
                <input type="text" name="server_key" value="{{ old('server_key', ($serverKey !== 'your_firebase_server_key_here' ? $serverKey : '')) }}" placeholder="AAAA... (Optional if Service Account JSON is provided)" class="w-full px-4 py-2.5 text-sm font-mono rounded-xl border border-slate-200 focus:border-brand-600 focus:ring-2 focus:ring-brand-600/20 outline-none">
            </div>
        </div>

        <div class="pt-4 border-t border-slate-100 flex items-center justify-between">
            <span class="text-xs text-slate-500">Currently registered active devices: <strong class="text-slate-800">{{ $totalActiveDevices }} device(s)</strong></span>
            <button type="submit" class="px-6 py-2.5 bg-brand-600 hover:bg-brand-700 text-white font-bold text-xs rounded-xl shadow-md transition">
                Save Firebase Settings
            </button>
        </div>
    </form>

    <!-- Test Dispatch Card -->
    <div class="bg-white p-8 rounded-2xl border border-slate-200/80 shadow-sm space-y-4">
        <div>
            <h3 class="text-base font-bold text-slate-900">Live Test Notification</h3>
            <p class="text-xs text-slate-500">Broadcast a real test push notification to all {{ $totalActiveDevices }} registered device(s) to verify delivery.</p>
        </div>

        <form action="{{ route('admin.settings.firebase.test') }}" method="POST">
            @csrf
            <button type="submit" {{ !$isConfigured ? 'disabled' : '' }} class="px-6 py-2.5 {{ $isConfigured ? 'bg-slate-900 hover:bg-black text-white' : 'bg-slate-200 text-slate-400 cursor-not-allowed' }} font-bold text-xs rounded-xl shadow-sm transition flex items-center space-x-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                <span>Send Live Test Push Notification</span>
            </button>
            @if(!$isConfigured)
            <span class="text-[11px] text-amber-600 mt-2 block">Configure and save your Firebase credentials above first to enable test dispatch.</span>
            @endif
        </form>
    </div>

</div>
@endsection
