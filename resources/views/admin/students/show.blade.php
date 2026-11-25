@extends('layouts.admin')

@section('page_title', 'Student Profile Details')

@section('content')
<div class="space-y-6 max-w-4xl">
    <div class="flex items-center justify-between">
        <a href="{{ route('admin.students.index') }}" class="text-xs text-indigo-600 hover:underline font-bold flex items-center gap-1">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            Back to Students List
        </a>
        <div class="flex items-center gap-2">
            <form action="{{ route('admin.students.toggle-status', $student->id) }}" method="POST">
                @csrf
                <button type="submit" class="px-4 py-2 rounded-xl text-xs font-bold text-white shadow-sm transition {{ $student->status === 'active' ? 'bg-amber-600 hover:bg-amber-700' : 'bg-emerald-600 hover:bg-emerald-700' }}">
                    {{ $student->status === 'active' ? 'Block Account' : 'Activate Account' }}
                </button>
            </form>
            <form action="{{ route('admin.students.destroy', $student->id) }}" method="POST" onsubmit="return confirm('Permanently delete student \'{{ addslashes($student->name) }}\'? All login tokens and verifications will be wiped immediately.');">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-4 py-2 rounded-xl text-xs font-bold text-white bg-rose-600 hover:bg-rose-700 shadow-sm transition">
                    Delete Student Completely
                </button>
            </form>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6 space-y-6">
        <!-- Header Profile -->
        <div class="flex items-center gap-4 pb-6 border-b border-slate-100">
            <div class="w-16 h-16 rounded-2xl bg-indigo-600 text-white flex items-center justify-center font-black text-2xl shadow-lg shadow-indigo-500/20">
                {{ strtoupper(substr($student->name, 0, 1)) }}
            </div>
            <div>
                <h3 class="text-xl font-extrabold text-slate-900">{{ $student->name }}</h3>
                <div class="text-xs text-slate-500 flex items-center gap-2 mt-1">
                    <span>{{ $student->email }}</span>
                    <span>•</span>
                    <span class="font-mono">Google ID: {{ $student->google_id }}</span>
                </div>
            </div>
        </div>

        <!-- Demographic & Dynamic Choices -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="p-4 rounded-xl bg-slate-50 border border-slate-100">
                <span class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-1">Target Exam</span>
                <span class="text-sm font-bold text-indigo-700">{{ $student->preparingFor->name ?? 'Not selected' }}</span>
            </div>

            <div class="p-4 rounded-xl bg-slate-50 border border-slate-100">
                <span class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-1">Current Class</span>
                <span class="text-sm font-bold text-slate-800">{{ $student->studentClass->name ?? 'Not selected' }}</span>
            </div>

            <div class="p-4 rounded-xl bg-slate-50 border border-slate-100">
                <span class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-1">Home State</span>
                <span class="text-sm font-bold text-slate-800">{{ $student->state->name ?? 'Not selected' }}</span>
            </div>
        </div>

        <!-- Mobile & Fast2SMS Verification -->
        <div class="p-4 rounded-xl bg-slate-50 border border-slate-100 space-y-2">
            <h4 class="text-xs font-bold text-slate-700 uppercase tracking-wider">Fast2SMS OTP Verification</h4>
            <div class="flex items-center gap-4 text-xs">
                <div>Mobile: <strong class="font-mono text-slate-900">{{ $student->phone ?? 'Not provided' }}</strong></div>
                <div>Status: 
                    @if($student->is_phone_verified)
                        <span class="text-emerald-600 font-bold">✓ Verified on {{ $student->phone_verified_at?->format('d M Y, h:i A') }}</span>
                    @else
                        <span class="text-amber-600 font-bold">Pending OTP Submission</span>
                    @endif
                </div>
            </div>
        </div>

        <!-- Registered Push Notification Devices (FCM) -->
        <div class="space-y-3">
            <h4 class="text-xs font-bold text-slate-700 uppercase tracking-wider">Firebase FCM Device Registrations ({{ $student->deviceTokens->count() }})</h4>
            <div class="space-y-2">
                @forelse($student->deviceTokens as $token)
                    <div class="p-3 rounded-xl bg-slate-50 border border-slate-100 flex items-center justify-between text-xs">
                        <div class="flex items-center gap-3 overflow-hidden">
                            <span class="px-2 py-0.5 rounded bg-slate-200 text-slate-700 font-bold uppercase text-[10px]">{{ $token->device_type }}</span>
                            <span class="font-mono text-slate-500 truncate max-w-md">{{ $token->token }}</span>
                        </div>
                        <span class="text-[10px] text-slate-400 shrink-0">Last used: {{ $token->last_used_at?->diffForHumans() ?? 'Recent' }}</span>
                    </div>
                @empty
                    <p class="text-xs text-slate-400 italic">No push notification tokens registered yet from mobile app.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
