<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EventParticipant extends Model
{
    protected $fillable = ['event_id', 'user_id', 'participant_count', 'remarks'];

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function getParticipantsCountAttribute(): int
    {
        return $this->participants()->sum('participant_count');
    }
}