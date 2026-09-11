@extends('layouts.app')

@section('title', 'アンケート')

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

    <h2>アンケート</h2>

    <p class="no-posts">
      回答するアンケートがありません
    </p>

</div>

@endsection