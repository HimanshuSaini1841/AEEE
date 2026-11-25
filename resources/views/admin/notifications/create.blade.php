@extends('layouts.admin')

@section('page_title', 'Compose Firebase Push Notification')

@section('content')
<div class="max-w-4xl space-y-6">
    @if(!$isConfigured)
    <div class="p-4 rounded-xl bg-amber-50 border border-amber-200 text-amber-800 text-sm flex items-start justify-between">
        <div class="flex items-start space-x-3">
            <svg class="w-5 h-5 text-amber-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
            <div>
                <span class="font-bold block">Firebase Cloud Messaging is Not Configured</span>
                <span class="text-xs">Your notifications are running in simulation/mock mode and will not reach physical devices. Please upload your Firebase Service Account JSON.</span>
            </div>
        </div>
        <a href="{{ route('admin.settings.firebase') }}" class="px-3 py-1.5 bg-amber-600 hover:bg-amber-700 text-white rounded-lg text-xs font-bold whitespace-nowrap ml-4">
            Configure Firebase &rarr;
        </a>
    </div>
    @endif

    <div class="bg-white p-8 rounded-2xl border border-slate-200/80 shadow-sm space-y-6">
        <div class="border-b border-slate-100 pb-4">
            <h3 class="text-lg font-extrabold text-slate-900">Broadcast Push Notification</h3>
            <p class="text-xs text-slate-500 mt-1">Delivered via Firebase Cloud Messaging (FCM) queue to students' mobile devices.</p>
        </div>

        <form action="{{ route('admin.notifications.send') }}" method="POST" class="space-y-6">
            @csrf

            <!-- Notification Content -->
            <div class="space-y-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Notification Title *</label>
                    <input type="text" name="title" required
                           placeholder="e.g. AEEE 2026 Mock Test Schedule Released!"
                           class="w-full px-4 py-3 text-sm rounded-xl border border-slate-200 focus:ring-2 focus:ring-indigo-600 outline-none">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Notification Message (Body) *</label>
                    <textarea name="body" rows="4" required
                              placeholder="e.g. Check your exam slot and practice questions on the dashboard now..."
                              class="w-full px-4 py-3 text-sm rounded-xl border border-slate-200 focus:ring-2 focus:ring-indigo-600 outline-none"></textarea>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Rich Image Banner URL (Optional)</label>
                    <input type="url" name="image_url"
                           placeholder="https://example.com/banner.jpg"
                           class="w-full px-4 py-3 text-sm rounded-xl border border-slate-200 focus:ring-2 focus:ring-indigo-600 outline-none">
                </div>
            </div>

            <!-- Audience Segmentation -->
            <div class="pt-4 border-t border-slate-100 space-y-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h4 class="text-xs font-bold text-slate-900 uppercase tracking-wider">Target Audience Filters</h4>
                        <p class="text-[11px] text-slate-400">Leave fields unselected to broadcast to ALL students.</p>
                    </div>
                    <span class="px-3 py-1 bg-indigo-50 text-indigo-700 rounded-full text-xs font-bold">
                        {{ number_format($totalDevices) }} Active App Devices Total
                    </span>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-600 mb-1">Target Class</label>
                        <select name="class_id" class="w-full px-3 py-2 text-xs rounded-xl border border-slate-200 focus:ring-2 focus:ring-indigo-600 outline-none bg-white">
                            <option value="">All Classes</option>
                            @foreach($classes as $c)
                                <option value="{{ $c->id }}">{{ $c->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-600 mb-1">Target Exam (Preparing For)</label>
                        <select name="preparing_for_id" class="w-full px-3 py-2 text-xs rounded-xl border border-slate-200 focus:ring-2 focus:ring-indigo-600 outline-none bg-white">
                            <option value="">All Exams</option>
                            @foreach($exams as $e)
                                <option value="{{ $e->id }}">{{ $e->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-600 mb-1">Target State</label>
                        <select name="state_id" class="w-full px-3 py-2 text-xs rounded-xl border border-slate-200 focus:ring-2 focus:ring-indigo-600 outline-none bg-white">
                            <option value="">All States</option>
                            @foreach($states as $s)
                                <option value="{{ $s->id }}">{{ $s->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="pt-4 flex items-center justify-end gap-3">
                <a href="{{ route('admin.notifications.history') }}" class="px-5 py-3 text-xs font-bold text-slate-600 hover:bg-slate-100 rounded-xl transition">
                    View History
                </a>
                <button type="submit" class="px-6 py-3 bg-[#9d174d] hover:bg-[#831843] text-white text-xs font-bold rounded-xl shadow-lg shadow-[#9d174d]/20 transition flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                    Dispatch Push Broadcast via Queue
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
