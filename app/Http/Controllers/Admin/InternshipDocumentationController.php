<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\InternshipDocumentationPhoto;
use Illuminate\Http\Request;
use Illuminate\View\View;

class InternshipDocumentationController extends Controller
{
    public function index(Request $request): View
    {
        $currentYear = (int) now()->year;
        $selectedYear = (int) $request->integer('year', $currentYear);
        $years = InternshipDocumentationPhoto::query()
            ->select('year')
            ->distinct()
            ->orderByDesc('year')
            ->pluck('year')
            ->prepend($currentYear)
            ->unique()
            ->values();

        return view('admin.documentation.index', [
            'photos' => InternshipDocumentationPhoto::query()
                ->with(['intern', 'uploadedBy'])
                ->where('year', $selectedYear)
                ->latest()
                ->get(),
            'years' => $years,
            'selectedYear' => $selectedYear,
            'currentYear' => $currentYear,
        ]);
    }
}
