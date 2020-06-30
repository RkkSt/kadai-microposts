@extends('layouts.app')

@section('content')
    @if($microposts)
        <p>検索結果は <?php print count($microposts); ?> 件です</p>
        <p><?php print count($microposts); ?> 件中、件を表示しています</p>
        @foreach($microposts as $micropost)
        <div>
            <div>{{ $micropost->content }}</div>
        </div>
        @endforeach
    @endif
@endsection