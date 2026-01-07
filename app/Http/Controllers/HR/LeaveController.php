<?php

namespace App\Http\Controllers\HR;

use App\Http\Controllers\Controller;
use App\Models\LeaveRequest;
use Illuminate\Http\Request;

class LeaveController extends Controller
{
    public function index()
    {
        $leaveRequests = LeaveRequest::with(['user', 'approver'])
            ->latest()
            ->paginate(15);

        $pendingLeaves = LeaveRequest::where('status', 'pending')
            ->with('user')
            ->latest()
            ->get();

        return view('hr.leaves.index', compact('leaveRequests', 'pendingLeaves'));
    }

    public function approve(LeaveRequest $leaveRequest)
    {
        $leaveRequest->update([
            'status' => 'approved',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
        ]);

        return back()->with('success', 'Leave request approved.');
    }

    public function reject(Request $request, LeaveRequest $leaveRequest)
    {
        $request->validate([
            'remarks' => ['required', 'string'],
        ]);

        $leaveRequest->update([
            'status' => 'rejected',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
            'remarks' => $request->remarks,
        ]);

        return back()->with('success', 'Leave request rejected.');
    }
}
