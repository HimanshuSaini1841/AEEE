@extends('layouts.frontend')

@section('title', 'Privacy Policy - Crack AEEE | SARVGYAN & HOSTCOB SOLUTIONS PRIVATE LIMITED')

@section('content')
<!-- Header Banner -->
<section class="bg-gradient-to-b from-brand-50 to-white py-16 border-b border-slate-200/70">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center space-y-4">
        <span class="text-xs font-extrabold text-brand-600 uppercase tracking-widest bg-brand-100/70 px-3.5 py-1.5 rounded-full border border-brand-200">
            TRANSPARENCY & DATA PROTECTION
        </span>
        <h1 class="text-4xl font-black text-slate-900 tracking-tight">
            Privacy Policy
        </h1>
        <p class="text-sm text-slate-500 font-medium max-w-xl mx-auto">
            Last updated: November 18, 2026 &bull; Developed by HOSTCOB SOLUTIONS PRIVATE LIMITED & SARVGYAN
        </p>
    </div>
</section>

<!-- Content Section -->
<section class="py-16 bg-white">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="prose prose-slate max-w-none space-y-10 text-slate-700 leading-relaxed text-sm">
            
            <!-- Summary Box -->
            <div class="p-6 rounded-2xl bg-brand-50/60 border border-brand-200/70 text-slate-800 space-y-2">
                <h3 class="text-base font-bold text-brand-900 flex items-center">
                    <i class="fa-solid fa-shield-halved text-brand-600 mr-2"></i> Our Privacy Commitment to Students
                </h3>
                <p class="text-xs text-slate-600 leading-relaxed">
                    At <strong>Crack AEEE</strong> (developed and operated by <strong>HOSTCOB SOLUTIONS PRIVATE LIMITED</strong> in association with <strong>SARVGYAN</strong>), your privacy is our top priority. We do not sell or rent student data to third-party telemarketers or advertisers. We collect only what is strictly necessary to authenticate your candidate identity, deliver AEEE past question papers, and keep you informed of critical examination dates.
                </p>
            </div>

            <!-- 1. Introduction & Controllers -->
            <div>
                <h2 class="text-xl font-extrabold text-slate-900 mb-3">1. Introduction & Data Controllers</h2>
                <p>
                    This Privacy Policy explains how <strong>Crack AEEE</strong> ("we", "our", or "the Platform"), co-managed by <strong>SARVGYAN</strong> and <strong>HOSTCOB SOLUTIONS PRIVATE LIMITED</strong>, collects, processes, stores, and protects personal data from students, aspirants, and visitors who use our mobile application and website (<a href="{{ route('frontend.home') }}" class="text-brand-600 font-bold hover:underline">{{ url('/') }}</a>).
                </p>
                <p class="mt-2">
                    For any questions regarding this policy, our Data Protection team can be reached directly at <a href="mailto:hostcob.doon@gmail.com" class="text-brand-600 font-bold hover:underline">hostcob.doon@gmail.com</a>.
                </p>
            </div>

            <!-- 2. Information We Collect -->
            <div>
                <h2 class="text-xl font-extrabold text-slate-900 mb-3">2. Information We Collect</h2>
                <p>We collect information to provide a smooth, verified, and personalized educational experience:</p>
                <ul class="list-disc list-inside space-y-2 mt-3 pl-2">
                    <li>
                        <strong>Google Authentication Profile:</strong> When you sign in via Google OAuth, we receive your verified full name, primary email address, Google unique user ID, and profile picture URL.
                    </li>
                    <li>
                        <strong>Mobile Phone Number:</strong> We collect your 10-digit Indian mobile number to verify your genuine candidate profile via an SMS One-Time Password (OTP).
                    </li>
                    <li>
                        <strong>Academic Profile Details:</strong> During onboarding and within your profile settings, we collect your academic level (Class 11, Class 12, or Repeater/Dropper), target entrance exam (e.g., AEEE 2026), home state, and city/district to assist in exam center preference mapping and state quota eligibility information.
                    </li>
                    <li>
                        <strong>Device & Technical Identifiers:</strong> For push notification delivery (via Firebase Cloud Messaging) and application crash diagnostics, we collect device token, operating system version, and anonymous session statistics.
                    </li>
                </ul>
            </div>

            <!-- 3. How We Use Collected Data -->
            <div>
                <h2 class="text-xl font-extrabold text-slate-900 mb-3">3. How We Use Your Information</h2>
                <p>The information collected is strictly utilized for the following legitimate educational purposes:</p>
                <ul class="list-disc list-inside space-y-2 mt-3 pl-2">
                    <li>Granting secure candidate access to official AEEE Previous Years Question Papers (PYQs) from 2005 to 2026.</li>
                    <li>Authenticating mobile credentials to prevent bot spam, automated scrapers, and malicious accounts.</li>
                    <li>Enabling the DRM-protected offline download functionality so you can read question papers without consuming mobile internet data.</li>
                    <li>Delivering important push notifications regarding Amrita Entrance Examination deadlines, slot booking schedules, hall ticket releases, and counselling schedules.</li>
                    <li>Improving application stability, DRM file integrity, and server performance.</li>
                </ul>
            </div>

            <!-- 4. DRM & Offline Data Security -->
            <div>
                <h2 class="text-xl font-extrabold text-slate-900 mb-3">4. DRM Protection & In-App Offline Storage</h2>
                <p>
                    All PDF question papers downloaded by students within the Crack AEEE Android application are protected by Digital Rights Management (DRM) and sandboxed directly inside the application's isolated local private storage.
                </p>
                <p class="mt-2">
                    Downloaded papers cannot be extracted, exported to external file explorers, or forwarded to third-party applications. This guarantees authentic, untampered educational material and protects the copyrighted layout curated by <strong>SARVGYAN</strong> and <strong>HOSTCOB SOLUTIONS PRIVATE LIMITED</strong>.
                </p>
            </div>

            <!-- 5. Third-Party Service Providers -->
            <div>
                <h2 class="text-xl font-extrabold text-slate-900 mb-3">5. Third-Party Service Providers</h2>
                <p>We work exclusively with reputable service providers to power key infrastructure:</p>
                <ul class="list-disc list-inside space-y-2 mt-3 pl-2">
                    <li><strong>Google OAuth:</strong> To provide single sign-on authentication.</li>
                    <li><strong>Fast2SMS:</strong> To transmit high-priority verification SMS messages containing your 6-digit OTP code. Phone numbers are dispatched solely for SMS transmission under strict DLT guidelines.</li>
                    <li><strong>Firebase (Google LLC):</strong> To deliver real-time push notifications regarding examination schedules and answer key publications.</li>
                </ul>
                <p class="mt-3 font-semibold text-slate-900">
                    We strictly DO NOT sell, trade, or monetize student contact records with telemarketing agencies, tuition resellers, or third-party marketing companies.
                </p>
            </div>

            <!-- 6. Data Retention & Deletion -->
            <div>
                <h2 class="text-xl font-extrabold text-slate-900 mb-3">6. Data Retention & User Rights</h2>
                <p>
                    You retain full control over your academic information. You have the right to:
                </p>
                <ul class="list-disc list-inside space-y-2 mt-3 pl-2">
                    <li>Access and update your name, academic level, target exam, home state, and city at any time from the in-app Profile tab.</li>
                    <li>Request total deletion of your account and associated verification logs by emailing our grievance desk at <a href="mailto:hostcob.doon@gmail.com" class="text-brand-600 font-bold hover:underline">hostcob.doon@gmail.com</a> with the subject line <em>"Account Deletion Request"</em>. Requests are executed within 7 working days.</li>
                </ul>
            </div>

            <!-- 7. Grievance Officer & Contact -->
            <div class="p-6 rounded-2xl bg-slate-50 border border-slate-200/80 space-y-3">
                <h2 class="text-lg font-extrabold text-slate-900">7. Grievance Officer & Contact Desk</h2>
                <p class="text-xs text-slate-600 leading-relaxed">
                    In compliance with the Information Technology Act and applicable Indian digital data protection standards, if you have any questions, feedback, or grievances regarding our privacy policies, please reach out to our designated officer:
                </p>
                <div class="text-xs text-slate-700 space-y-1 pt-1 font-medium">
                    <p><strong>Entity:</strong> HOSTCOB SOLUTIONS PRIVATE LIMITED (in association with SARVGYAN)</p>
                    <p><strong>Email:</strong> <a href="mailto:hostcob.doon@gmail.com" class="text-brand-600 font-bold underline">hostcob.doon@gmail.com</a></p>
                    <p><strong>Location:</strong> Dehradun, Uttarakhand, India</p>
                    <p><strong>Response Time:</strong> Within 24-48 business hours</p>
                </div>
            </div>

        </div>

    </div>
</section>
@endsection
