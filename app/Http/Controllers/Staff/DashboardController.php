<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\HRAnnouncement;
use App\Models\LeaveRequest;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // Today's attendance
        $todayAttendance = Attendance::where('user_id', $user->id)
            ->whereDate('date', today())
            ->first();

        // Recent announcements
        $announcements = HRAnnouncement::where(function($query) {
                $query->where('audience', 'all')
                    ->orWhere('target_department', auth()->user()->employee->department ?? '');
            })
            ->latest()
            ->take(5)
            ->get();

        // Leave requests status
        $leaveRequests = LeaveRequest::where('user_id', $user->id)
            ->latest()
            ->take(5)
            ->get();

        $pendingLeaves = LeaveRequest::where('user_id', $user->id)
            ->where('status', 'pending')
            ->count();

        return view('staff.dashboard', compact(
            'todayAttendance',
            'announcements',
            'leaveRequests',
            'pendingLeaves'
        ));
    }
}
