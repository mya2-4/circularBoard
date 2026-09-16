<?php

namespace App\Http\Controllers\Admin;

use App\Models\Survey;
use App\Models\SurveyResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\SurveyQuestion;
use App\Models\SurveyQuestionOption;
use App\Models\SurveyAnswer;
use Illuminate\Support\Facades\DB;

class surveyController extends Controller
{

public function index(Request $request)
{
    $query = Survey::withCount(['questions', 'responses']);

    if ($request->filled('status')) {
        $query->where('status', $request->status);
    }

    if ($request->filled('q')) {
        $query->where('title', 'like', '%' . $request->q . '%');
    }

    $surveys = $query->orderBy('created_at', 'desc')
        ->paginate(15)
        ->withQueryString();

    $openCount = Survey::where('status', 'open')->count();

    $thisMonthResponsesCount = SurveyResponse::whereMonth('created_at', now()->month)
        ->whereYear('created_at', now()->year)
        ->count();

    $avgResponseRate = Survey::whereNotNull('target_count')
        ->where('target_count', '>', 0)
        ->withCount('responses')
        ->get()
        ->avg(fn ($s) => $s->target_count > 0 ? ($s->responses_count / $s->target_count) * 100 : null);

    return view('residentsScreen.admin.survey', compact(
        'surveys',
        'openCount',
        'thisMonthResponsesCount',
        'avgResponseRate'
    ));
    }

    public function create()
    {
        return view('residentsScreen.admin.survey.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'         => 'required|string|max:255',
            'category'      => 'nullable|string|max:100',
            'starts_at'     => 'nullable|date',
            'ends_at'       => 'nullable|date|after_or_equal:starts_at',
            'target_count'  => 'nullable|integer|min:0',
            'status'        => 'required|in:open,draft,closed',
    
            'questions'                    => 'required|array|min:1',
            'questions.*.body'             => 'required|string|max:500',
            'questions.*.type'             => 'required|in:text,single_choice,multiple_choice',
            'questions.*.options'          => 'required_unless:questions.*.type,text|array',
            'questions.*.options.*'        => 'nullable|string|max:255',
        ]);
    
        DB::transaction(function () use ($validated) {
            $survey = Survey::create([
                'title'        => $validated['title'],
                'category'     => $validated['category'] ?? null,
                'starts_at'    => $validated['starts_at'] ?? null,
                'ends_at'      => $validated['ends_at'] ?? null,
                'target_count' => $validated['target_count'] ?? null,
                'status'       => $validated['status'],
                'created_by'   => auth()->id(),
            ]);
    
            foreach ($validated['questions'] as $index => $q) {
                $question = SurveyQuestion::create([
                    'survey_id'  => $survey->id,
                    'body'       => $q['body'],
                    'type'       => $q['type'],
                    'sort_order' => $index,
                ]);
    
                if ($q['type'] !== 'text' && !empty($q['options'])) {
                    foreach ($q['options'] as $optIndex => $label) {
                        if (trim((string) $label) === '') {
                            continue;
                        }
                        SurveyQuestionOption::create([
                            'survey_question_id' => $question->id,
                            'label'               => $label,
                            'sort_order'          => $optIndex,
                        ]);
                    }
                }
            }
        });
    
        return redirect()
            ->route('admin.surveys.index')
            ->with('success', 'アンケートを作成しました');
    }

    public function show(Survey $survey)
    {
        $survey->load([
            'questions' => fn ($q) => $q->orderBy('sort_order'),
            'questions.options' => fn ($q) => $q->withCount('selections')->orderBy('sort_order'),
        ]);

        $responsesCount = $survey->responses()->count();

        $textAnswers = SurveyAnswer::whereHas('question', function ($q) use ($survey) {
                $q->where('survey_id', $survey->id)->where('type', 'text');
            })
            ->whereNotNull('answer_text')
            ->where('answer_text', '!=', '')
            ->with('question')
            ->latest()
            ->get()
            ->groupBy('survey_question_id');

        return view('residentsScreen.admin.survey.show', compact('survey', 'responsesCount', 'textAnswers'));
    }

    public function destroy(Survey $survey)
    {
        $survey->delete();

        return redirect()
            ->route('admin.surveys.index')
            ->with('success', 'アンケートを削除しました');
    }
}