<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Intern;
use App\Models\ReplyLetter;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class InternReplyLetterTest extends TestCase
{
    use RefreshDatabase;

    public function test_intern_can_view_and_download_their_reply_letter(): void
    {
        Storage::fake('public');
        $intern = Intern::factory()->create();
        $letter = ReplyLetter::create([
            'intern_id' => $intern->id,
            'uploaded_by' => Admin::factory()->create()->id,
            'file_path' => 'reply-letters/surat.pdf',
        ]);
        Storage::disk('public')->put($letter->file_path, 'PDF content');

        $this->actingAs($intern, 'intern')
            ->get(route('intern.reply-letters.index'))
            ->assertOk()
            ->assertSee('Surat Balasan Magang');

        $this->actingAs($intern, 'intern')
            ->get(route('intern.reply-letters.download', $letter))
            ->assertOk()
            ->assertDownload('surat-balasan.pdf');
    }

    public function test_intern_cannot_download_another_intern_reply_letter(): void
    {
        $intern = Intern::factory()->create();
        $letter = ReplyLetter::create([
            'intern_id' => Intern::factory()->create()->id,
            'uploaded_by' => Admin::factory()->create()->id,
            'file_path' => 'reply-letters/private.pdf',
        ]);

        $this->actingAs($intern, 'intern')
            ->get(route('intern.reply-letters.download', $letter))
            ->assertForbidden();
    }
}
