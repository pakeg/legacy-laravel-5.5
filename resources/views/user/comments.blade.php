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
                <h3>Последние комментарии</h3>
                @foreach ($comments as $comment)
                    <div class="comment">
                        <h5>{{$comment->post->title}}</h5>
                        <div class="list-com-info">
                            <div class="list-com-user">
                                <div><img src="{{$thumb}}" alt=""></div>
                                <div class="name">{{$comment->user->name}}</div>
                            </div>                        
                            <div class="date">{{$comment->created_at->format('d.m; H:i')}}</div>
                        </div>
                        <div class="list-com-text">
                            <div class="quotes"><i class="fas fa-quote-left"></i></div>
                            <div>{!! html_entity_decode($comment->text, ENT_QUOTES, 'UTF-8') !!}</div>
                        </div>
                        <div class="list-com-button">
                            <button><a href="{{url('news/')}}/{{$comment->post->id}}">Перейти к новости</a></button>
                        </div>
                    </div>
                @endforeach
                    {{-- paginate --}}
                    <div class="news-pag">
                        {{$comments->links()}}
                        @if ($comments->currentPage() > 1)
                            <a href="{{$comments->previousPageUrl()}}"> < </a>
                        @else
                            <a> < </a>
                        @endif
                        @if ($comments->lastPage() != $comments->currentPage())
                            <a href="{{$comments->nextPageUrl()}}"> > </a>
                        @else
                            <a> > </a>
                        @endif
                    </div>
        	</div>
        </div>
    </div>
@endsection