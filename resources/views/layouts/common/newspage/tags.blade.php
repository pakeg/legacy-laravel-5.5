@extends('layouts.main')

@section('content')
	<div class="container" id="main-block">
    <div class="row">
      <div class="col-md-8 col-8">
      	<div class="right-col" id="news-main">
      		<div class="news-title"><span>Тег: {{$tag->name}}</span></div>
	      	@foreach ($post_list as $post)
	      		<div class="news-info news-list">
	      			<div>{{$post->created_at->format('d.m.y')}}</div>
	      			<div><a href="{{url('news/')}}/{{$post->id}}">{{$post->title}}</a></div>
	      		</div>
	      	@endforeach

	      	<div class="news-pag">
		    		{{$post_list->links()}}
		    		@if ($post_list->currentPage() > 1)
							<a href="{{$post_list->previousPageUrl()}}"> < </a>
						@else
							<a> < </a>
						@endif
						@if ($post_list->lastPage() != $post_list->currentPage())
							<a href="{{$post_list->nextPageUrl()}}"> > </a>
						@else
							<a> > </a>
						@endif
      		</div>
      	</div>
      </div>

      <div class="col-md-4 col-4">
      	<div id="archive" class="left-col">
					<div class="arh-title"><span>ПОПУЛЯРНЫЕ СТАТЬИ</span></div>
					@foreach ($posts_arch as $post)
						<div class="blc-post blc-new">
							<div class="blc-time">
								<img src="{{$thumb}}{{!empty($post->image) ? $post->image : 'no_image.jpg'}}" alt="">
							</div>
							<div class="blc-cont blc-cont-news">
								<div>
									<a href="{{url('news/')}}/{{$post->id}}">{{$post->title}}</a>
								</div>
								<div>
									<span>{{$post->created_at->format('d.m.y')}}</span>
									<div><i class="fa fa-eye" aria-hidden="true"></i>{{$post->viewed}}</div>
									<div><i class="fa fa-comments" aria-hidden="true"></i>{{$post->comments->count()}}</div>
								</div>
							</div>
						</div>
					@endforeach
				</div>
      </div>
    </div>
   </div>
@endsection