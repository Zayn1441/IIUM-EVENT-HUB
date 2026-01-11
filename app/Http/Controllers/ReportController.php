<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\Report;

class ReportController extends Controller
{
    public function store(Request $request, Event $event)
    {
        $request->validate([
            'reason' => 'required|string|max:1000',
        ]);

        Report::create([
            'user_id' => auth()->id(),
            'event_id' => $event->id,
            'reason' => $request->reason,
        ]);

        return back()->with('success', 'Event reported successfully.');
    }

    public function index()
    {
        // Ensure user is admin
        if (!auth()->user()->is_admin) {
            abort(403);
        }

        $reports = Report::with(['user', 'event'])->latest()->paginate(20);
        return view('admin.reports.index', compact('reports'));
    }

    public function destroy(Report $report)
    {
        if (!auth()->user()->is_admin) {
            abort(403);
        }

        $report->delete(); // This acts as "Ignore"
        return back()->with('success', 'Report ignored/deleted.');
    }

    public function sendMessage(Request $request, Event $event)
    {
        if (!auth()->user()->is_admin) {
            abort(403);
        }

        $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        \App\Models\Notice::create([
            'user_id' => $event->user_id,
            'type' => 'admin_message',
            'title' => 'Message from Admin',
            'message' => $request->message . "\n\nRegarding your event: " . $event->title,
            'action_url' => route('events.show', $event),
        ]);

        return back()->with('success', 'Message sent to event organizer.');
    }
}
