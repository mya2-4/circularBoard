<?php
// app/Models/SurveyQuestion.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SurveyQuestion extends Model
{
    protected $fillable = ['survey_id', 'body', 'sort_order'];

    public function survey(): BelongsTo
    {
        return $this->belongsTo(Survey::class);
    }
}