<?php
// app/Models/SurveyAnswerSelection.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SurveyAnswerSelection extends Model
{
    protected $fillable = ['survey_answer_id', 'survey_question_option_id'];

    public function answer(): BelongsTo
    {
        return $this->belongsTo(SurveyAnswer::class);
    }

    public function option(): BelongsTo
    {
        return $this->belongsTo(SurveyQuestionOption::class, 'survey_question_option_id');
    }
}