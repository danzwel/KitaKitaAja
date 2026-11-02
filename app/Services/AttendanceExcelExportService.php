<?php

namespace App\Services;

use App\Models\Intern;
use App\Models\AttendanceRecord;
use Carbon\CarbonInterface;
use Illuminate\Support\Collection;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Font;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AttendanceExcelExportService
{
    public function summary(Collection $summaryRows): StreamedResponse
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Rekap Kehadiran');

        $this->addTitle($sheet, 'Rekap Absensi Semua Peserta', 'N');
        $sheet->fromArray([['Peserta', 'Username', 'Institusi', 'Status', 'Periode Mulai', 'Periode Selesai', 'Hari Tercatat', 'Jam Kerja Tercatat', 'Hadir', 'Terlambat', 'Izin', 'Sakit', 'Alpa', 'Menunggu Verifikasi']], null, 'A4');

        $rows = $summaryRows->map(fn (array $row): array => [
            $row['intern']->name,
            $row['intern']->username,
            $row['intern']->university,
            ucfirst($row['intern']->status),
            $row['period_start'],
            $row['period_end'],
            $row['working_days'],
            $row['working_duration'],
            $row['present'],
            $row['late'],
            $row['izin'],
            $row['sakit'],
            $row['alpha'],
            $row['pending'],
        ])->all();

        if ($rows) {
            $sheet->fromArray($rows, null, 'A5');
        }

        $this->formatTable($sheet, 'A4:N'.max(5, 4 + count($rows)));

        return $this->download($spreadsheet, 'rekap-absensi-semua-peserta.xlsx');
    }

    public function intern(Intern $intern, Collection $attendanceHistory, Collection $leaveRequests, CarbonInterface $start, CarbonInterface $end): StreamedResponse
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Riwayat Kehadiran');

        $this->addTitle($sheet, 'Rekap Absensi Peserta', 'H');
        $sheet->fromArray([
            ['Nama Peserta', $intern->name],
            ['Username', $intern->username],
            ['Institusi', $intern->university],
            ['Periode Magang', $start->format('d M Y').' s/d '.$end->format('d M Y')],
        ], null, 'A3');
        $sheet->mergeCells('B3:H3');
        $sheet->mergeCells('B4:H4');
        $sheet->mergeCells('B5:H5');
        $sheet->mergeCells('B6:H6');
        $totalWorkingMinutes = $attendanceHistory->sum(fn (array $item): int => $item['record']?->working_minutes ?? 0);
        $sheet->fromArray([['Jam Kerja Tercatat', $this->durationLabel($totalWorkingMinutes)]], null, 'A7');
        $sheet->mergeCells('B7:H7');

        $sheet->fromArray([['Tanggal', 'Datang', 'Jarak Datang (m)', 'Pulang', 'Jarak Pulang (m)', 'Jam Kerja', 'Status Datang', 'Status Pulang']], null, 'A9');
        $rows = $attendanceHistory->map(function (array $item): array {
            $record = $item['record'];

            return [
                $item['date']->format('Y-m-d'),
                $record?->check_in_at?->format('H:i') ?? '-',
                $record?->check_in_distance_meters ?? '-',
                $record?->check_out_at?->format('H:i') ?? '-',
                $record?->check_out_distance_meters ?? '-',
                $record?->working_duration ?? '-',
                $this->statusLabel($record?->check_in_status ?? $item['status']),
                $this->statusLabel($record?->check_out_status ?? '-'),
            ];
        })->all();
        if ($rows) {
            $sheet->fromArray($rows, null, 'A10');
        }
        $this->formatTable($sheet, 'A9:H'.max(10, 9 + count($rows)));

        $leaveSheet = $spreadsheet->createSheet();
        $leaveSheet->setTitle('Pengajuan Izin');
        $this->addTitle($leaveSheet, 'Pengajuan Izin, Sakit, dan Tetap Hadir', 'H');
        $leaveSheet->fromArray([['Jenis', 'Tanggal Mulai', 'Tanggal Selesai', 'Jam Keperluan', 'Alasan', 'Status', 'Batas Persetujuan', 'Catatan Admin']], null, 'A4');
        $leaveRows = $leaveRequests->map(fn ($leave): array => [
            $leave->attendance_exception ? 'Izin Tetap Hadir' : ucfirst($leave->type),
            $leave->start_date->format('Y-m-d'),
            $leave->end_date->format('Y-m-d'),
            $leave->attendance_exception && $leave->start_time ? $leave->start_time.' - '.$leave->end_time : '-',
            $leave->reason,
            $this->statusLabel($leave->status),
            $leave->approval_deadline_at?->format('Y-m-d H:i') ?? '-',
            $leave->review_note ?? '-',
        ])->all();
        if ($leaveRows) {
            $leaveSheet->fromArray($leaveRows, null, 'A5');
        }
        $this->formatTable($leaveSheet, 'A4:H'.max(5, 4 + count($leaveRows)));

        return $this->download($spreadsheet, 'rekap-absensi-'.$intern->username.'.xlsx');
    }

    public function daily(Collection $records, CarbonInterface $date): StreamedResponse
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Absensi Harian');

        $this->addTitle($sheet, 'Absensi Peserta Magang', 'G');
        $sheet->mergeCells('A3:G3');
        $sheet->setCellValue('A3', 'Tanggal '. $date->translatedFormat('l, d F Y'));
        $sheet->getStyle('A3')->getFont()->setItalic(true)->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('FF64748B'));
        $sheet->fromArray([['Peserta', 'Username', 'Datang', 'Pulang', 'Status Datang', 'Status Pulang', 'Lokasi Datang']], null, 'A5');
        $rows = $records->map(fn (AttendanceRecord $record): array => [
            $record->intern->name,
            $record->intern->username,
            $record->check_in_at?->format('H:i') ?? '-',
            $record->check_out_at?->format('H:i') ?? '-',
            $this->statusLabel($record->check_in_status ?? '-'),
            $this->statusLabel($record->check_out_status ?? '-'),
            $record->check_in_distance_meters !== null ? $record->check_in_distance_meters.' m' : 'Belum ada',
        ])->all();
        if ($rows) {
            $sheet->fromArray($rows, null, 'A6');
        }
        $this->formatTable($sheet, 'A5:G'.max(6, 5 + count($rows)));

        return $this->download($spreadsheet, 'absensi-harian-'.$date->format('Y-m-d').'.xlsx');
    }

    private function addTitle($sheet, string $title, string $lastColumn): void
    {
        $sheet->mergeCells('A1:'.$lastColumn.'1');
        $sheet->setCellValue('A1', $title);
        $sheet->mergeCells('A2:'.$lastColumn.'2');
        $sheet->setCellValue('A2', 'Dibuat pada '.now()->format('d M Y H:i').' WIB');
        $sheet->getStyle('A1:'.$lastColumn.'1')->getFont()->setBold(true)->setSize(16)->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('FFFFFFFF'));
        $sheet->getStyle('A1:'.$lastColumn.'1')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FF0C2340');
        $sheet->getStyle('A1:'.$lastColumn.'2')->getFont()->setItalic(true)->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('FF64748B'));
        $sheet->getStyle('A1:'.$lastColumn.'2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getRowDimension(1)->setRowHeight(26);
    }

    private function formatTable($sheet, string $range): void
    {
        [$start, $end] = explode(':', $range);
        $headerRow = preg_replace('/\D/', '', $start);
        $sheet->getStyle($range)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN)->getColor()->setARGB('FFE2E8F0');
        $sheet->getStyle(preg_replace('/\d+/', '', $start).$headerRow.':'.preg_replace('/\d+/', '', $end).$headerRow)
            ->getFont()->setBold(true)->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('FFFFFFFF'));
        $sheet->getStyle(preg_replace('/\d+/', '', $start).$headerRow.':'.preg_replace('/\d+/', '', $end).$headerRow)
            ->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FF1D4ED8');
        $sheet->getStyle($range)->getAlignment()->setVertical(Alignment::VERTICAL_CENTER)->setWrapText(true);
        $sheet->freezePane(preg_replace('/\d+/', '', $start).($headerRow + 1));
        $sheet->setAutoFilter($range);

        foreach (range('A', preg_replace('/\d+/', '', $end)) as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }
    }

    private function download(Spreadsheet $spreadsheet, string $filename): StreamedResponse
    {
        return response()->streamDownload(function () use ($spreadsheet): void {
            (new Xlsx($spreadsheet))->save('php://output');
        }, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }

    private function statusLabel(string $status): string
    {
        return match ($status) {
            'tepat_waktu' => 'Tepat Waktu',
            'menunggu_verifikasi' => 'Menunggu Verifikasi',
            'menunggu_persetujuan_izin' => 'Menunggu Persetujuan Izin',
            'approved' => 'Disetujui',
            'rejected' => 'Ditolak',
            '-' => '-',
            default => ucfirst(str_replace('_', ' ', $status)),
        };
    }

    private function durationLabel(int $minutes): string
    {
        $hours = intdiv($minutes, 60);
        $remainingMinutes = $minutes % 60;

        return $remainingMinutes > 0 ? $hours.' jam '.$remainingMinutes.' menit' : $hours.' jam';
    }
}
