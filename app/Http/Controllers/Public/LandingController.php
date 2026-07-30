<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;

class LandingController extends Controller
{
    public function index()
    {
        return view('public.home');
    }
}