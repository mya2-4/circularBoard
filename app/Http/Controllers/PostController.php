<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;

class PostController extends Controller
{
    public function home() {
        $posts = Post::latest()->get();
        return view('residentsScreen.home',compact('posts'));
    }

    public function admin() {
        return view('residentsScreen.admin');
    }
}
