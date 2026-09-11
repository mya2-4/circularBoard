@extends('layouts.app')

@section('title', 'ホーム')

@section('content')

<style>
    .tabs {
        display: flex;
        gap: 6px;
        padding: 16px 40px 0;
        background: var(--bg);
    }

    .tab {
        padding: 10px 4px;
        font-size: 13px;
        color: var(--ink-soft);
        border-bottom: 2px solid transparent;
        cursor: pointer;
        margin-right: 28px;
    }

    .tab.active {
        color: var(--ink);
        font-weight: 700;
        border-bottom-color: var(--accent-deep);
    }

    .post {
        padding: 26px 0;
        border-bottom: 1px solid var(--line);
    }

    .post:last-child {
        border-bottom: none;
    }

    .post-head {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 10px;
    }

    .post-head h2 {
        font-family: 'Noto Serif JP', serif;
        font-size: 18px;
        font-weight: 500;
        margin: 0;
        color: var(--ink);
    }

    .new-badge {
        font-size: 11px;
        color: var(--new-tag);
        border: 1px solid var(--new-tag);
        padding: 2px 8px;
        border-radius: 20px;
        letter-spacing: 0.05em;
    }

    .post p {
        margin: 0;
        font-size: 14px;
        line-height: 1.9;
        color: var(--ink-soft);
        max-width: 640px;
    }

    .no-posts {
        font-size: 18px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
</style>


<div class="tabs">
    <div class="tab active">新着回覧</div>
    <div class="tab">過去の回覧</div>
</div>


<div class="content">

    @forelse ($posts as $post)

        <div class="post">

            <div class="post-head">

                <h2>
                    {{ $post->title }}
                </h2>

                @if ($post->is_new)
                    <span class="new-badge">
                        新着
                    </span>
                @endif

            </div>

            <p>
                {{ $post->content }}
            </p>

        </div>

    @empty

        <p class="no-posts">
            新着回覧がありません
        </p>

    @endforelse

</div>

@endsection