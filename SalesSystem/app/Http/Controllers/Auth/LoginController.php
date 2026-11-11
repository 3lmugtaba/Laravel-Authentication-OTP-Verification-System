<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (!Auth::attempt($credentials)) {
            return back()->withErrors(['email' => 'The supplied credentials are incorrect.'])->onlyInput('email');
        }

        if (!Auth::user()->is_Verified) {
            Auth::logout();
            return back()->withErrors(['email' => 'Verify your account before logging in.'])->onlyInput('email');
        }

        $request->session()->regenerate();
        return redirect()->intended(route('Dash'));
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login')->with('status', 'You have been logged out.');
    }
}
