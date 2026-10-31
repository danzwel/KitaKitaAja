<?php

namespace App\Http\Controllers\Intern;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LeaveRequestController extends Controller
{
    public function index(Request $request): View
    {
        return view('intern.attendance.leave', [
            'leaveRequests' => $request->user('intern')->leaveRequests()->latest()->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'type' => ['required', 'in:izin,sakit,darurat'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            'attendance_exception' => ['nullable', 'boolean'],
            'start_time' => ['nullable', 'date_format:H:i', 'required_if:attendance_exception,1'],
            'end_time' => ['nullable', 'date_format:H:i', 'required_if:attendance_exception,1', 'after:start_time'],
            'reason' => ['required', 'string', 'min:10', 'max:2000'],
            'attachment' => ['nullable', 'file', 'max:5120', 'mimes:jpg,jpeg,png,pdf'],
        ]);

        $validated['attendance_exception'] = $request->boolean('attendance_exception');

        if ($validated['attendance_exception']) {
            if ($validated['type'] !== 'izin') {
                return back()->withInput()->withErrors(['type' => 'Izin tetap hadir hanya dapat diajukan dengan jenis izin.']);
            }

            if ($validated['start_date'] !== $validated['end_date']) {
                return back()->withInput()->withErrors(['end_date' => 'Izin tetap hadir berlaku untuk satu tanggal pengajuan.']);
            }

            $validated['approval_deadline_at'] = now()->addHours(72);
        } else {
            $validated['start_time'] = null;
            $validated['end_time'] = null;
        }

        if ($request->hasFile('attachment')) {
            $validated['attachment'] = $request->file('attachment')->store('leave-attachments', 'public');
        }

        $request->user('intern')->leaveRequests()->create($validated);

        $message = $validated['attendance_exception']
            ? 'Izin tetap hadir berhasil dikirim. Scan di luar jam normal diizinkan sementara selama maksimal 3×24 jam sambil menunggu keputusan admin.'
            : 'Pengajuan berhasil dikirim dan menunggu persetujuan admin.';

        return redirect()->route('intern.attendance.leave')->with('success', $message);
    }
}
