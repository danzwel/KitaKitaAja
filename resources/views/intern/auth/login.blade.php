@extends('layouts.app')

@section('title', 'Login Mahasiswa')

@section('content')
<div class="mx-auto max-w-md px-6 py-16">
    <div class="rounded-xl border border-gray-100 bg-white p-6 shadow-sm">
        <h1 class="font-display text-2xl font-bold text-navy">Login Mahasiswa</h1>
        <p class="mt-2 text-sm text-gray-600">Gunakan username dan password dari hasil cek status pengajuan.</p>
        <form method="POST" action="{{ route('intern.login') }}" class="mt-6 space-y-4">
            @csrf
            <div>
                <label for="username" class="block text-sm font-medium text-gray-700">Username</label>
                <input id="username" name="username" value="{{ old('username') }}" required autofocus class="mt-1 block w-full rounded-lg border-gray-300">
                @error('username')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                <input id="password" type="password" name="password" required class="mt-1 block w-full rounded-lg border-gray-300">
                @error('password')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>
            <button class="w-full rounded-lg bg-health px-4 py-3 font-semibold text-white">Masuk</button>
        </form>
    </div>
</div>
@endsection
