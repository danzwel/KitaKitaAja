<?php

namespace App\Http\Controllers\Admin;

use App\Exceptions\ApplicationAlreadyProcessedException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ApproveApplicationRequest;
use App\Http\Requests\Admin\RejectApplicationRequest;
use App\Models\InternshipApplication;
use App\Services\Admin\ApplicationApprovalService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ApplicationController extends Controller
{
    private const SORTABLE_COLUMNS = [
        'application_code',
        'nama',
        'nim',
        'universitas',
        'status',
        'created_at',
    ];

    public function __construct(
        private readonly ApplicationApprovalService $approvalService,
    ) {}

    /**
     * Halaman tabel data pengajuan: search, filter status, sorting, pagination.
     */
    public function index(Request $request): View
    {
        $sort = in_array($request->input('sort'), self::SORTABLE_COLUMNS, true)
            ? $request->input('sort')
            : 'created_at';
        $direction = $request->input('direction') === 'asc' ? 'asc' : 'desc';
        $statuses = ['menunggu_verifikasi', 'diproses', 'diterima', 'ditolak'];

        $applications = InternshipApplication::query()
            ->with(['bidang', 'document'])
            ->orderBy($sort, $direction)
            ->get();

        return view('admin.applications.index', compact('applications'));
    }

    /**
     * Detail pengajuan: identitas, data kampus, bidang, periode, status, dokumen.
     */
    public function show(InternshipApplication $application): View
    {
        $application->load(['bidang', 'document']);

        return view('admin.applications.show', compact('application'));
    }

    /**
     * Terima pengajuan -> generate akun mahasiswa otomatis -> simpan ke interns.
     */
    public function approve(ApproveApplicationRequest $request, InternshipApplication $application): RedirectResponse
    {
        try {
            $this->approvalService->approve($application, $request->validated('approval_note'));
        } catch (ApplicationAlreadyProcessedException $e) {
            return back()->with('error', $e->getMessage());
        }

        return back()->with('success', 'Pengajuan berhasil diterima.');
    }

    /**
     * Tolak pengajuan dengan alasan.
     */
    public function reject(RejectApplicationRequest $request, InternshipApplication $application): RedirectResponse
    {
        try {
            $this->approvalService->reject(
                $application,
                $request->validated('rejection_reason'),
            );
        } catch (ApplicationAlreadyProcessedException $e) {
            return back()->with('error', $e->getMessage());
        }

        return back()->with('success', 'Pengajuan berhasil ditolak.');
    }

}
