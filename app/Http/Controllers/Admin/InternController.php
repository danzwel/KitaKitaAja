<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateInternRequest;
use App\Models\Intern;
use App\Services\Admin\InternAccountService;
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
            ->paginate(10)
            ->withQueryString();

        return view('admin.interns.index', compact('interns'));
    }

    public function show(Intern $intern): View
    {
        $this->authorize('view', $intern);

        $intern->load('department', 'application', 'internshipApplication.bidang', 'replyLetters');

        return view('admin.interns.show', compact('intern'));
    }

    public function edit(Intern $intern): View
    {
        $this->authorize('update', $intern);

        $intern->load('department');

        return view('admin.interns.edit', compact('intern'));
    }

    public function update(UpdateInternRequest $request, Intern $intern): RedirectResponse
    {
        $this->authorize('update', $intern);

        $intern->update($request->validated());

        return redirect()->route('admin.interns.index')
            ->with('success', 'Data mahasiswa magang berhasil diperbarui.');
    }

    public function destroy(Intern $intern): RedirectResponse
    {
        $this->authorize('delete', $intern);

        $intern->delete();

        return back()->with('success', 'Data mahasiswa magang berhasil dihapus.');
    }

    /**
     * Reset password mahasiswa magang ke password random baru.
     */
    public function resetPassword(Intern $intern): RedirectResponse
    {
        $this->authorize('resetPassword', $intern);

        $newPassword = $this->accountService->resetPassword($intern);

        // TODO: kirim password baru via notifikasi ke mahasiswa, bukan session flash.
        return back()->with('success', "Password berhasil direset. Password baru: {$newPassword}");
    }
}
