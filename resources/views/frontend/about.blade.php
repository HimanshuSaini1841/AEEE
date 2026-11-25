@extends('layouts.frontend')

@section('title', 'About Us - Crack AEEE')

@section('content')
<!-- Header Banner -->
<section class="bg-gradient-to-b from-brand-50 to-white py-16 lg:py-20 border-b border-slate-200/70">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 text-center space-y-4">
        <span class="text-xs font-extrabold text-brand-600 uppercase tracking-widest bg-brand-100/70 px-3.5 py-1.5 rounded-full border border-brand-200">
            OUR MISSION & STORY
        </span>
        <h1 class="text-4xl sm:text-5xl font-black text-slate-900 tracking-tight">
            Empowering Aspirants to Excel in AEEE
        </h1>
        <p class="text-base sm:text-lg text-slate-600 font-medium max-w-2xl mx-auto leading-relaxed">
            Crack AEEE was established with a singular objective: to provide students with authentic, structured previous year papers and genuine guidance for the Amrita Entrance Examination - Engineering.
        </p>
    </div>
</section>

<!-- Main Narrative -->
<section class="py-16 bg-white">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 space-y-16">

        <!-- Who We Are -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-10 items-center">
            <div class="space-y-4">
                <span class="text-xs font-extrabold text-brand-600 uppercase tracking-widest">ABOUT THE PLATFORM</span>
                <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 leading-snug">
                    Bridging the Preparation Gap for Engineering Aspirants
                </h2>
                <p class="text-sm text-slate-600 leading-relaxed">
                    Amrita Vishwa Vidyapeetham is consistently ranked among the top universities in India, making the Amrita Entrance Examination - Engineering (AEEE) one of the most competitive tests for engineering candidates across Tamil Nadu, Kerala, Karnataka, and the rest of India.
                </p>
                <p class="text-sm text-slate-600 leading-relaxed">
                    Historically, students faced immense difficulties finding authentic past papers, resulting in unverified question compilations, spam-heavy websites, and paywalls. <strong>Crack AEEE</strong> eliminates these barriers by bringing official year-wise papers (2005 - 2026) directly to students in a safe, ad-free mobile environment.
                </p>
            </div>

            <div class="bg-slate-50 p-8 rounded-3xl border border-slate-200/80 shadow-sm space-y-4">
                <h3 class="text-lg font-bold text-slate-900">What Makes Us Different:</h3>
                <ul class="space-y-3 text-sm text-slate-700">
                    <li class="flex items-start space-x-3">
                        <i class="fa-solid fa-circle-check text-brand-600 mt-1"></i>
                        <span><strong>20+ Years Archive:</strong> Official question papers from 2005 right through 2026.</span>
                    </li>
                    <li class="flex items-start space-x-3">
                        <i class="fa-solid fa-circle-check text-brand-600 mt-1"></i>
                        <span><strong>Secure Offline Reading:</strong> Download papers once to study on the go without consuming mobile data.</span>
                    </li>
                    <li class="flex items-start space-x-3">
                        <i class="fa-solid fa-circle-check text-brand-600 mt-1"></i>
                        <span><strong>In-App DRM Protection:</strong> Built to protect materials from piracy and ensure uncorrupted viewing.</span>
                    </li>
                    <li class="flex items-start space-x-3">
                        <i class="fa-solid fa-circle-check text-brand-600 mt-1"></i>
                        <span><strong>Distraction-Free:</strong> Zero intrusive ads or unrelated pop-ups during your study sessions.</span>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Academic Standards & Pillars -->
        <div class="pt-8 border-t border-slate-200/80">
            <div class="text-center max-w-2xl mx-auto mb-12 space-y-3">
                <span class="text-xs font-extrabold text-brand-600 uppercase tracking-widest bg-brand-50 px-3 py-1 rounded-full border border-brand-200/60">
                    OUR CORE PILLARS
                </span>
                <h2 class="text-3xl font-extrabold text-slate-900">
                    Built for Student Success
                </h2>
                <p class="text-sm text-slate-600 font-medium">
                    We designed Crack AEEE from the ground up to provide a focused, distraction-free preparation ecosystem.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                
                <div class="p-8 rounded-3xl bg-slate-50 border border-slate-200/80 hover:border-brand-300 transition duration-300">
                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-brand-600 to-brand-700 text-white flex items-center justify-center text-xl shadow-md shadow-brand-600/20 mb-6">
                        <i class="fa-solid fa-book-open-reader"></i>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-2">Authentic Past Papers</h3>
                    <span class="text-xs font-bold text-brand-600 uppercase tracking-wider block mb-3">2005 - 2026 Complete Year-Wise Catalog</span>
                    <p class="text-sm text-slate-600 leading-relaxed">
                        Every question paper in our repository is thoroughly checked for authenticity and syllabus alignment. Students can practice with full-length papers reflecting the genuine examination format.
                    </p>
                </div>

                <div class="p-8 rounded-3xl bg-slate-50 border border-slate-200/80 hover:border-brand-300 transition duration-300">
                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-slate-800 to-slate-900 text-white flex items-center justify-center text-xl shadow-md shadow-slate-900/20 mb-6">
                        <i class="fa-solid fa-shield-halved"></i>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-2">Secure DRM In-App Technology</h3>
                    <span class="text-xs font-bold text-slate-500 uppercase tracking-wider block mb-3">Offline Study & Anti-Piracy Protection</span>
                    <p class="text-sm text-slate-600 leading-relaxed">
                        Our custom PDF viewer protects student study materials while enabling seamless offline reading. Download once, and practice anywhere without requiring an active internet connection.
                    </p>
                </div>

            </div>
        </div>

        <!-- Academic Disclaimer & Standards -->
        <div class="p-8 rounded-3xl bg-brand-50/50 border border-brand-200/60 space-y-3">
            <h4 class="text-base font-bold text-brand-900 flex items-center">
                <i class="fa-solid fa-circle-info text-brand-600 mr-2"></i> Important Academic Disclaimer
            </h4>
            <p class="text-xs text-slate-600 leading-relaxed">
                Crack AEEE is an independent educational tool created to assist engineering candidates in practicing question papers for AEEE. Amrita Vishwa Vidyapeetham is the sole owner of the registered trademark "AEEE". References to the exam are made strictly for educational and nominative purposes under fair use.
            </p>
            <div class="pt-2 text-xs text-slate-600">
                For administrative, copyright, or academic inquiries, contact us directly at <a href="mailto:hostcob.doon@gmail.com" class="font-bold text-brand-700 underline">hostcob.doon@gmail.com</a>.
            </div>
        </div>

    </div>
</section>
@endsection
