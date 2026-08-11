<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEventRequest;
use App\Http\Requests\UpdateEventRequest;
use App\Models\Event;

class EventController extends Controller
{
    public function index()
    {
        $events = Event::withCount('movements')->oldest('event_date')->get();
        return view('events.index', compact('events'));
    }

    public function create()
    {
        return view('events.create');
    }

    public function store(StoreEventRequest $request)
    {
        $validated = $request->validated();

        Event::create($validated);

        return redirect()->route('events.index')->with('success', 'Event berhasil ditambahkan');
    }

    public function edit(Event $event)
    {
        return view('events.edit', compact('event'));
    }

    public function update(UpdateEventRequest $request, Event $event)
    {
        $validated = $request->validated();

        $event->update($validated);

        return redirect()->route('events.index')->with('success', 'Event berhasil diupdate');
    }

    public function destroy(Event $event)
    {
        $event->delete();

        return redirect()->route('events.index')->with('success', 'Event berhasil dihapus');
    }
}