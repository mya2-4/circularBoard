<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventParticipant;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index()
    {
        $events = Event::withSum('participants', 'participant_count')
            ->orderByDesc('start_at')
            ->get();

        return view('residentsScreen.event', compact('events'));
    }

    public function show(Event $event)
    {
        $event->loadSum('participants', 'participant_count');

        $myParticipation = $event->participants()
            ->where('user_id', auth()->id())
            ->first();

        return view('residentsScreen.event-show', compact('event', 'myParticipation'));
    }

    public function participate(Request $request, Event $event)
{
    if ($event->status !== 'open') {
        return back()->with('error', 'このイベントは現在参加受付を行っていません');
    }

    $alreadyJoined = $event->participants()
        ->where('user_id', auth()->id())
        ->exists();

    if ($alreadyJoined) {
        return back()->with('error', 'すでに参加申込済みです');
    }

    $validated = $request->validate([
        'participant_count' => 'required|integer|min:1|max:20',
        'remarks'            => 'nullable|string|max:1000',
    ], [
        'participant_count.required' => '参加人数を入力してください',
        'participant_count.min'      => '参加人数は1人以上で入力してください',
    ]);

    $currentTotal = $event->participants()->sum('participant_count');

    if ($event->capacity && ($currentTotal + $validated['participant_count']) > $event->capacity) {
        $remaining = max(0, $event->capacity - $currentTotal);
        return back()
            ->withInput()
            ->with('error', "残り定員は{$remaining}名です。人数を調整してください");
    }

    EventParticipant::create([
        'event_id'          => $event->id,
        'user_id'           => auth()->id(),
        'participant_count' => $validated['participant_count'],
        'remarks'           => $validated['remarks'] ?? null,
    ]);

    return redirect()
        ->route('events.show', $event->id)
        ->with('success', '参加申込が完了しました');
    }
}
