<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LandingController extends Controller
{
    public function index(Request $request)
    {
        // Port forwarding VS Code memakai domain devtunnels.ms. Arahkan hanya
        // akses dari port forward ke login mahasiswa; URL utama tetap beranda.
        if (str_ends_with(strtolower($request->getHost()), '.devtunnels.ms')) {
            return redirect('/mahasiswa/login');
        }

        return view('public.home');
    }
}
