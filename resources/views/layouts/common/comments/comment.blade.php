@foreach($items as $item)

<li id="li-comment-{{$item->id}}" class="comment">
	<div id="comment-{{$item->id}}" class="comment-container">

		{{-- 1 --}}
		<div class="comm-head">
			<div class="comm-info">
				<div><img src="{{$image_user}}{{$item->user->image}}" alt="{{$item->user->name}}"></div>
				<div>{{$item->user->name}}</div>
				<div>{{$item->created_at->format('d.m.y, H:i')}}</div>
			</div>
			<div class="like-dis">
				@php $count = $item->likesDiffDislikesCount;
					if ($count == 0){
						$class = 'default';
				 	}else if ($count > 0 ){
				 		$class = 'liked';
				 	}else if ($count < 0){
				 		$class = 'disliked';
				 	}
				@endphp
				@if (Auth::user())
					@if ($item->liked)
						<button disabled="disabled" data-id-com='{{$item->id}}' data-id-like="1" class="likes like"><i class="fas fa-thumbs-up liked"></i></button>
					@elseif ($item->disliked)
						<button data-id-com='{{$item->id}}' data-id-like="1" class="likes like disno"><i class="fas fa-thumbs-up"></i></button>
					@else
						<button data-id-com='{{$item->id}}' data-id-like="1" class="likes like"><i class="fas fa-thumbs-up"></i></button>
					@endif
						<span class="{{$class}}">{{$item->likesDiffDislikesCount}}</span>
					@if ($item->disliked)
						<button disabled="disabled" data-id-com='{{$item->id}}' data-id-like="0" class="likes dislike"><i class="fas fa-thumbs-down disliked"></i></button>
					@elseif ($item->liked)
						<button data-id-com='{{$item->id}}' data-id-like="0" class="likes dislike disno"><i class="fas fa-thumbs-down"></i></button>
					@else
						<button data-id-com='{{$item->id}}' data-id-like="0" class="likes dislike"><i class="fas fa-thumbs-down"></i></button>
					@endif
				@else
					<span class="{{$class}}">{{$count}}</span>
				@endif
			</div>
		</div>

		{{-- 2 --}}
		<div class="comm-text">			
			<div>{!! html_entity_decode($item->text, ENT_QUOTES, 'UTF-8') !!}</div>
		</div>

		{{-- 3 --}}
		<div class="comm-reply">
			@if (Auth::check())
				@if ( (Auth::user()->id != $item->user->id) && (!Auth::guest()) )
					<div class="comm-add"><p onclick="replyCom('{{$item->id}}');">Ответить</p> &#8195;<i class="fas fa-comment-dots"></i></div>
				@endif
			<div>Поделиться &#8195;<i class="fas fa-share"></i></div>
				@if ( (Auth::user()->id != $item->user->id) && (!Auth::guest()) )
					<div>Пожаловаться &#8195;<i class="fas fa-gavel"></i></div>
				@endif
			@endif
		</div>
	</div>
	
	{{-- children --}}
	@if(isset($com[$item->id]))
	<ul class="children">
		@include('layouts.common.comments.comment', ['items' => $com[$item->id]])
	</ul>
	@endif
	
</li>

@endforeach