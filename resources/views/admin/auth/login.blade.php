@extends('layouts.auth')

@section('content')
<div class="bg-white rounded-2xl shadow-2xl border border-slate-100 p-8">
    <div class="text-center mb-8">
        <img src="{{ asset('images/logo.png') }}" alt="Crack AEEE" class="w-16 h-16 mx-auto mb-3 object-contain">
        <h2 class="text-2xl font-bold text-slate-900 tracking-tight">Crack AEEE Admin Portal</h2>
        <p class="text-xs text-slate-500 mt-1">Student Management & Push Broadcast Console</p>
    </div>

    @if($errors->any())
        <div class="mb-5 p-3 rounded-xl bg-rose-50 border border-rose-200 text-rose-700 text-xs">
            {{ $errors->first() }}
        </div>
    @endif

    <form action="{{ route('admin.login.submit') }}" method="POST" class="space-y-4">
        @csrf
        <div>
            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Admin Email</label>
            <input type="email" name="email" value="{{ old('email') }}" required autofocus
                   class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-[#9d174d] focus:border-[#9d174d] outline-none transition"
                   placeholder="Enter your admin email">
        </div>

        <div>
            <div class="mb-1.5">
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Password</label>
            </div>
            <input type="password" name="password" required
                   class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-[#9d174d] focus:border-[#9d174d] outline-none transition"
                   placeholder="Enter your password">
        </div>

        <div class="flex items-center justify-between text-xs text-slate-600 py-1">
            <label class="flex items-center gap-2 cursor-pointer">
                <input type="checkbox" name="remember" class="rounded text-[#9d174d] focus:ring-[#9d174d] border-slate-300">
                <span>Remember this computer</span>
            </label>
        </div>

        <button type="submit"
                class="w-full py-3.5 px-4 rounded-xl bg-[#9d174d] hover:bg-[#831843] text-white font-semibold text-sm transition shadow-lg shadow-[#9d174d]/25">
            Sign In to Console
        </button>
    </form>
</div>
@endsection
