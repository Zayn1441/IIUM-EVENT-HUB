<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Event::query();

        // Search logic
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                    ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }

        // Category Filter (Multi-select)
        if ($request->filled('category')) {
            $categories = is_array($request->category) ? $request->category : explode(',', $request->category);
            // Remove 'All' if present or handle empty
            $categories = array_diff($categories, ['All']);
            if (!empty($categories)) {
                $query->whereIn('category', $categories);
            }
        }

        // Starpoints Filter
        if ($request->boolean('starpoints')) { // changed to boolean() to be safe
            $query->where('is_starpoints', true);
        }

        // Tags Filter (Multi-select)
        if ($request->filled('tags')) {
            $tags = is_array($request->tags) ? $request->tags : explode(',', $request->tags);
            $query->whereHas('tags', function ($q) use ($tags) {
                $q->whereIn('name', $tags);
            });
        }

        // Kulliyyah/Organizer Filter (Multi-select)
        if ($request->filled('organizer')) {
            $organizers = is_array($request->organizer) ? $request->organizer : explode(',', $request->organizer);
            $query->whereIn('organizer', $organizers);
        }

        // Sort logic
        $sort = $request->query('sort', 'default');

        switch ($sort) {
            case 'latest':
                $query->orderBy('created_at', 'desc');
                break;
            case 'oldest':
                $query->orderBy('created_at', 'asc');
                break;
            case 'date_desc': // Furthest future First
                $query->orderBy('date', 'desc')->orderBy('time', 'desc');
                break;
            case 'date_asc': // Earliest First (Global, includes past)
                $query->orderBy('date', 'asc')->orderBy('time', 'asc');
                break;
            case 'title_asc':
                $query->orderBy('title', 'asc');
                break;
            case 'title_desc':
                $query->orderBy('title', 'desc');
                break;
            default: // Upcoming (Smart Sort)
                $query->orderByRaw("CASE WHEN CONCAT(date, ' ', time) >= NOW() THEN 0 ELSE 1 END")
                    ->orderBy('date', 'asc')
                    ->orderBy('time', 'asc');
                break;
        }

        $events = $query->withCount('registrations')->paginate(12)->withQueryString();

        return view('events.index', compact('events'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('events.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|in:Academic,Cultural,Sports,Religious,Social,Workshop,Market,Community',
            'description' => 'required|string',
            'date' => 'required|date',
            'time' => 'required',
            'location' => 'required|string|max:255',
            'organizer' => 'required|string|max:255',
            'is_starpoints' => 'boolean',
            'image' => 'nullable|image|max:2048', // Max 2MB
            'participation_link' => 'nullable|url',
            'tags' => 'nullable|string', // Comma separated tags for now, or JSON
        ]);

        // Handle File Upload
        $imagePath = null;
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('events', 'public');
            $imagePath = \Illuminate\Support\Facades\Storage::url($path);
        }

        $event = Event::create([
            'user_id' => auth()->id(),
            'title' => $validated['title'],
            'category' => $validated['category'],
            'description' => $validated['description'],
            'date' => $validated['date'],
            'time' => $validated['time'],
            'location' => $validated['location'],
            'organizer' => $validated['organizer'],
            'is_starpoints' => $request->boolean('is_starpoints'),
            'participation_link' => $validated['participation_link'],
            'image_path' => $imagePath,
        ]);

        // Handle Tags (Simple implementation: split by comma if string, or handle array)
        // Ideally we'd use the 'tags' relationship. For now, let's create tags if they don't exist.
        if ($request->filled('tags')) {
            $tagNames = array_map('trim', explode(',', $request->tags));
            $kulliyyahTags = ['AHAS KIRKHS', 'AIKOL', 'KAED', 'KENMS', 'KOED', 'KOE', 'KICT'];

            foreach ($tagNames as $tagName) {
                if (!empty($tagName)) {
                    $type = in_array($tagName, $kulliyyahTags) ? 'Kulliyyah' : 'Activity';
                    $tag = \App\Models\Tag::firstOrCreate(
                        ['name' => $tagName],
                        ['type' => $type]
                    );
                    $event->tags()->attach($tag->id);
                }
            }
        }

        return redirect()->route('dashboard')->with('success', 'Event created successfully!');
    }
    /**
     * Display the specified resource.
     */
    /**
     * Display the specified resource.
     */
    public function show(Event $event)
    {
        $event->load('tags');
        return view('events.show', compact('event'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Event $event)
    {
        if ($event->user_id !== auth()->id() && !auth()->user()->is_admin) {
            abort(403);
        }
        return view('events.edit', compact('event'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Event $event)
    {
        if ($event->user_id !== auth()->id() && !auth()->user()->is_admin) {
            abort(403);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'date' => 'required|date',
            'time' => 'required',
            'location' => 'required|string|max:255',
            'category' => 'required|in:Academic,Cultural,Sports,Religious,Social,Workshop,Market,Community',
            'organizer' => 'required|string|max:255',
            'image' => 'nullable|image|max:2048', // 2MB max matched with store method
            'participation_link' => 'nullable|url',
            'is_starpoints' => 'required|boolean',
            'tags' => 'nullable|string',
        ]);

        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($event->image_path) {
                $relativePath = str_replace('/storage/', '', $event->image_path);
                if (\Illuminate\Support\Facades\Storage::disk('public')->exists($relativePath)) {
                    \Illuminate\Support\Facades\Storage::disk('public')->delete($relativePath);
                }
            }

            $path = $request->file('image')->store('events', 'public');
            $validated['image_path'] = \Illuminate\Support\Facades\Storage::url($path);
        }

        $event->update([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'date' => $validated['date'],
            'time' => $validated['time'],
            'location' => $validated['location'],
            'category' => $validated['category'],
            'organizer' => $validated['organizer'],
            'image_path' => $validated['image_path'] ?? $event->image_path,
            'participation_link' => $validated['participation_link'],
            'is_starpoints' => $validated['is_starpoints'],
        ]);

        // Handle Tags
        if ($request->filled('tags')) {
            $tagNames = array_map('trim', explode(',', $request->tags));
            $kulliyyahTags = ['AHAS KIRKHS', 'AIKOL', 'KAED', 'KENMS', 'KOED', 'KOE', 'KICT'];
            $tagIds = [];

            foreach ($tagNames as $tagName) {
                if (!empty($tagName)) {
                    $type = in_array($tagName, $kulliyyahTags) ? 'Kulliyyah' : 'Activity';
                    $tag = \App\Models\Tag::firstOrCreate(
                        ['name' => $tagName],
                        ['type' => $type]
                    );
                    $tagIds[] = $tag->id;
                }
            }
            $event->tags()->sync($tagIds);
        } else {
            $event->tags()->detach();
        }

        return redirect()->route('events.show', $event)->with('success', 'Event updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Event $event)
    {
        // Check authorization
        if ($event->user_id !== auth()->id() && !auth()->user()->is_admin) {
            abort(403);
        }

        // Delete image if exists
        if ($event->image_path) {
            $relativePath = str_replace('/storage/', '', $event->image_path);
            if (\Illuminate\Support\Facades\Storage::disk('public')->exists($relativePath)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($relativePath);
            }
        }

        $event->delete();

        if (url()->previous() === route('events.show', $event)) {
            return redirect()->route('events.index')->with('success', 'Event deleted successfully.');
        }

        return back()->with('success', 'Event deleted successfully.');
    }

    public function myEvents()
    {
        $events = Event::where('user_id', auth()->id())
            ->withCount('registrations')
            ->orderByRaw("CASE WHEN CONCAT(date, ' ', time) >= NOW() THEN 0 ELSE 1 END")
            ->orderBy('date', 'asc')
            ->orderBy('time', 'asc')
            ->paginate(12);
        return view('events.my-events', compact('events'));
    }

    public function savedEvents()
    {
        $user = auth()->user();

        // Fetch saved registrations with event details
        $savedRegistrations = $user->registrations()
            ->with([
                'event' => function ($query) {
                    $query->withCount('registrations');
                },
                'event.tags'
            ])
            ->where('type', 'saved')
            ->get()
            ->sortBy(function ($registration) {
                $event = $registration->event;
                if (!$event)
                    return 999;
                $dt = \Carbon\Carbon::parse($event->date->format('Y-m-d') . ' ' . $event->time);
                // Future events: priority 0, sorted by timestamp
                // Past events: priority 1, sorted by timestamp
                return ($dt->isPast() ? 10000000000 : 0) + $dt->timestamp;
            })
            ->filter(function ($registration) {
                return $registration->event !== null;
            });

        return view('events.saved', compact('savedRegistrations'));
    }

    public function toggleReminder(Event $event)
    {
        $registration = \App\Models\Registration::where('user_id', auth()->id())
            ->where('event_id', $event->id)
            ->where('type', 'saved')
            ->firstOrFail();

        $registration->update(['remind_me' => !$registration->remind_me]);

        return back()->with('success', 'Reminder preference updated.');
    }

    public function calendar()
    {
        $events = Event::orderBy('date')->get();
        return view('events.calendar', compact('events'));
    }

    public function register(Event $event)
    {
        // Check if already registered
        $existing = \App\Models\Registration::where('user_id', auth()->id())
            ->where('event_id', $event->id)
            ->where('type', 'registered')
            ->first();

        if ($existing) {
            return back()->with('info', 'You are already registered for this event.');
        }

        // Create Registration record
        \App\Models\Registration::create([
            'user_id' => auth()->id(),
            'event_id' => $event->id,
            'status' => 'confirmed',
            'type' => 'registered'
        ]);

        // Also add to 'Saved' list automatically (to match React frontend behavior where registering -> isSaved=true)
        \App\Models\Registration::firstOrCreate([
            'user_id' => auth()->id(),
            'event_id' => $event->id,
            'type' => 'saved'
        ], [
            'status' => 'pending',
            'remind_me' => false
        ]);

        return back()->with('success', 'Successfully registered for ' . $event->title);
    }

    public function save(Event $event)
    {
        $existing = \App\Models\Registration::where('user_id', auth()->id())
            ->where('event_id', $event->id)
            ->where('type', 'saved')
            ->first();

        if ($existing) {
            $existing->delete(); // Toggle off
            return back()->with('success', 'Event removed from saved list.');
        }

        \App\Models\Registration::create([
            'user_id' => auth()->id(),
            'event_id' => $event->id,
            'status' => 'pending', // Status implies nothing for saved
            'type' => 'saved'
        ]);

        return back()->with('success', 'Event saved successfully.');
    }
}
