@extends('layouts.frontend')

@section('title', 'Contact Us - Crack AEEE Support & Inquiries')

@section('content')
<!-- Header Banner -->
<section class="bg-gradient-to-b from-brand-50 to-white py-16 border-b border-slate-200/70">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center space-y-4">
        <span class="text-xs font-extrabold text-brand-600 uppercase tracking-widest bg-brand-100/70 px-3.5 py-1.5 rounded-full border border-brand-200">
            WE ARE HERE TO HELP
        </span>
        <h1 class="text-4xl font-black text-slate-900 tracking-tight">
            Get in Touch with Our Team
        </h1>
        <p class="text-base text-slate-600 font-medium max-w-xl mx-auto leading-relaxed">
            Have a question about AEEE past papers, app functionality, or account verification? Our support team is happy to assist you.
        </p>
    </div>
</section>

<!-- Content Grid -->
<section class="py-16 bg-white">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12">
            
            <!-- Left Info Panel (5 cols) -->
            <div class="lg:col-span-5 space-y-8">
                
                <div>
                    <h3 class="text-2xl font-extrabold text-slate-900 mb-2">Direct Contact Channels</h3>
                    <p class="text-sm text-slate-600 leading-relaxed">
                        Whether you are an aspirant needing help with the app, an educator suggesting study materials, or have feedback, reach out using the details below.
                    </p>
                </div>

                <!-- Primary Email Card -->
                <div class="p-6 rounded-2xl bg-brand-50/70 border border-brand-200/70 space-y-2">
                    <div class="w-10 h-10 rounded-xl bg-brand-600 text-white flex items-center justify-center text-lg mb-3">
                        <i class="fa-solid fa-envelope"></i>
                    </div>
                    <span class="text-xs font-bold text-brand-700 uppercase tracking-wider block">Official Support Email</span>
                    <a href="mailto:hostcob.doon@gmail.com" class="text-lg font-black text-slate-900 hover:text-brand-600 transition block break-all">
                        hostcob.doon@gmail.com
                    </a>
                    <p class="text-xs text-slate-500 pt-1">
                        We typically respond to student inquiries within 12 to 24 business hours.
                    </p>
                </div>

                <!-- Support Information -->
                <div class="space-y-4 pt-2">
                    <div class="flex items-start space-x-3 text-sm">
                        <div class="w-8 h-8 rounded-lg bg-slate-100 text-slate-700 flex items-center justify-center font-bold text-xs mt-0.5">
                            <i class="fa-solid fa-headset"></i>
                        </div>
                        <div>
                            <span class="block font-bold text-slate-800">Crack AEEE Student Support</span>
                            <span class="text-xs text-slate-500">Dedicated assistance for candidate registration and app access</span>
                        </div>
                    </div>

                    <div class="flex items-start space-x-3 text-sm">
                        <div class="w-8 h-8 rounded-lg bg-slate-100 text-slate-700 flex items-center justify-center font-bold text-xs mt-0.5">
                            <i class="fa-solid fa-clock"></i>
                        </div>
                        <div>
                            <span class="block font-bold text-slate-800">Support Working Hours</span>
                            <span class="text-xs text-slate-500">Monday &ndash; Saturday: 9:30 AM to 6:30 PM IST</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Form Panel (7 cols) -->
            <div class="lg:col-span-7">
                <div class="bg-white p-8 sm:p-10 rounded-3xl border border-slate-200/80 shadow-lg shadow-slate-100">
                    
                    <h3 class="text-2xl font-extrabold text-slate-900 mb-2">Send Us a Message</h3>
                    <p class="text-xs text-slate-500 mb-6">
                        Fill out the form below and our team will get in touch with you at your email.
                    </p>

                    @if(session('success'))
                    <div class="mb-6 p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm flex items-start space-x-3">
                        <i class="fa-solid fa-circle-check text-emerald-600 mt-0.5"></i>
                        <div>
                            <span class="font-bold block">Message Sent Successfully!</span>
                            <span>{{ session('success') }}</span>
                        </div>
                    </div>
                    @endif

                    @if($errors->any())
                    <div class="mb-6 p-4 rounded-xl bg-red-50 border border-red-200 text-red-800 text-sm space-y-1">
                        <span class="font-bold block">Please fix the following issues:</span>
                        <ul class="list-disc list-inside text-xs">
                            @foreach($errors->all() as $err)
                            <li>{{ $err }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    <form action="{{ route('frontend.contact.submit') }}" method="POST" class="space-y-5">
                        @csrf
                        
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                            <div>
                                <label for="name" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Your Full Name *</label>
                                <input type="text" id="name" name="name" value="{{ old('name') }}" required placeholder="e.g. Rahul Sharma" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-brand-600 focus:ring-2 focus:ring-brand-600/20 text-sm text-slate-800 transition outline-none">
                            </div>

                            <div>
                                <label for="email" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Email Address *</label>
                                <input type="email" id="email" name="email" value="{{ old('email') }}" required placeholder="e.g. rahul@example.com" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-brand-600 focus:ring-2 focus:ring-brand-600/20 text-sm text-slate-800 transition outline-none">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                            <div>
                                <label for="phone" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Phone Number (Optional)</label>
                                <input type="tel" id="phone" name="phone" value="{{ old('phone') }}" placeholder="10-digit mobile number" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-brand-600 focus:ring-2 focus:ring-brand-600/20 text-sm text-slate-800 transition outline-none">
                            </div>

                            <div>
                                <label for="subject" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Subject *</label>
                                <input type="text" id="subject" name="subject" value="{{ old('subject') }}" required placeholder="e.g. PYQ PDF Question or Feedback" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-brand-600 focus:ring-2 focus:ring-brand-600/20 text-sm text-slate-800 transition outline-none">
                            </div>
                        </div>

                        <div>
                            <label for="message" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Message *</label>
                            <textarea id="message" name="message" rows="5" required placeholder="How can we help your AEEE preparation journey?" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-brand-600 focus:ring-2 focus:ring-brand-600/20 text-sm text-slate-800 transition outline-none resize-none">{{ old('message') }}</textarea>
                        </div>

                        <button type="submit" class="w-full py-3.5 px-6 rounded-xl bg-gradient-to-r from-brand-700 to-brand-600 hover:from-brand-800 hover:to-brand-700 text-white font-bold text-sm shadow-lg shadow-brand-600/25 hover:shadow-xl transition transform hover:-translate-y-0.5 flex items-center justify-center space-x-2">
                            <span>Send Support Message</span>
                            <i class="fa-solid fa-paper-plane text-xs"></i>
                        </button>
                    </form>

                </div>
            </div>

        </div>
    </div>
</section>
@endsection
