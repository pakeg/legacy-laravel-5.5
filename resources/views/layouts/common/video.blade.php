@extends('layouts.main')

@section('content')
	<div class="container" id="main-block">
	  <div class="row">
	  	{{-- ПРАВАЯ ПАНЕЛЬ--}}
	  	<div class="col-md-8 col-8"> 
	    	<div id="video" class="right-col">  
	    		<div class="main-video">
		    		<h4>ВИДЕО</h4>        
					 	<video
							id="video-{{$videos[0]->id}}"
							class="video-js vjs-default-skin vjs-big-play-centered"
							controls
							width="100%" height="300px"
							data-setup='{ "sources": [{ "type": "video/youtube", "src": "{{$videos[0]->video->url}}"}], "youtube": { "iv_load_policy": 1 } }'
						>
						</video>
						<div class="main-vd-title">
							<div>{{$videos[0]->title}}</div>
						</div>
					</div>
					<div class="block-video">
						@foreach ($videos as $key => $video)
							@if ($key == 0) @continue @endif
								@php if( ($key % 2) != 0){  $class="video-mar"; } else{ $class='';} @endphp
								<div class="{{$class}}">
									<video
										id="video-{{$video->id}}"
										class="video-js vjs-default-skin vjs-big-play-centered"
										controls
										width="100%" height="300px"
										data-setup='{ "sources": [{ "type": "video/youtube", "src": "{{$video->video->url}}"}], "youtube": { "iv_load_policy": 1 } }'
									>
									</video>
									<div>
										<div class="resors-title"><a href="{{url('video/')}}/{{$video->id}}">{{$video->title}}</a></div>
										<div class="resors-cont">
											<div>{{$video->created_at->format('d.m.y, H:i')}}</div>
											<div><i class="fa fa-eye" aria-hidden="true"></i>{{$video->viewed}}</div>
											<div><i class="fa fa-comments" aria-hidden="true"></i>{{$video->comments->count()}}</div>
										</div>		
									</div>
							</div>							
						@endforeach
					</div>
					
					{{-- pagination --}}
					<div class="news-pag">
		    		{{$videos->links()}}
		    		@if ($videos->currentPage() > 1)
							<a href="{{$videos->previousPageUrl()}}"> <i class="fas fa-chevron-left"></i> </a>
						@else
							<a> <i class="fas fa-chevron-left"></i> </a>
						@endif
						@if ($videos->lastPage() != $videos->currentPage())
							<a href="{{$videos->nextPageUrl()}}"> <i class="fas fa-chevron-right"></i> </a>
						@else
							<a> <i class="fas fa-chevron-right"></i> </a>
						@endif
      		</div>
				</div> 
			</div>

			{{-- ЛЕВАЯ ПАНЕЛЬ--}}
			<div class="col-md-4 col-4">
				<div id="archive" class="left-col">
						<div class="arh-title"><span>АРХИВ ВИДЕО</span></div>
						@foreach ($video_arch as $post)
							<div class="blc-post">
								<div class="blc-time">
									<span>{{$post->created_at->format('H:i')}}</span>
								</div>
								<div class="blc-cont">
									<div>
										<a href="{{url('video/')}}/{{$post->id}}">{{$post->title}}</a>
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
									<button><a href="#">Все видео</a></button>
								</div>
								<div class="blc-share">
									<button><a href="#"><i class="fa fa-rss"></i></a></button>
								</div>
							</div>
					</div>				
			</div>
	  </div>
	 </div>
<script type="text/javascript">
	$(document).ready(function(){
		$('.main-video .vjs-big-play-button').click(function(){
			$('.main-video .main-vd-title').remove();
		});
	});
</script>
<script src="https://vjs.zencdn.net/7.1.0/video.js"></script>
<script src="{{ asset('js/ytb.js') }}"></script>
@endsection