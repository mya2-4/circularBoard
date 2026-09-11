@extends('layouts.app')

@section('title', 'イベント')

@section('content')

<style>
  .no-posts {
        font-size: 18px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
</style>

<div class="content">

    <h2>地域のイベント</h2>

    <p class="no-posts">
      地域で開催予定のイベントがありません
    </p>

</div>

@endsection