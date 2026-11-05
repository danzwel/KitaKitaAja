<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateInternRequest;
use App\Models\Bidang;
use App\Models\Intern;
use App\Services\Admin\InternAccountService;
use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class InternController extends Controller
{
    public function __construct(
        private readonly InternAccountService $accountService,
    ) {}

    public function index(Request $request): View
    {
        $this->authorize('viewAny', Intern::class);

        $interns = Intern::query()
            ->with(['department', 'internshipApplication.bidang'])
            ->search($request->input('q'))
            ->status($request->input('status'))
            ->latest()
            ->get();

        return view('admin.interns.index', compact('interns'));
    }

    public function show(Intern $intern): View
    {
        $this->authorize('view', $intern);

        $intern->load('department', 'application', 'internshipApplication.bidang', 'replyLetters', 'certificate');

        return view('admin.interns.show', compact('intern'));
    }

    public function edit(Intern $intern): View
    {
        $this->authorize('update', $intern);

        $intern->load('internshipApplication.bidang');

        $selectedBidangId = $intern->internshipApplication?->bidang_id;
        $bidangs = Bidang::query()
            ->where(function ($query) use ($selectedBidangId): void {
                $query->where('is_active', true);

                if ($selectedBidangId) {
                    $query->orWhere('id', $selectedBidangId);
                }
            })
            ->orderBy('nama_bidang')
            ->get();

        return view('admin.interns.edit', compact('intern', 'bidangs'));
    }

    public function update(UpdateInternRequest $request, Intern $intern): RedirectResponse
    {
        $this->authorize('update', $intern);

        DB::transaction(function () use ($request, $intern): void {
            $data = $request->validated();
            $periodStart = Carbon::parse($data['periode_mulai']);
            $periodEnd = Carbon::parse($data['periode_selesai']);

            $intern->update([
                ...Arr::only($data, ['name', 'university']),
                'period' => $periodStart->format('d M Y').' - '.$periodEnd->format('d M Y'),
                // Jika tanggal lama diperbaiki ke tanggal yang belum berakhir,
                // akses peserta aktif kembali secara otomatis.
                'status' => $periodEnd->lt(now()->startOfDay())
                    ? Intern::STATUS_SELESAI
                    : Intern::STATUS_AKTIF,
            ]);
            $intern->internshipApplication()->update([
                'nama' => $data['name'],
                'universitas' => $data['university'],
                'bidang_id' => $data['bidang_id'],
                'periode_mulai' => $periodStart->toDateString(),
                'periode_selesai' => $periodEnd->toDateString(),
            ]);
        });

        return redirect()->route('admin.interns.index')
            ->with('success', 'Data peserta magang berhasil diperbarui.');
    }

    public function destroy(Intern $intern): RedirectResponse
    {
        $this->authorize('delete', $intern);

        $intern->delete();

        return back()->with('success', 'Data peserta magang berhasil dihapus.');
    }

    /**
     * Reset password peserta magang ke password random baru.
     */
    public function resetPassword(Intern $intern): RedirectResponse
    {
        $this->authorize('resetPassword', $intern);

        $newPassword = $this->accountService->resetPassword($intern);

        // TODO: kirim password baru via notifikasi ke peserta, bukan session flash.
        return back()->with('success', "Password berhasil direset. Password baru: {$newPassword}");
    }
}
