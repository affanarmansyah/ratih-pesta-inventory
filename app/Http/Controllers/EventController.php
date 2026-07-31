<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index()
    {
        $events = Event::withCount('movements')->latest('event_date')->get();
        return view('events.index', compact('events'));
    }

    public function create()
    {
        return view('events.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'event_date' => 'required|date',
            'location' => 'nullable|string|max:255',
            'status' => 'required|in:pending,berlangsung,selesai',
        ]);

        Event::create($validated);

        return redirect()->route('events.index')->with('success', 'Event berhasil ditambahkan');
    }

    public function edit(Event $event)
    {
        return view('events.edit', compact('event'));
    }

    public function update(Request $request, Event $event)
    {
        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'event_date' => 'required|date',
            'location' => 'nullable|string|max:255',
            'status' => 'required|in:pending,berlangsung,selesai',
        ]);

        $event->update($validated);

        return redirect()->route('events.index')->with('success', 'Event berhasil diupdate');
    }

    public function destroy(Event $event)
    {
        $event->delete();

        return redirect()->route('events.index')->with('success', 'Event berhasil dihapus');
    }
}