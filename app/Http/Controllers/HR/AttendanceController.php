<?php

namespace App\Http\Controllers\HR;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\User;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        $query = Attendance::with('user');

        if ($request->has('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->has('date')) {
            $query->whereDate('date', $request->date);
        }

        $attendances = $query->latest('date')->paginate(20);
        $staff = User::whereIn('role', [User::ROLE_STAFF, User::ROLE_UPLOADER])->get();

        return view('hr.attendance.index', compact('attendances', 'staff'));
    }

    public function edit(Attendance $attendance)
    {
        return view('hr.attendance.edit', compact('attendance'));
    }

    public function update(Request $request, Attendance $attendance)
    {
        $request->validate([
            'time_in' => ['nullable', 'date_format:H:i'],
            'time_out' => ['nullable', 'date_format:H:i'],
            'status' => ['required', 'in:present,late,absent,undertime,overtime'],
            'notes' => ['nullable', 'string'],
        ]);

        $attendance->update($request->all());

        return redirect()->route('hr.attendance.index')
            ->with('success', 'Attendance record updated.');
    }
}
