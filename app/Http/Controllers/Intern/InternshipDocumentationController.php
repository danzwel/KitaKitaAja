<?php

namespace App\Http\Controllers\Intern;

use App\Http\Controllers\Controller;
use App\Models\Intern;
use App\Models\InternshipDocumentationPhoto;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class InternshipDocumentationController extends Controller
{
    public function index(Request $request): View|RedirectResponse
    {
        $intern = $request->user('intern');

        if ($intern->status !== Intern::STATUS_SELESAI) {
            return redirect()->route('intern.dashboard')
                ->with('error', 'Dokumentasi hanya dapat diunggah setelah masa magang selesai.');
        }

        return view('intern.documentation.index', [
            'intern' => $intern,
            'photos' => $intern->documentationPhotos()->latest()->get(),
            'maximumPhotos' => InternshipDocumentationPhoto::MAXIMUM_PHOTOS_PER_INTERN,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'photos' => ['required', 'array', 'min:1'],
            'photos.*' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'caption' => ['nullable', 'string', 'max:255'],
        ]);

        /** @var Intern $authenticatedIntern */
        $authenticatedIntern = $request->user('intern');
        if ($authenticatedIntern->status !== Intern::STATUS_SELESAI) {
            return redirect()->route('intern.dashboard')
                ->with('error', 'Dokumentasi hanya dapat diunggah setelah masa magang selesai.');
        }

        $paths = [];

        try {
            DB::transaction(function () use ($request, $authenticatedIntern, &$paths): void {
                $intern = Intern::query()->lockForUpdate()->findOrFail($authenticatedIntern->id);

                if ($intern->status !== Intern::STATUS_SELESAI) {
                    abort(422, 'Dokumentasi hanya dapat diunggah setelah masa magang selesai.');
                }

                $photos = $request->file('photos');
                $existingPhotoCount = $intern->documentationPhotos()->lockForUpdate()->count();
                $remainingQuota = InternshipDocumentationPhoto::MAXIMUM_PHOTOS_PER_INTERN - $existingPhotoCount;

                if (count($photos) > $remainingQuota) {
                    abort(422, 'Anda hanya dapat mengunggah '.$remainingQuota.' foto lagi. Batas dokumentasi adalah 5 foto per mahasiswa.');
                }

                foreach ($photos as $photo) {
                    $path = $photo->store('internship-documentation/'.now()->year.'/'.$intern->id, 'public');
                    $paths[] = $path;

                    InternshipDocumentationPhoto::create([
                        'year' => (int) now()->year,
                        'intern_id' => $intern->id,
                        'image_path' => $path,
                        'caption' => $request->string('caption')->trim()->toString() ?: null,
                    ]);
                }
            });
        } catch (\Throwable $exception) {
            if ($paths) {
                Storage::disk('public')->delete($paths);
            }

            if ($exception instanceof \Symfony\Component\HttpKernel\Exception\HttpException) {
                return back()->withInput()->with('error', $exception->getMessage());
            }

            throw $exception;
        }

        return redirect()->route('intern.documentation.index')
            ->with('status', count($paths).' foto dokumentasi berhasil diunggah.');
    }

    public function destroy(Request $request, InternshipDocumentationPhoto $photo): RedirectResponse
    {
        /** @var Intern $intern */
        $intern = $request->user('intern');

        abort_unless($photo->intern_id === $intern->id, 403);

        Storage::disk('public')->delete($photo->image_path);
        $photo->delete();

        return redirect()->route('intern.documentation.index')
            ->with('status', 'Foto dokumentasi berhasil dihapus. Anda dapat mengunggah foto pengganti.');
    }
}
