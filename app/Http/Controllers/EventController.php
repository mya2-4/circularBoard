<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index(Request $request)
    {
        $events = Event::withCount('participants')
            ->whereIn('status', ['open', 'closed', 'ended'])
            ->orderBy('start_at')
            ->get();

        return view('residentsScreen.event', compact('events'));
    }
}