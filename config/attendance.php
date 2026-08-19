<?php

return [
    'check_in_time' => env('ATTENDANCE_CHECK_IN_TIME', '07:45'),
    'check_out_time' => env('ATTENDANCE_CHECK_OUT_TIME', '16:00'),
    'latitude' => env('ATTENDANCE_LATITUDE'),
    'longitude' => env('ATTENDANCE_LONGITUDE'),
    'radius_meters' => (int) env('ATTENDANCE_RADIUS_METERS', 150),
];
