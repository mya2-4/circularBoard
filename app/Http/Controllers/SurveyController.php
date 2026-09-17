<?php

namespace App\Http\Controllers;

use App\Models\Survey;
use App\Models\SurveyAnswer;
use App\Models\SurveyResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SurveyController extends Controller
{
    public function index()
    {
        $userId = auth()->id();

        $surveys = Survey::where('status', 'open')
            ->withCount('questions')
            ->with(['responses' => fn ($q) => $q->where('user_id', $userId)])
            ->orderByDesc('starts_at')
            ->get()
            ->map(function ($survey) {
                $survey->answered = $survey->responses->isNotEmpty();
                return $survey;
            });

        return view('residentsScreen.survey', compact('surveys'));
    }

    public function store(Request $request, Survey $survey)
    {
        if ($survey->status !== 'open') {
            return back()->with('error', 'このアンケートは現在回答を受け付けていません');
        }

        $alreadyAnswered = $survey->responses()
            ->where('user_id', auth()->id())
            ->exists();

        if ($alreadyAnswered) {
            return back()->with('error', 'すでに回答済みです');
        }

        $questions = $survey->questions()->with('options')->get();

        // バリデーションルールを質問タイプごとに組み立て
        $rules = [];
        foreach ($questions as $question) {
            $field = "answers.{$question->id}";
            if ($question->type === 'multiple_choice') {
                $rules[$field] = 'required|array|min:1';
                $rules["{$field}.*"] = 'integer|exists:survey_question_options,id';
            } elseif ($question->type === 'single_choice') {
                $rules[$field] = 'required|integer|exists:survey_question_options,id';
            } else {
                $rules[$field] = 'required|string|max:2000';
            }
        }

        $validated = $request->validate($rules, [], [
            // エラーメッセージを分かりやすくしたい場合はここで属性名を調整可能
        ]);

        DB::transaction(function () use ($survey, $questions, $validated) {
            $response = SurveyResponse::create([
                'survey_id' => $survey->id,
                'user_id'   => auth()->id(),
            ]);

            foreach ($questions as $question) {
                $value = $validated['answers'][$question->id] ?? null;

                if ($question->type === 'text') {
                    SurveyAnswer::create([
                        'survey_response_id' => $response->id,
                        'survey_question_id' => $question->id,
                        'answer_text'         => $value,
                    ]);
                } else {
                    $answer = SurveyAnswer::create([
                        'survey_response_id' => $response->id,
                        'survey_question_id' => $question->id,
                    ]);

                    $optionIds = $question->type === 'multiple_choice' ? $value : [$value];
                    $answer->selectedOptions()->sync($optionIds);
                }
            }
        });

        return redirect()
            ->route('surveys.index')
            ->with('success', '回答ありがとうございました');
    }
}