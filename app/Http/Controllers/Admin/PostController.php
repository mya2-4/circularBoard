<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;

class PostController extends Controller
{
    /**
     * 回覧一覧（管理画面）
     */
    public function index(Request $request)
    {
        $query = Post::query();

        if ($request->filled('status')) {
            $query->where('status', $request->query('status'));
        }

        if ($request->filled('q')) {
            $query->where('title', 'like', '%' . $request->query('q') . '%');
        }

        $posts = $query->orderByDesc('published_at')->paginate(15)->withQueryString();

        return view('residentsScreen.admin', compact('posts'));
    }

    /**
     * 新規作成フォーム
     */
    public function create()
    {
        return view('admin.posts.create');
    }

    /**
     * 保存
     */
    public function store(Request $request)
    {
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

        $userIds = User::pluck('id');
        $post->reads()->createMany(
            $userIds->map(fn ($id) => ['user_id' => $id, 'status' => 'unread'])->toArray()
        );

        return redirect()
            ->route('admin.posts.show', $post->id)
            ->with('success', '投稿を作成しました');
    }

    /**
     * 詳細（閲覧状況）
     */
    public function show(Request $request, Post $post)
    {
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

    /**
     * 編集フォーム
     */
    public function edit(Post $post)
    {
        return view('admin.posts.edit', compact('post'));
    }

    /**
     * 更新
     */
    public function update(Request $request, Post $post)
    {
        $validated = $request->validate([
            'title'         => 'required|string|max:255',
            'category'      => 'nullable|string|max:100',
            'summary'       => 'nullable|string',
            'body'          => 'required|string',
            'status'        => 'required|in:public,draft',
            'read_mode'     => 'nullable|string',
            'send_reminder' => 'nullable|boolean',
        ]);

        $wasNotPublic = $post->status !== 'public';

        $post->update($validated);

        if ($wasNotPublic && $post->status === 'public') {
            $post->update(['published_at' => now()]);
        }

        return redirect()
            ->route('admin.posts.index')
            ->with('success', '投稿を更新しました');
    }

    /**
     * 削除
     */
    public function destroy(Post $post)
    {
        $post->delete();

        return redirect()
            ->route('admin.posts.index')
            ->with('success', '投稿を削除しました');
    }
}