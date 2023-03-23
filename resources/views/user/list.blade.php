@extends('layouts.main')

@section('content')
    <div class="container">
        <div class="row">
        	<div class="col-md-4 col-4">
        		@include('user._user')
        	</div>
        	<div class="col-md-8 col-8" id="list-post">
            <div id="user-stats">
                <div>Новостей: &#8195;{{$count_news}}</div>
                <div>Статей: &#8195;{{$count_articles}}</div>
            </div>
                @foreach ( $posts as $key => $post )
            		<div class="row">
                        <div class="col-md-3 col-3">
                            <img id="preview-image-list" src="{{ $thumb[$key] }}" alt="">
                        </div>
                        <div class="col-md-9 col-9 list-post-cont">
                            <div class="list-title">{{ $post->title }}</div>
                            <div class="list-desc"> {!! html_entity_decode($post->description, ENT_QUOTES, 'UTF-8')!!}</div>
                            <div>Написана  {{ $post->created_at->format('d.m.Y') }}</div>
                            <div class="list-button">
                                <a href="{{ action('User\Office\HomeController@delete', ['name' =>$post->user->id,'id' =>$post->id]) }}">Удалить статью</a>
                                <a href="{{ action('User\Office\HomeController@edit', ['name' =>$post->user->id,'id' =>$post->id]) }}">Редактировать статью</a>
                            </div>
                        </div>                        
                    </div>
                @endforeach
        	</div>
        </div>
    </div>
@endsection