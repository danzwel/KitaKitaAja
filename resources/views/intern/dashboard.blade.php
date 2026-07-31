@extends('layouts.app')

@section('title', 'Dashboard Mahasiswa')

@section('content')
<div class="mx-auto max-w-4xl px-6 py-16">
    <div class="rounded-xl border border-gray-100 bg-white p-6 shadow-sm">
        <h1 class="font-display text-2xl font-bold text-navy">Dashboard Mahasiswa</h1>
        <p class="mt-3 text-gray-700">Selamat datang, {{ auth('intern')->user()->name }}.</p>
        <form method="POST" action="{{ route('intern.logout') }}" class="mt-6">
            @csrf
            <button class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-semibold text-gray-700">Logout</button>
        </form>
    </div>
</div>
@endsection
