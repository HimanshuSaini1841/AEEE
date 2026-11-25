<?php

namespace App\Http\Controllers;

use App\Models\Pyq;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class FrontendController extends Controller
{
    /**
     * Homepage - Crack AEEE landing page
     */
    public function home(): View
    {
        $pyqs = Pyq::where('is_active', true)
            ->orderBy('year', 'desc')
            ->take(8)
            ->get();

        $totalPyqs = Pyq::where('is_active', true)->count();
        $totalStudents = Student::count();
        $availableYears = Pyq::where('is_active', true)->distinct()->pluck('year')->sortDesc()->values();

        return view('frontend.home', compact('pyqs', 'totalPyqs', 'totalStudents', 'availableYears'));
    }

    /**
     * About Us page - Mission, SarvGyan & Hostcob Solutions background
     */
    public function about(): View
    {
        return view('frontend.about');
    }

    /**
     * Contact Us page
     */
    public function contact(): View
    {
        return view('frontend.contact');
    }

    /**
     * Handle Contact Form submission
     */
    public function contactSubmit(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|max:150',
            'phone' => 'nullable|string|max:20',
            'subject' => 'required|string|max:150',
            'message' => 'required|string|max:2000',
        ]);

        // You can log or send notification here
        return redirect()->route('frontend.contact')->with('success', 'Thank you for reaching out! Your message has been received and our team will get back to you shortly.');
    }

    /**
     * Privacy Policy page
     */
    public function privacy(): View
    {
        return view('frontend.privacy');
    }

    /**
     * Terms & Conditions page
     */
    public function terms(): View
    {
        return view('frontend.terms');
    }
}
