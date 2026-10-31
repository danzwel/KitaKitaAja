<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use App\Services\InternshipStatusService;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::call(fn () => app(InternshipStatusService::class)->syncExpired())
    ->dailyAt('00:05')
    ->name('sync-expired-internships')
    ->withoutOverlapping();
