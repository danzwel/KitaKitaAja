<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class AdminAccountController extends Controller
{
    public function index(): View
    {
        return view('admin.accounts.index', [
            'admins' => Admin::query()->orderByDesc('is_active')->orderBy('name')->get(),
        ]);
    }

    public function create(): View
    {
        return view('admin.accounts.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:admins,email'],
            'password' => ['required', 'confirmed', 'min:8'],
        ]);

        Admin::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'is_active' => true,
        ]);

        return redirect()->route('admin.accounts.index')
            ->with('success', 'Akun admin baru berhasil ditambahkan.');
    }

    public function edit(Request $request, Admin $account): View|RedirectResponse
    {
        if ($account->is($request->user('admin'))) {
            return redirect()->route('admin.profile.edit')
                ->with('success', 'Gunakan menu Profil Saya untuk mengubah akun Anda sendiri.');
        }

        return view('admin.accounts.edit', compact('account'));
    }

    public function update(Request $request, Admin $account): RedirectResponse
    {
        $currentAdmin = $request->user('admin');
        if ($account->is($currentAdmin)) {
            return back()->with('error', 'Gunakan menu Profil Saya untuk mengubah akun Anda sendiri.');
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique(Admin::class)->ignore($account->id)],
            'is_active' => ['required', 'boolean'],
        ]);

        if ($account->is_active && ! $validated['is_active'] && Admin::where('is_active', true)->count() <= 1) {
            return back()->with('error', 'Minimal harus ada satu akun admin aktif.');
        }

        $account->update($validated);

        return redirect()->route('admin.accounts.index')
            ->with('success', 'Akun admin berhasil diperbarui.');
    }

    public function resetPassword(Request $request, Admin $account): RedirectResponse
    {
        if ($account->is($request->user('admin'))) {
            return back()->with('error', 'Gunakan menu Profil Saya untuk mengubah password akun Anda sendiri.');
        }

        $validated = $request->validate([
            'password' => ['required', 'confirmed', 'min:8'],
        ]);

        $account->update([
            'password' => Hash::make($validated['password']),
            'remember_token' => null,
        ]);

        return back()->with('success', 'Password akun admin berhasil diperbarui.');
    }
}
