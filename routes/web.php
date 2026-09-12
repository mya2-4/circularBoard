<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\SurveyController;
use App\Http\Controllers\AuthController;


Route::get('/home', [PostController::class, 'home'])
    ->name('home');

Route::get('/events', [EventController::class, 'index'])
    ->name('events.index');

Route::get('/surveys', [SurveyController::class, 'index'])
    ->name('surveys.index');

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', [LoginController::class,'login'])
    ->name('login');

Route::get('/register', [LoginController::class,'register'])
    ->name('register');

Route::post('/register', [AuthController::class, 'register'])
    ->name('register.store');

Route::post('/login', [AuthController::class, 'login'])
    ->name('login.store');

Route::post('/logout', [AuthController::class, 'logout'])
    ->name('logout');

Route::get('/admin', [PostController::class, 'admin'])
    ->name('admin.posts.index');

Route::get('/event', [EventController::class, 'event']);

Route::get('/survey', [SurveyController::class, 'adminindex']);

Route::get('/admin/posts/{post_id}', [PostController::class, 'show'])
    ->name('admin.posts.show');