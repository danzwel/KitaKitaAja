<?php

namespace App\Console\Commands;

use App\Models\Application;
use App\Models\ApplicationDocument;
use App\Models\Intern;
use App\Models\InternshipApplication;
use App\Models\ReplyLetter;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

class PurgeParticipantData extends Command
{
    protected $signature = 'app:purge-participant-data {--force : Jalankan tanpa meminta konfirmasi}';

    protected $description = 'Hapus data peserta magang dan turunannya tanpa menghapus admin atau bidang magang';

    public function handle(): int
    {
        if (! $this->option('force') && ! $this->confirm(
            'Hapus SEMUA data peserta, pengajuan, absensi, izin, dan dokumen peserta dari database ini?'
        )) {
            $this->info('Dibatalkan. Tidak ada data yang diubah.');

            return self::SUCCESS;
        }

        $publicFiles = $this->participantFilePaths();

        DB::transaction(function (): void {
            // Hapus tabel anak lebih dahulu agar aman terhadap foreign key.
            foreach ([
                'attendance_records',
                'leave_requests',
                'reply_letters',
                'internship_certificates',
                'internship_documentation_photos',
                'application_documents',
                'interns',
                'applications',
                'internship_applications',
            ] as $table) {
                if (Schema::hasTable($table)) {
                    DB::table($table)->delete();
                }
            }
        });

        $deletedFiles = 0;
        foreach ($publicFiles as $path) {
            if (Storage::disk('public')->delete($path)) {
                $deletedFiles++;
            }
        }

        foreach ([
            'documents',
            'intern-photos',
            'internship-final-reports',
            'internship-documentation',
            'leave-attachments',
            'reply-letters',
        ] as $directory) {
            Storage::disk('public')->deleteDirectory($directory);
        }

        $this->info('Data peserta dan file turunannya berhasil dibersihkan.');
        $this->line("File peserta yang dihapus: {$deletedFiles}");
        $this->line('Akun admin dan data bidang magang tetap dipertahankan.');

        return self::SUCCESS;
    }

    /** @return array<int, string> */
    private function participantFilePaths(): array
    {
        $paths = [];

        if (Schema::hasTable('interns')) {
            $paths = array_merge($paths, Intern::withTrashed()
                ->get(['photo', 'final_report_path'])
                ->flatMap(fn (Intern $intern): array => [$intern->photo, $intern->final_report_path])
                ->all());
        }

        if (Schema::hasTable('reply_letters')) {
            $paths = array_merge($paths, ReplyLetter::query()->pluck('file_path')->all());
        }

        if (Schema::hasTable('application_documents')) {
            $paths = array_merge($paths, ApplicationDocument::query()
                ->get(['surat_pengantar', 'foto', 'cv', 'transkrip_nilai', 'proposal', 'portofolio'])
                ->flatMap(fn (ApplicationDocument $document): array => [
                    $document->surat_pengantar, $document->foto, $document->cv,
                    $document->transkrip_nilai, $document->proposal, $document->portofolio,
                ])
                ->all());
        }

        if (Schema::hasTable('applications')) {
            $paths = array_merge($paths, Application::withTrashed()
                ->get(['cover_letter_path', 'cv_path', 'proposal_path'])
                ->flatMap(fn (Application $application): array => [
                    $application->cover_letter_path, $application->cv_path, $application->proposal_path,
                ])
                ->all());
        }

        return array_values(array_unique(array_filter($paths)));
    }
}
