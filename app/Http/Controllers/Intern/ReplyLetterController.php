<?php

namespace App\Http\Controllers\Intern;

use App\Http\Controllers\Controller;
use App\Models\ReplyLetter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ReplyLetterController extends Controller
{
    public function index(Request $request): View
    {
        return view('intern.reply-letters.index', [
            'letters' => $request->user('intern')->replyLetters()->latest()->get(),
        ]);
    }

    public function download(Request $request, ReplyLetter $replyLetter)
    {
        abort_unless($replyLetter->intern_id === $request->user('intern')->id, 403);
        abort_unless(Storage::disk('public')->exists($replyLetter->file_path), 404);

        return Storage::disk('public')->download($replyLetter->file_path, 'surat-balasan.pdf');
    }
}
