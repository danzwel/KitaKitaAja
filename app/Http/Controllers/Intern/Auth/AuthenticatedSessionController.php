<?php

namespace App\Http\Controllers\Intern\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    public function create(): View
    {
        return view('intern.auth.login');
    }

    public function store(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        if (! Auth::guard('intern')->attempt($credentials, $request->boolean('remember'))) {
            return back()->withErrors(['username' => 'Username atau password tidak sesuai.'])->onlyInput('username');
        }

        $request->session()->regenerate();

        return redirect()->intended(route('intern.dashboard'));
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('intern')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('intern.login');
    }
}
