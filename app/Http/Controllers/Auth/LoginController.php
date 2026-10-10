<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    // SHOW LOGIN FORM
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // HANDLE LOGIN ATTEMPT
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            AuditLog::record(
                action: 'login',
                description: 'User logged in.',
            );

            return redirect()->intended(route('pos.index'));
        }

        return back()->withErrors([
            'email' => 'These credentials do not match our records.',
        ])->onlyInput('email');
    }

    // LOGOUT
    public function logout(Request $request)
    {
        AuditLog::record(
            action: 'logout',
            description: 'User logged out.',
        );

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}