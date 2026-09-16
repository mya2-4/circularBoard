<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Survey;
use App\Models\SurveyResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class SurveyController extends Controller
{
    public function index(Request $request)
    {
        $query = Survey::withCount(['questions', 'responses']);

        if ($request->filled('status')) {
            $query->where('status', $request->query('status'));
        }

        if ($request->filled('q')) {
            $query->where('title', 'like', '%' . $request->query('q') . '%');
        }

        $surveys = $query->orderByDesc('created_at')->paginate(10)->withQueryString();

        $openCount = Survey::where('status', 'open')->count();

        $thisMonthResponsesCount = SurveyResponse::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        $avgResponseRate = Survey::where('status', 'open')
            ->whereNotNull('target_count')
            ->get()
            ->avg('response_rate');

        return view('residentsScreen.admin.survey', compact(
            'surveys', 'openCount', 'thisMonthResponsesCount', 'avgResponseRate'
        ));
    }
}