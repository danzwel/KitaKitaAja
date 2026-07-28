<?php

namespace App\Http\Controllers\Admin;

use App\Exceptions\ApplicationAlreadyProcessedException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\RejectApplicationRequest;
use App\Models\Application;
use App\Services\Admin\ApplicationApprovalService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ApplicationController extends Controller
{
    public function __construct(
        private readonly ApplicationApprovalService $approvalService,
    ) {}

    /**
     * Halaman tabel data pengajuan: search, filter status, sorting, pagination.
     */
    public function index(Request $request): View
    {
        $this->authorize('viewAny', Application::class);

        $applications = Application::query()
            ->with('department')
            ->search($request->input('q'))
            ->status($request->input('status'))
            ->orderBy(
                $request->input('sort', 'application_date'),
                $request->input('direction', 'desc')
            )
            ->paginate(10)
            ->withQueryString();

        return view('admin.applications.index', compact('applications'));
    }

    /**
     * Detail pengajuan: identitas, data kampus, bidang, periode, status, dokumen.
     */
    public function show(Application $application): View
    {
        $this->authorize('view', $application);

        $application->load('department', 'processedBy', 'intern');

        return view('admin.applications.show', compact('application'));
    }

    /**
     * Terima pengajuan -> generate akun mahasiswa otomatis -> simpan ke interns.
     */
    public function approve(Application $application): RedirectResponse
    {
        $this->authorize('process', $application);

        try {
            $this->approvalService->approve($application, Auth::guard('admin')->user());
        } catch (ApplicationAlreadyProcessedException $e) {
            return back()->with('error', $e->getMessage());
        }

        // TODO: kirim notifikasi/email berisi username & password ke mahasiswa
        // via Notification, bukan ditampilkan/dicatat langsung di controller.

        return back()->with('success', 'Pengajuan diterima. Akun mahasiswa berhasil dibuat.');
    }

    /**
     * Tolak pengajuan dengan alasan.
     */
    public function reject(RejectApplicationRequest $request, Application $application): RedirectResponse
    {
        try {
            $this->approvalService->reject(
                $application,
                Auth::guard('admin')->user(),
                $request->validated('rejection_reason'),
            );
        } catch (ApplicationAlreadyProcessedException $e) {
            return back()->with('error', $e->getMessage());
        }

        return back()->with('success', 'Pengajuan berhasil ditolak.');
    }
}
