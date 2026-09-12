<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


class SurveyController extends Controller
{
    public function index() {
        return view('residentsScreen.survey');
    }

    public function adminindex() {
        return view('residentsScreen.admin-survey');
    }
}
