<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class EventController extends Controller
{
    public function index(Request $request)
    {
        $query = Event::with('creator');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%$search%")
                  ->orWhere('event_code', 'like', "%$search%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('year')) {
            $query->whereYear('event_date', $request->year);
        }

        $events = $query->latest('event_date')->paginate(10);

        return view('admin.events.index', compact('events'));
    }

    public function create()
    {
        return view('admin.events.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'event_date' => 'required|date',
            'location' => 'required|string|max:255',
            'description' => 'required|string',
            'notes' => 'nullable|string',
            'status' => 'required|in:Draft,Selesai,Dibatalkan',
        ]);

        Event::create($validated);

        return redirect()->route('admin.events.index')->with('success', 'Berita Acara berhasil dibuat.');
    }

    public function show(Event $event)
    {
        $event->load('creator');
        return view('admin.events.show', compact('event'));
    }

    public function edit(Event $event)
    {
        return view('admin.events.edit', compact('event'));
    }

    public function update(Request $request, Event $event)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'event_date' => 'required|date',
            'location' => 'required|string|max:255',
            'description' => 'required|string',
            'notes' => 'nullable|string',
            'status' => 'required|in:Draft,Selesai,Dibatalkan',
        ]);

        $event->update($validated);

        return redirect()->route('admin.events.index')->with('success', 'Berita Acara berhasil diperbarui.');
    }

    public function destroy(Event $event)
    {
        $event->delete();
        return redirect()->route('admin.events.index')->with('success', 'Berita Acara berhasil dihapus (Soft Delete).');
    }

    public function downloadPdf(Event $event)
    {
        $event->load('creator');
        $pdf = Pdf::loadView('admin.events.pdf', compact('event'));
        
        $filename = str_replace('/', '-', $event->event_code) . '.pdf';
        return $pdf->download($filename);
    }
}
