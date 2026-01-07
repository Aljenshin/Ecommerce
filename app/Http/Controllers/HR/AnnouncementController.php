<?php

namespace App\Http\Controllers\HR;

use App\Http\Controllers\Controller;
use App\Models\HRAnnouncement;
use Illuminate\Http\Request;

class AnnouncementController extends Controller
{
    public function index()
    {
        $announcements = HRAnnouncement::with('creator')
            ->latest()
            ->paginate(15);

        return view('hr.announcements.index', compact('announcements'));
    }

    public function create()
    {
        return view('hr.announcements.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'message' => ['required', 'string'],
            'type' => ['required', 'in:general,schedule,shift_change,notice'],
            'audience' => ['required', 'in:all,department'],
            'target_department' => ['nullable', 'string', 'max:255'],
            'is_urgent' => ['boolean'],
            'effective_date' => ['nullable', 'date'],
        ]);

        HRAnnouncement::create([
            'created_by' => auth()->id(),
            'title' => $request->title,
            'message' => $request->message,
            'type' => $request->type,
            'audience' => $request->audience,
            'target_department' => $request->target_department,
            'is_urgent' => $request->boolean('is_urgent'),
            'effective_date' => $request->effective_date,
        ]);

        return redirect()->route('hr.announcements.index')
            ->with('success', 'Announcement created successfully.');
    }

    public function destroy(HRAnnouncement $announcement)
    {
        $announcement->delete();

        return redirect()->route('hr.announcements.index')
            ->with('success', 'Announcement deleted successfully.');
    }
}
