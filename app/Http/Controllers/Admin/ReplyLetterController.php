<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UploadReplyLetterRequest;
use App\Models\Intern;
use App\Models\ReplyLetter;
use App\Services\Admin\ReplyLetterService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class ReplyLetterController extends Controller
{
    public function __construct(
        private readonly ReplyLetterService $replyLetterService,
    ) {}

    /**
     * Upload surat balasan (PDF) untuk mahasiswa yang sudah diterima.
     * Disimpan di disk 'public' folder reply-letters, bisa diunduh
     * mahasiswa lewat dashboard mereka (modul Raihan).
     */
    public function store(UploadReplyLetterRequest $request, Intern $intern): RedirectResponse
    {
        $this->authorize('uploadReplyLetter', $intern);

        $this->replyLetterService->upload(
            $intern,
            Auth::guard('admin')->user(),
            $request->file('file'),
        );

        return back()->with('success', 'Surat balasan berhasil diunggah.');
    }

    public function destroy(ReplyLetter $replyLetter): RedirectResponse
    {
        $this->authorize('update', $replyLetter->intern);

        $this->replyLetterService->delete($replyLetter);

        return back()->with('success', 'Surat balasan berhasil dihapus.');
    }
}
