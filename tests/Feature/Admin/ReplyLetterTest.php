<?php

namespace Tests\Feature\Admin;

use App\Models\Admin;
use App\Models\Application;
use App\Models\Intern;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ReplyLetterTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_upload_reply_letter_for_accepted_intern(): void
    {
        Storage::fake('public');
        $admin = Admin::factory()->create();
        $application = Application::factory()->diterima()->create();
        $intern = Intern::factory()->create([
            'application_id' => $application->id,
            'department_id' => $application->department_id,
        ]);
        $file = UploadedFile::fake()->create('surat-balasan.pdf', 100, 'application/pdf');

        $this->actingAs($admin, 'admin')
            ->post(route('admin.reply-letters.store', $intern), ['file' => $file])
            ->assertRedirect()
            ->assertSessionHas('success');

        $this->assertDatabaseCount('reply_letters', 1);
        Storage::disk('public')->assertExists('reply-letters/'.$file->hashName());
    }

    public function test_admin_cannot_upload_reply_letter_for_unaccepted_intern(): void
    {
        Storage::fake('public');
        $admin = Admin::factory()->create();
        $intern = Intern::factory()->create();
        $file = UploadedFile::fake()->create('surat-balasan.pdf', 100, 'application/pdf');

        $this->actingAs($admin, 'admin')
            ->post(route('admin.reply-letters.store', $intern), ['file' => $file])
            ->assertForbidden();

        $this->assertDatabaseCount('reply_letters', 0);
    }
}
