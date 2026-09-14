<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\PostImage;
use App\Models\Region;

class PostController extends Controller
{
    public function home()
    {
        $userId = auth()->id();

        $posts = Post::query()
            ->where('status', 'public')
            ->orderByDesc('published_at')
            ->with(['reads' => fn ($q) => $q->where('user_id', $userId)])
            ->get()
            ->map(function ($post) {
                $read = $post->reads->first();
                $post->read_status = $read->status ?? 'unread';
                return $post;
            });

        return view('residentsScreen.home', compact('posts'));
    }

    public function index(Request $request)
    {
        $posts = Post::query()
            ->when($request->status, fn ($q) => $q->where('status', $request->status))
            ->when($request->q, fn ($q) => $q->where('title', 'like', "%{$request->q}%"))
            ->latest('published_at')
            ->paginate(10)
            ->withQueryString();

        return view('residentsScreen.admin', compact('posts'));
    }

    public function create()
    {
        $regions = Region::orderBy('name')->get();
        $householdCount = Region::sum('household_count');

        return view('residentsScreen.create', compact('regions', 'householdCount'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'             => 'required|string|max:255',
            'category'          => 'required|in:event,notice,disaster,environment',
            'summary'           => 'nullable|string|max:255',
            'body'              => 'required|string',
            'status'            => 'required|in:public,draft',
            'published_at_date' => 'nullable|date',
            'published_at_time' => 'nullable|date_format:H:i',
            'read_mode'         => 'required|in:read_only,confirmation_required',
            'send_reminder'     => 'nullable|boolean',
            'regions'           => 'nullable|array',
            'regions.*'         => 'exists:regions,id',
            'images'            => 'nullable|array|max:4',
            'images.*'          => 'image|mimes:jpg,jpeg,png|max:5120',
        ]);

        $publishedAt = null;
        if ($validated['status'] === 'public') {
            $date = $validated['published_at_date'] ?? now()->format('Y-m-d');
            $time = $validated['published_at_time'] ?? '09:00';
            $publishedAt = "{$date} {$time}:00";
        }

        $post = Post::create([
            'title'         => $validated['title'],
            'category'      => $validated['category'],
            'summary'       => $validated['summary'] ?? null,
            'body'          => $validated['body'],
            'status'        => $validated['status'],
            'published_at'  => $publishedAt,
            'read_mode'     => $validated['read_mode'],
            'send_reminder' => $request->boolean('send_reminder'),
            'created_by'    => auth()->id(),
        ]);

        if (!empty($validated['regions'])) {
            $post->regions()->sync($validated['regions']);
        }

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $image) {
                $path = $image->store('post-images', 'public');

                PostImage::create([
                    'post_id'    => $post->id,
                    'path'       => $path,
                    'sort_order' => $index,
                ]);
            }
        }

        return redirect()
            ->route('admin.posts.index')
            ->with('success', '投稿を作成しました');
    }

    public function update(Request $request, Post $post)
    {
        $validated = $request->validate([
            'title'             => 'required|string|max:255',
            'category'          => 'required|in:event,notice,disaster,environment',
            'summary'           => 'nullable|string|max:255',
            'body'              => 'required|string',
            'status'            => 'required|in:public,draft',
            'published_at_date' => 'nullable|date',
            'published_at_time' => 'nullable|date_format:H:i',
            'read_mode'         => 'required|in:read_only,confirmation_required',
            'send_reminder'     => 'nullable|boolean',
            'regions'           => 'nullable|array',
            'regions.*'         => 'exists:regions,id',
            'images'            => 'nullable|array|max:4',
            'images.*'          => 'image|mimes:jpg,jpeg,png|max:5120',
        ]);

        $publishedAt = $post->published_at;
        if ($validated['status'] === 'public') {
            $date = $validated['published_at_date'] ?? now()->format('Y-m-d');
            $time = $validated['published_at_time'] ?? '09:00';
            $publishedAt = "{$date} {$time}:00";
        }

        $post->update([
            'title'         => $validated['title'],
            'category'      => $validated['category'],
            'summary'       => $validated['summary'] ?? null,
            'body'          => $validated['body'],
            'status'        => $validated['status'],
            'published_at'  => $publishedAt,
            'read_mode'     => $validated['read_mode'],
            'send_reminder' => $request->boolean('send_reminder'),
        ]);

        $post->regions()->sync($validated['regions'] ?? []);

        if ($request->hasFile('images')) {
            $existingCount = $post->images()->count();

            foreach ($request->file('images') as $index => $image) {
                $path = $image->store('post-images', 'public');

                PostImage::create([
                    'post_id'    => $post->id,
                    'path'       => $path,
                    'sort_order' => $existingCount + $index,
                ]);
            }
        }

        return redirect()
            ->route('admin.posts.index')
            ->with('success', '投稿を更新しました');
    }

    public function destroy(Post $post)
    {
        $post->delete();

        return redirect()
            ->route('admin.posts.index')
            ->with('success', '投稿を削除しました');
    }

    public function show(Post $post)
    {
        $userId = auth()->id();

        $read = \App\Models\PostRead::firstOrCreate(
            ['post_id' => $post->id, 'user_id' => $userId],
            ['status' => 'unread']
        );

        // まだ未読なら「既読」にする（閲覧数もここでカウント）
        if ($read->status === 'unread') {
            $read->update([
                'status'  => 'read',
                'read_at' => now(),
            ]);

            $post->increment('views_count');
        }

        $post->load('images');
        $readStatus = $read->fresh()->status; // unread はこの時点であり得ない（read か confirmed）

        return view('residentsScreen.show', [
            'post'       => $post,
            'readStatus' => $readStatus,
        ]);
    }

    public function confirm(Post $post)
    {
        $userId = auth()->id();

        \App\Models\PostRead::updateOrCreate(
            ['post_id' => $post->id, 'user_id' => $userId],
            [
                'status'       => 'confirmed',
                'read_at'      => now(),
                'confirmed_at' => now(),
            ]
        );

        return redirect()
            ->route('posts.show', $post->id)
            ->with('success', '確認しました');
    }
}