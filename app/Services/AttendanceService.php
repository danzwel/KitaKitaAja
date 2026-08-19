<?php

namespace App\Services;

use App\Models\AttendanceSession;
use Carbon\Carbon;

class AttendanceService
{
    public function distanceMeters(?float $latitude, ?float $longitude, ?float $targetLatitude, ?float $targetLongitude): ?int
    {
        if ($latitude === null || $longitude === null || $targetLatitude === null || $targetLongitude === null) {
            return null;
        }

        $earthRadius = 6371000;
        $latDelta = deg2rad($targetLatitude - $latitude);
        $lngDelta = deg2rad($targetLongitude - $longitude);
        $a = sin($latDelta / 2) ** 2
            + cos(deg2rad($latitude)) * cos(deg2rad($targetLatitude)) * sin($lngDelta / 2) ** 2;

        return (int) round($earthRadius * 2 * atan2(sqrt($a), sqrt(1 - $a)));
    }

    public function locationStatus(AttendanceSession $session, ?int $distance): string
    {
        if ($distance === null) {
            return $session->latitude === null || $session->longitude === null
                ? 'gps_tidak_dikonfigurasi'
                : 'menunggu_verifikasi';
        }

        return $distance <= $session->radius_meters ? 'valid' : 'di_luar_radius';
    }

    public function timeStatus(string $type, Carbon $now): string
    {
        $configured = $type === 'datang'
            ? config('attendance.check_in_time', '07:45')
            : config('attendance.check_out_time', '16:00');

        return $type === 'datang' && $now->format('H:i') > $configured
            ? 'terlambat'
            : 'tepat_waktu';
    }
}
