<?php

namespace App\Http\Controllers\Admin;

use App\Models\Admin;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ProfileController
{
    public function edit(): View
    {
        return view('admin.profile.edit', [
            'admin' => auth('admin')->user(),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $admin = $request->user('admin');

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique(Admin::class)->ignore($admin->id)],
        ]);

        $admin->update($validated);

        return back()->with('success', 'Profil admin berhasil diperbarui.');
    }

    public function updatePassword(Request $request): RedirectResponse
    {
        $request->validate([
            'current_password' => ['required', 'current_password:admin'],
            'password' => ['required', 'confirmed', 'min:8'],
        ]);

        $request->user('admin')->update([
            'password' => Hash::make($request->string('password')->toString()),
        ]);

        return back()->with('success', 'Password admin berhasil diperbarui.');
    }
}
