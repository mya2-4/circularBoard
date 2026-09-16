<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\SurveyController;
use App\Http\Controllers\Admin\PostController as AdminPostController;
use App\Http\Controllers\Admin\EventController as AdminEventController;
use App\Http\Controllers\Admin\SurveyController as AdminSurveyController;
use App\Http\Controllers\AuthController;


// ===== 一般利用者側 =====
Route::get('/home', [PostController::class, 'home'])
    ->name('home');

Route::get('/posts/{post}', [PostController::class, 'show'])
    ->name('posts.show')
    ->middleware('auth');

Route::get('/events', [EventController::class, 'index'])
    ->name('events.index');

Route::get('/surveys', [SurveyController::class, 'index'])
    ->name('surveys.index');

Route::get('/posts/{post}', [PostController::class, 'show'])
    ->name('posts.show')
    ->middleware('auth');

Route::post('/posts/{post}/confirm', [PostController::class, 'confirm'])
    ->name('posts.confirm')
    ->middleware('auth');

Route::get('/events/{event}', [EventController::class, 'show'])
    ->name('events.show')
    ->middleware('auth');

Route::post('/events/{event}/participate', [EventController::class, 'participate'])
    ->name('events.participate')
    ->middleware('auth');

Route::get('/', function () {
        return view('welcome');
});

// ===== 認証関連 =====
Route::get('/login', [LoginController::class, 'login'])
    ->name('login');

Route::get('/register', [LoginController::class, 'register'])
    ->name('register');

Route::post('/register', [AuthController::class, 'register'])
    ->name('register.store');

Route::post('/login', [AuthController::class, 'login'])
    ->name('login.store');

Route::post('/logout', [AuthController::class, 'logout'])
    ->name('logout');

// ===== その他（要確認・後述） =====
Route::get('/event', [EventController::class, 'event']);
Route::get('/survey', [SurveyController::class, 'adminindex']);

// ===== 管理画面 =====
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    Route::resource('posts', AdminPostController::class);
    Route::resource('events', AdminEventController::class);
    Route::resource('surveys', AdminSurveyController::class);

    Route::post('logout', [AuthController::class, 'logout'])->name('logout');
});