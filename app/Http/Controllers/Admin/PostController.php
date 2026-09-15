<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;

class PostController extends Controller
{
  public function show(Post $post)
  {
      $reads = $post->reads()
          ->with('user') // 氏名・世帯情報など
          ->latest('updated_at')
          ->paginate(15);

      $confirmedCount = $post->confirmed_count; // モデルの既存アクセサを利用
      $readCount      = $post->read_count;
      $unreadCount    = $post->unread_count;
      $totalCount     = $confirmedCount + $readCount + $unreadCount;

      return view('residentsScreen.show1', compact(
          'post', 'reads', 'confirmedCount', 'readCount', 'unreadCount', 'totalCount'
      ));
  }
}