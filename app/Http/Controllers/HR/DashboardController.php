<?php

namespace App\Http\Controllers\HR;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\LeaveRequest;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Staff statistics
        $staffCount = User::whereIn('role', [User::ROLE_STAFF, User::ROLE_UPLOADER])->count();
        $activeStaff = User::whereIn('role', [User::ROLE_STAFF, User::ROLE_UPLOADER])
            ->where('is_active', true)
            ->count();

        // Leave requests pending approval
        $pendingLeaves = LeaveRequest::where('status', 'pending')->count();

        // Today's attendance stats
        $todayAttendances = Attendance::whereDate('date', today())->count();
        $todayLates = Attendance::whereDate('date', today())
            ->where('status', 'late')
            ->count();
        $todayAbsents = Attendance::whereDate('date', today())
            ->where('status', 'absent')
            ->count();

        // Recent leave requests
        $recentLeaves = LeaveRequest::with('user')
            ->latest()
            ->take(5)
            ->get();

        // Recent announcements
        $recentAnnouncements = \App\Models\HRAnnouncement::latest()->take(3)->get();

        return view('hr.dashboard', compact(
            'staffCount',
            'activeStaff',
            'pendingLeaves',
            'todayAttendances',
            'todayLates',
            'todayAbsents',
            'recentLeaves',
            'recentAnnouncements'
        ));
    }
}
