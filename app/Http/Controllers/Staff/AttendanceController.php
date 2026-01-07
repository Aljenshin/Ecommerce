<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    public function index()
    {
        $attendances = Attendance::where('user_id', auth()->id())
            ->latest('date')
            ->paginate(20);

        return view('staff.attendance.index', compact('attendances'));
    }

    public function timeIn()
    {
        $today = today();
        $now = now();

        // Check if already timed in today
        $existing = Attendance::where('user_id', auth()->id())
            ->whereDate('date', $today)
            ->first();

        if ($existing && $existing->time_in) {
            return back()->with('error', 'You have already timed in today.');
        }

        // Check if late (assuming work starts at 9:00 AM)
        $workStart = Carbon::parse($today->format('Y-m-d') . ' 09:00:00');
        $isLate = $now->gt($workStart);
        $minutesLate = $isLate ? $now->diffInMinutes($workStart) : 0;

        Attendance::updateOrCreate(
            [
                'user_id' => auth()->id(),
                'date' => $today,
            ],
            [
                'time_in' => $now->format('H:i:s'),
                'status' => $isLate ? 'late' : 'present',
                'minutes_late' => $minutesLate,
            ]
        );

        return back()->with('success', 'Time in recorded successfully.');
    }

    public function timeOut()
    {
        $today = today();
        $now = now();

        $attendance = Attendance::where('user_id', auth()->id())
            ->whereDate('date', $today)
            ->first();

        if (!$attendance || !$attendance->time_in) {
            return back()->with('error', 'Please time in first.');
        }

        if ($attendance->time_out) {
            return back()->with('error', 'You have already timed out today.');
        }

        $timeIn = Carbon::parse($attendance->date->format('Y-m-d') . ' ' . $attendance->time_in);
        $timeOut = $now;
        $totalMinutes = $timeOut->diffInMinutes($timeIn);
        $totalHours = round($totalMinutes / 60, 2);

        // Check for undertime (assuming 8 hours = 480 minutes)
        $expectedMinutes = 480;
        $minutesUndertime = max(0, $expectedMinutes - $totalMinutes);
        $minutesOvertime = max(0, $totalMinutes - $expectedMinutes);

        $status = 'present';
        if ($minutesUndertime > 0 && $totalMinutes < $expectedMinutes) {
            $status = 'undertime';
        } elseif ($minutesOvertime > 0) {
            $status = 'overtime';
        }

        $attendance->update([
            'time_out' => $now->format('H:i:s'),
            'status' => $status,
            'minutes_undertime' => $minutesUndertime,
            'minutes_overtime' => $minutesOvertime,
            'total_hours' => $totalHours,
        ]);

        return back()->with('success', 'Time out recorded successfully.');
    }
}
