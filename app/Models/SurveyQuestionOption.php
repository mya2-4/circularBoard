<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\SurveyAnswerSelection;

class SurveyQuestionOption extends Model
{
    protected $fillable = ['survey_question_id', 'label', 'sort_order'];

    public function question(): BelongsTo
    {
        return $this->belongsTo(SurveyQuestion::class, 'survey_question_id');
    }

    public function selections(): HasMany
    {
        return $this->hasMany(SurveyAnswerSelection::class, 'survey_question_option_id');
    }
}