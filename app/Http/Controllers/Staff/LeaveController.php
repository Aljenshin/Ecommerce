<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\LeaveRequest;
use Illuminate\Http\Request;
use Carbon\Carbon;

class LeaveController extends Controller
{
    public function index(Request $request)
    {
        $query = LeaveRequest::where('user_id', auth()->id());

        if ($request->has('date') && $request->date) {
            $query->where(function($q) use ($request) {
                $q->whereDate('start_date', '<=', $request->date)
                  ->whereDate('end_date', '>=', $request->date);
            });
        }

        $leaveRequests = $query->latest()->paginate(15);

        return view('staff.leaves.index', compact('leaveRequests'));
    }

    public function create()
    {
        return view('staff.leaves.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'leave_type' => ['required', 'in:vacation,sick,emergency,personal'],
            'start_date' => ['required', 'date', 'after_or_equal:today'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            'reason' => ['required', 'string', 'max:1000'],
        ]);

        $startDate = Carbon::parse($request->start_date);
        $endDate = Carbon::parse($request->end_date);
        $daysRequested = $startDate->diffInDays($endDate) + 1;

        LeaveRequest::create([
            'user_id' => auth()->id(),
            'leave_type' => $request->leave_type,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'days_requested' => $daysRequested,
            'reason' => $request->reason,
            'status' => 'pending',
        ]);

        return redirect()->route('staff.leaves.index')
            ->with('success', 'Leave request submitted successfully.');
    }
}
