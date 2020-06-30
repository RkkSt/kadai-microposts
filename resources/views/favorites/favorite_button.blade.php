@if (Auth::user()->is_favoriting($micropost->id))
    {!! Form::open(['route' => ['user.unfavorite', $micropost->id], 'method' =>'delete']) !!}
        {!! Form::submit('Unfavorite', ['class' => "btn btn-warning btn-sm"]) !!}
    {!! Form::close() !!}
    @else
        {!! Form::open(['route' => ['user.favorite', $micropost->id]]) !!}
            {!! Form::submit('Favorite', ['class' => "btn btn-success btn-sm"]) !!}
        {!! Form::close() !!}
@endif