<?php

namespace App\Http\Controllers\Intern;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function edit(Request $request)
    {
        return view('intern.profile.edit', [
            'intern' => $request->user('intern'),
        ]);
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string'],
        ]);

        $request->user('intern')->update($validated);

        return redirect()->route('intern.profile.edit')->with('status', 'profile-updated');
    }

    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => ['required', 'current_password:intern'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $request->user('intern')->update([
            'password' => Hash::make($validated['password']),
        ]);

        return redirect()->route('intern.profile.edit')->with('status', 'password-updated');
    }

    public function uploadPhoto(Request $request)
    {
        $request->validate([
            'photo' => ['required', 'image', 'max:2048'],
        ]);

        $intern = $request->user('intern');

        if ($intern->photo) {
            Storage::disk('public')->delete($intern->photo);
        }

        $path = $request->file('photo')->store('intern-photos', 'public');
        $intern->update(['photo' => $path]);

        return redirect()->route('intern.profile.edit')->with('status', 'photo-updated');
    }
}
