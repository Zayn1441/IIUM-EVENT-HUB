<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notice;

class NoticeController extends Controller
{
    public function index()
    {
        $notices = auth()->user()->notices()->latest()->paginate(50);

        // Mark as read when viewing (optional, or by click)
        // For now, we'll mark them as read when the user visits the index page
        auth()->user()->notices()->where('is_read', false)->update(['is_read' => true]);

        return view('notices.index', compact('notices'));
    }

    public function clearAll()
    {
        auth()->user()->notices()->delete();
        return back()->with('success', 'All notices cleared.');
    }
}
