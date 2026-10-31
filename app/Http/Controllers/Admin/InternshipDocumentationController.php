<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\InternshipDocumentationPhoto;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
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
                ->with('uploadedBy')
                ->where('year', $selectedYear)
                ->latest()
                ->get(),
            'years' => $years,
            'selectedYear' => $selectedYear,
            'currentYear' => $currentYear,
            'maximumPhotos' => InternshipDocumentationPhoto::MAXIMUM_PHOTOS_PER_YEAR,
            'currentYearPhotoCount' => InternshipDocumentationPhoto::where('year', $currentYear)->count(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'photo' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'caption' => ['nullable', 'string', 'max:255'],
        ]);

        $year = (int) now()->year;
        $path = null;

        try {
            DB::transaction(function () use ($request, $validated, $year, &$path): void {
                $count = InternshipDocumentationPhoto::query()
                    ->where('year', $year)
                    ->lockForUpdate()
                    ->count();

                if ($count >= InternshipDocumentationPhoto::MAXIMUM_PHOTOS_PER_YEAR) {
                    abort(422, 'Kuota dokumentasi tahun '.$year.' sudah mencapai 10 foto.');
                }

                $path = $request->file('photo')->store('internship-documentation/'.$year, 'public');
                InternshipDocumentationPhoto::create([
                    'year' => $year,
                    'uploaded_by' => $request->user('admin')->id,
                    'image_path' => $path,
                    'caption' => $validated['caption'] ?? null,
                ]);
            });
        } catch (\Throwable $exception) {
            if ($path) {
                Storage::disk('public')->delete($path);
            }

            if ($exception instanceof \Symfony\Component\HttpKernel\Exception\HttpException) {
                return back()->withInput()->with('error', $exception->getMessage());
            }

            throw $exception;
        }

        return redirect()->route('admin.documentation.index', ['year' => $year])
            ->with('success', 'Foto dokumentasi berhasil diunggah.');
    }

    public function destroy(InternshipDocumentationPhoto $documentation): RedirectResponse
    {
        Storage::disk('public')->delete($documentation->image_path);
        $documentation->delete();

        return back()->with('success', 'Foto dokumentasi berhasil dihapus.');
    }
}
