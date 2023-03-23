@extends('layouts.main')

@section('content')
	<div class="container" id="main-block">
	  <div class="row">
	  	{{-- ПРАВАЯ ПАНЕЛЬ--}}
	  	<div class="col-md-8 col-8"> 
	    	<div id="photo" class="right-col">  
		    	<h4>ФОТОГАЛЕРЕЯ</h4>				 	
					<div class="block-photo">
						@foreach ($photos as $photo)
							<div>
								<div><img src="{{$thumb}}{{$photo->image}}" alt=""></div>
								<div>
									<div class="resors-title"><a href="{{url('gallery/')}}/{{$photo->id}}">{{$photo->title}}</a></div>
									<div class="resors-cont">
										<div>{{$photo->created_at->format('d.m.y, H:i')}}</div>
										<div><i class="fa fa-eye" aria-hidden="true"></i>{{$photo->viewed}}</div>
										<div><i class="fa fa-comments" aria-hidden="true"></i>{{$photo->comments->count()}}</div>
									</div>		
								</div>
							</div>							
						@endforeach
					</div>
					
					{{-- pagination --}}
					<div class="news-pag">
		    		{{$photos->links()}}
		    		@if ($photos->currentPage() > 1)
							<a href="{{$photos->previousPageUrl()}}"> <i class="fas fa-chevron-left"></i> </a>
						@else
							<a> <i class="fas fa-chevron-left"></i> </a>
						@endif
						@if ($photos->lastPage() != $photos->currentPage())
							<a href="{{$photos->nextPageUrl()}}"> <i class="fas fa-chevron-right"></i> </a>
						@else
							<a> <i class="fas fa-chevron-right"></i> </a>
						@endif
      		</div>
				</div> 
			</div>

			{{-- ЛЕВАЯ ПАНЕЛЬ--}}
			<div class="col-md-4 col-4">
				<div id="archive" class="left-col">
						<div class="arh-title"><span>АРХИВ ГАЛЕРЕИ</span></div>
						@foreach ($photo_arch as $post)
							<div class="blc-post">
								<div class="blc-time">
									<span>{{$post->created_at->format('H:i')}}</span>
								</div>
								<div class="blc-cont">
									<div>
										<a href="{{url('gallery/')}}/{{$post->id}}">{{$post->title}}</a>
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
									<button><a href="#">Вся галерея</a></button>
								</div>
								<div class="blc-share">
									<button><a href="#"><i class="fa fa-rss"></i></a></button>
								</div>
							</div>
					</div>				
			</div>
	  </div>
	 </div>
@endsection