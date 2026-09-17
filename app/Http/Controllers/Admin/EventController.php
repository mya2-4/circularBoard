<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\EventParticipant;
use Illuminate\Http\Request;

class EventController extends Controller
{
  public function index(Request $request)
  {
      $query = Event::withCount('participants');
  
      if ($request->filled('status')) {
          $query->where('status', $request->query('status'));
      }

      $events = $query->orderByDesc('start_at')->paginate(10)->withQueryString();
  
      $upcomingCount = Event::whereIn('status', ['open', 'closed'])->where('start_at', '>=', now())->count();
      $openParticipantsCount = EventParticipant::whereHas('event', fn ($q) => $q->where('status', 'open'))->count();
      $endedThisMonthCount = Event::where('status', 'ended')
          ->whereMonth('start_at', now()->month)
          ->whereYear('start_at', now()->year)
          ->count();
  
      return view('residentsScreen.admin.event', compact(
          'events', 'upcomingCount', 'openParticipantsCount', 'endedThisMonthCount'
      ));
  }
    
  public function create()
  {
    return view('residentsScreen.admin.event.create');
  }

  public function store(Request $request)
  {
      $validated = $request->validate([
          'title'    => 'required|string|max:255',
          'category' => 'nullable|string|max:100',
          'venue'    => 'nullable|string|max:255',
          'start_date' => 'required|date',
          'start_time' => 'required',
          'end_time'   => 'nullable|date_format:H:i|after:start_time',
          'capacity'   => 'nullable|integer|min:0',
          'status'     => 'required|in:open,closed,ended',
      ]);

      $startAt = \Carbon\Carbon::parse($validated['start_date'] . ' ' . $validated['start_time']);
      $endAt = $validated['end_time']
          ? \Carbon\Carbon::parse($validated['start_date'] . ' ' . $validated['end_time'])
          : null;

      $event = Event::create([
          'title'      => $validated['title'],
          'category'   => $validated['category'] ?? null,
          'venue'      => $validated['venue'] ?? null,
          'start_at'   => $startAt,
          'end_at'     => $endAt,
          'capacity'   => $validated['capacity'] ?? null,
          'status'     => $validated['status'],
          'created_by' => auth()->id(),
      ]);

      return redirect()
          ->route('admin.events.index')
          ->with('success', 'イベントを作成しました');
  }

  public function show(Request $request, Event $event)
  {
      $participants = $event->participants()
          ->with('user')
          ->latest('updated_at')
          ->paginate(15);

      $totalParticipants = $event->participants()->sum('participant_count');

      return view('residentsScreen.admin.event.show', compact('event', 'participants', 'totalParticipants'));
  }
}