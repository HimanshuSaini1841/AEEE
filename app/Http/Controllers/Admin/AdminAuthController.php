<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AdminAuthController extends Controller
{
    public function showLoginForm(): View
    {
        return view('admin.auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if (Auth::guard('web')->attempt(array_merge($credentials, ['is_active' => true]), $request->filled('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended(route('admin.dashboard'))->with('success', 'Welcome back to Crack AEEE Admin Portal.');
        }

        return back()->withErrors([
            'email' => 'Invalid admin credentials or account is deactivated.',
        ])->onlyInput('email');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login')->with('info', 'Logged out safely.');
    }
}
