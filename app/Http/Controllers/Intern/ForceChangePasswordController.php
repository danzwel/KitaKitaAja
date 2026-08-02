<?php

namespace App\Http\Controllers\Intern;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class ForceChangePasswordController extends Controller
{
    public function create()
    {
        return view('intern.auth.force-change-password');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $intern = $request->user('intern');

        $intern->update([
            'password' => Hash::make($validated['password']),
            'temporary_initial_password' => null,
        ]);

        return redirect()->route('intern.dashboard')->with('status', 'Password berhasil diubah.');
    }
}
