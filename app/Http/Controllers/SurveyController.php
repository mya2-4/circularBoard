<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;


class SurveyController extends Controller
{
    public function index() {
        return view('residentsScreen.survey');
    }
}
