<li id="li-comment-{{$data['id']}}" class="comment">
	<div id="comment-{{$data['id']}}" class="comment-container">

		{{-- 1 --}}
		<div class="comm-head">
			<div class="comm-info">
				<div><img src="{{$data['user_image']}}" alt="{{$data['user_name']}}"></div>
				<div>{{$data['user_name']}}</div>
				<div>{{$data['created_at']}}</div>
			</div>
			<div class="like-dis">

			</div>
		</div>

		{{-- 2 --}}
		<div class="comm-text">
			<div>{!! html_entity_decode($data['text'], ENT_QUOTES, 'UTF-8') !!}</div>
		</div>

		{{-- 3 --}}
		<div class="comm-reply">
			<div>Поделиться &#8195;<i class="fas fa-share"></i></div>
		</div>
	</div>
</li>