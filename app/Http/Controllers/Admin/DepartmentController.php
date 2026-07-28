<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreDepartmentRequest;
use App\Http\Requests\Admin\UpdateDepartmentRequest;
use App\Models\Department;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DepartmentController extends Controller
{
    public function index(Request $request): View
    {
        $this->authorize('viewAny', Department::class);

        $departments = Department::query()
            ->when($request->input('q'), fn ($q, $keyword) => $q->where('name', 'like', "%{$keyword}%"))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.departments.index', compact('departments'));
    }

    public function create(): View
    {
        $this->authorize('create', Department::class);

        return view('admin.departments.create');
    }

    public function store(StoreDepartmentRequest $request): RedirectResponse
    {
        Department::create($request->validated());

        return redirect()->route('admin.departments.index')
            ->with('success', 'Bidang magang berhasil ditambahkan.');
    }

    public function edit(Department $department): View
    {
        $this->authorize('update', $department);

        return view('admin.departments.edit', compact('department'));
    }

    public function update(UpdateDepartmentRequest $request, Department $department): RedirectResponse
    {
        $this->authorize('update', $department);

        $department->update($request->validated());

        return redirect()->route('admin.departments.index')
            ->with('success', 'Bidang magang berhasil diperbarui.');
    }

    public function destroy(Department $department): RedirectResponse
    {
        $this->authorize('delete', $department);

        if ($department->applications()->exists() || $department->interns()->exists()) {
            return back()->with('error', 'Bidang ini masih memiliki data pengajuan/mahasiswa terkait dan tidak dapat dihapus.');
        }

        $department->delete();

        return back()->with('success', 'Bidang magang berhasil dihapus.');
    }
}
