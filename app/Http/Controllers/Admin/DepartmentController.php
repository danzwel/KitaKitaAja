<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreDepartmentRequest;
use App\Http\Requests\Admin\UpdateDepartmentRequest;
use App\Models\Bidang;
use App\Models\InternshipApplication;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DepartmentController extends Controller
{
    public function index(Request $request): View
    {
        $bidangs = Bidang::query()
            ->when($request->filled('q'), fn ($query) => $query->where('nama_bidang', 'like', '%'.$request->input('q').'%'))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.departments.index', compact('bidangs'));
    }

    public function create(): View
    {
        return view('admin.departments.create');
    }

    public function store(StoreDepartmentRequest $request): RedirectResponse
    {
        Bidang::create($request->validated());

        return redirect()->route('admin.departments.index')
            ->with('success', 'Bidang magang berhasil ditambahkan.');
    }

    public function edit(Bidang $department): View
    {
        return view('admin.departments.edit', compact('department'));
    }

    public function update(UpdateDepartmentRequest $request, Bidang $department): RedirectResponse
    {
        $department->update($request->validated());

        return redirect()->route('admin.departments.index')
            ->with('success', 'Bidang magang berhasil diperbarui.');
    }

    public function destroy(Bidang $department): RedirectResponse
    {
        if (InternshipApplication::where('bidang_id', $department->id)->exists()) {
            return back()->with('error', 'Bidang ini masih digunakan oleh pengajuan dan tidak dapat dihapus.');
        }

        $department->delete();

        return back()->with('success', 'Bidang magang berhasil dihapus.');
    }
}
