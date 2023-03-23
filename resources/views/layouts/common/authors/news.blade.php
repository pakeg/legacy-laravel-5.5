@extends('layouts.main')

@section('content')
    <div class="container" id="author">
        <div class="row">
            <div class="col-md-8 col-8 list-author right-col">

               {{-- news --}}
                @foreach ( $news as $post)
                <div>
                    <div><img src="{{$thumb}}{{$post->image}}" alt=""></div>
                    <div>
                        <div class="title">{{$post->title}}</div>
                        <div class="desc">
                            {!! substr(strip_tags(html_entity_decode($post->description, ENT_QUOTES, 'UTF-8')), 0, 739)!!}..
                        </div>
                        <div class="butt">
                            <button><a href="{{url('news/')}}/{{$post->id}}">Подробнее</a></button>
                        </div>
                    </div>
                </div>
                @endforeach
                <div class="news-pag">
                    {{$news->links()}}
                    @if ($news->currentPage() > 1)
                        <a href="{{$news->previousPageUrl()}}"> <i class="fas fa-chevron-left"></i> </a>
                    @else
                        <a> <i class="fas fa-chevron-left"></i> </a>
                    @endif
                    @if ($news->lastPage() != $news->currentPage())
                        <a href="{{$news->nextPageUrl()}}"> <i class="fas fa-chevron-right"></i> </a>
                    @else
                        <a> <i class="fas fa-chevron-right"></i> </a>
                    @endif
                </div>
            </div>

            {{-- боковая панель --}}
            <div class="col-md-4 col-4">
                <div id="archive" class="left-col">
                    <div class="arh-title"><span>СТАТЬИ АВТОРА</span></div>
                    @foreach ($articles as $post)
                        <div class="blc-post">
                            <div class="blc-time">
                                <span>{{$post->created_at->format('H:i')}}</span>
                            </div>
                            <div class="blc-cont">
                                <div>
                                    <a href="{{url('news/')}}/{{$post->id}}">{{$post->title}}</a>
                                </div>
                                <div>
                                    <div><i class="fa fa-eye" aria-hidden="true"></i>{{$post->viewed}}</div>
                                    <div><i class="fa fa-comments" aria-hidden="true"></i>{{$post->comments->count()}}</div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                        <div>
                            <div class="blc-but">
                                <button><a href="{{route('authors-articles', $user->name)}}">Все статьи</a></button>
                            </div>
                        </div>
                </div>
            </div>
        </div>
    </div>
@endsection