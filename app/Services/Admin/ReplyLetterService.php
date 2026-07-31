<?php

namespace App\Services\Admin;

use App\Models\Admin;
use App\Models\Intern;
use App\Models\ReplyLetter;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class ReplyLetterService
{
    public function upload(Intern $intern, Admin $admin, UploadedFile $file): ReplyLetter
    {
        $path = $file->store('reply-letters', 'public');

        try {
            return ReplyLetter::create([
                'intern_id' => $intern->id,
                'uploaded_by' => $admin->id,
                'file_path' => $path,
            ]);
        } catch (\Throwable $exception) {
            Storage::disk('public')->delete($path);

            throw $exception;
        }
    }

    public function delete(ReplyLetter $replyLetter): void
    {
        Storage::disk('public')->delete($replyLetter->file_path);
        $replyLetter->delete();
    }
}
