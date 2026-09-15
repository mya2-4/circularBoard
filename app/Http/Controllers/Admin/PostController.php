<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;

class PostController extends Controller
{
  public function show(Request $request, Post $post) {
      $query = $post->reads()->with('user');
  
      if ($request->filled('status')) {
          $query->where('status', $request->query('status'));
      }
  
      $reads = $query->latest('updated_at')->paginate(15)->withQueryString();
  
      $confirmedCount = $post->confirmed_count;
      $readCount      = $post->read_count;
      $unreadCount    = $post->unread_count;
      $totalCount     = $confirmedCount + $readCount + $unreadCount;
  
      return view('residentsScreen.show1', compact(
          'post', 'reads', 'confirmedCount', 'readCount', 'unreadCount', 'totalCount'
      ));
  }

  public function store(Request $request) {
      $validated = $request->validate([
          'title'         => 'required|string|max:255',
          'category'      => 'nullable|string|max:100',
          'summary'       => 'nullable|string',
          'body'          => 'required|string',
          'status'        => 'required|in:public,draft',
          'read_mode'     => 'nullable|string',
          'send_reminder' => 'nullable|boolean',
      ]);

      $validated['created_by'] = auth()->id();

      $post = Post::create($validated);

      if ($post->status === 'public') {
          $post->update(['published_at' => now()]);
      }

      // 全住民に対して未読レコードを作成
      $userIds = User::pluck('id');
      $post->reads()->createMany(
          $userIds->map(fn ($id) => ['user_id' => $id, 'status' => 'unread'])->toArray()
      );

      return redirect()
          ->route('admin.posts.show', $post->id)
          ->with('success', '投稿を作成しました');
  }
}