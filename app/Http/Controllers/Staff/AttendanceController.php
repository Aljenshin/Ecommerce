<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        $query = Attendance::where('user_id', auth()->id());

        if ($request->has('date') && $request->date) {
            $query->whereDate('date', $request->date);
        }

        $attendances = $query->latest('date')->paginate(20);

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

        // Parse time_in properly
        // time_in is stored as H:i:s string, extract just the time part
        $timeInString = (string) $attendance->time_in;
        
        // If it contains a date (has spaces or is longer than 8 chars), extract time only
        if (strpos($timeInString, ' ') !== false) {
            $parts = explode(' ', $timeInString);
            $timeInString = end($parts); // Get the time part
        }
        
        // Ensure we have a valid time format (H:i:s)
        if (!preg_match('/^\d{1,2}:\d{2}:\d{2}$/', $timeInString)) {
            // Try to parse as datetime and extract time
            try {
                $parsed = Carbon::parse($attendance->time_in);
                $timeInString = $parsed->format('H:i:s');
            } catch (\Exception $e) {
                return back()->with('error', 'Invalid time in record. Please contact HR.');
            }
        }
        
        // Create datetime by combining date and time
        $timeIn = Carbon::createFromFormat('Y-m-d H:i:s', $attendance->date->format('Y-m-d') . ' ' . $timeInString);
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
