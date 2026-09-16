<?php
// app/Models/Survey.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Survey extends Model
{
    protected $fillable = [
        'title', 'category', 'starts_at', 'ends_at',
        'target_count', 'status', 'created_by',
    ];

    protected $casts = [
        'starts_at' => 'date',
        'ends_at'   => 'date',
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function questions(): HasMany
    {
        return $this->hasMany(SurveyQuestion::class)->orderBy('sort_order');
    }

    public function responses(): HasMany
    {
        return $this->hasMany(SurveyResponse::class);
    }

    public function getResponsesCountAttribute(): int
    {
        if (array_key_exists('responses_count', $this->attributes)) {
            return (int) $this->attributes['responses_count'];
        }
        return $this->responses()->count();
    }

    public function getResponseRateAttribute(): ?int
    {
        if (!$this->target_count) {
            return null;
        }
        return (int) round($this->responses_count / $this->target_count * 100);
    }
}