@extends('layouts.main')

@section('content')
	<div class="container" id="main-block">
    <div class="row">
      <div class="col-md-8 col-8">
      	<div class="right-col" id="news-main">
      		<div class="news-img video-page">
            <video
              id="video"
              class="video-js vjs-default-skin vjs-big-play-centered"
              controls
              width="100%" height="300px"
              data-setup='{ "sources": [{ "type": "video/youtube", "src": "{{$post->video->url}}"}], "youtube": { "iv_load_policy": 1 } }'
            >
            </video>  
          </div>
      		<div class="news-title"><h1>{{$post->title}}</h1></div>
      		<div class="news-info">
      			<div>{{$post->created_at->format('d.m.y')}}</div>
      			<div><i class="fa fa-eye" aria-hidden="true"></i>{{$post->viewed}}</div>
      			<div><i class="fa fa-comments" aria-hidden="true"></i>{{$post->comments->count()}}</div>
      		</div>
      		<div class="news-desc">
      			{!! html_entity_decode($post->description, ENT_QUOTES, 'UTF-8')!!}
      		</div>
      		<div class="news-auth">
      			<div>Источник: SoccerTimes</div>
      			<div>Автор: {{$post->user->name}}</div>
      		</div>
      		<div class="news-tags">
      			<p>Tеги:</p>
      			@foreach ($post->tags as $tag)
      						<span class="news-tag">{{$tag->name}}</span>
      			@endforeach
      		</div>
      		<div class="news-soc">
      			<div><a href="#"><img src="{{asset('images/vk.jpg')}}" alt=""></a></div>
      			<div><a href="#"><img src="{{asset('images/tl.jpg')}}" alt=""></a></div>
      			<div><a href="#"><img src="{{asset('images/tw.jpg')}}" alt=""></a></div>
      			<div><a href="#"><img src="{{asset('images/fc.jpg')}}" alt=""></a></div>
      			<div><a href="#"><img src="{{asset('images/gl.jpg')}}" alt=""></a></div>
      		</div>
      	</div>

        {{-- блок комментариев --}}
        <div class="right-col" id="comments-block">
            <div class="comm-show">
                <div>
                    <div>КОММЕНТАРИИ: {{$post->comments->count()}}</div>
                    <div><i class="fa fa-chevron-up"></i></div>
                </div>
                @if (Auth::guest())
                    <button><img src="{{ asset('images/log-in.png') }}" alt="">Войти</button>
                @endif
            </div>  
            <div class="comm-hide">
                @if (Auth::user())
                    <div class="write-comm block-send">
                        <div><i class="far fa-comment"></i></div>
                        <div>
                            <textarea placeholder="Напишите ваш комментарий"></textarea>
                        </div>
                    </div>
                    <div class="send">
                      <span>Отправить</span>
                    </div>
                    <div class="error">
                      @if ($errors->has('text'))
                        <span class="help-block">
                            <strong>{{ $errors->first('text') }}</strong>
                        </span>
                      @endif
                    </div>
                @else
                    <span>Войдите или зарегистрируйтесь чтобы оставить комментарий</span>
                @endif

                {{-- комментарии --}}       
                <div id="comments">
                  @if($com)
                    <ol class="commentlist group">   
                    <!-- только родительские комментарии parent_id = 0-->                     
                      @foreach($com as $k => $comments)
                                                   
                          @if($k)
                              @break
                          @endif

                          @include('layouts.common.comments.comment', ['items' => $comments])

                      @endforeach                        
                    </ol>
                  @endif
                </div>
            </div>
        </div>
      </div>

<script type="text/javascript">
    //add comment parent_id 0
    $('#comments-block .send span').click(function(){
      addAjaxCommnet();
    });

    //add comment parent_id > 0
    $('#comments').on('click', '.block-reply .reply span', function(){
      $(this).parents('.block-reply').remove();

      var text_reply = $(this).parents('.block-reply').find('textarea'),
          parent_id  = $(this).attr('data-parent-id');

      addAjaxCommnet(parent_id, text_reply);
    });

    //add field textarea
    var p = $('#comments .comm-add');
    function replyCom(parent_id){ 
            
      $('#comments ol').find('.block-reply').remove();
      if (p.parents('#comment-'+parent_id).parent().children().is('.block-reply')) {           
      } else {
        html = '<div class="block-reply">';
        html += '<div class="write-comm">';
        html +=  '<div><i class="far fa-comment"></i></div>';
        html +=  '<div>';
        html +=  '<textarea placeholder="Напишите ваш комментарий"></textarea>';
        html +=     '</div>';
        html += '</div>';
        html +=  '<div class="reply">';
        html += '<span data-parent-id="'+parent_id+'">Отправить</span>';
        html += '</div>';
        html += '</div>';
        p.parents('#comment-'+parent_id).after(html);
      }
    }

    // add new comment
    function addAjaxCommnet (parent_id = 0, text_reply = ''){
      var text;
      if ( text_reply == '') {
        text = $('.block-send textarea');
      }else {
        text = text_reply;
      }

          
      $.ajax({
             headers: {
              'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            type:'post',
            dataType:'json',
            url: '/video/{{$post->id}}',
            data: 'text=' + text.val() + '&parent_id=' + parent_id,
            success: function(json){

              if (json.parent_id == 0){
                text.val('');
                $('#comments ol').prepend(json.html);
              } else {
                  if ( $('#li-comment-'+json.parent_id).children().is('ul.children') ){
                      $('#li-comment-'+json.parent_id).children('ul.children').prepend(json.html);
                  }else {
                    $('#li-comment-'+json.parent_id).append('<ul class="children">'+ json.html + '</ul>')
                  }
              }  
            },
            error: function(xhr, ajaxOptions, thrownError) {
              alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
              $('.page-header').after('<div id="import" class="container-fluid">'+ thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText +'</div>');
            } 
      });
    }

    $('#comments .comm-head .likes').on('click', function (){
      var $this     = $(this),
          com_id    = $(this).attr('data-id-com'),
          type_like = $(this).attr('data-id-like'),
          _class    = ''; 

        $.ajax({
             headers: {
              'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            type:'post',
            dataType:'json',
            url: '/video/{{$post->id}}/likes',
            data: 'com_id='+com_id+'&type_like='+type_like,
            success: function(json){
              if (json.count == 0){
                _class = 'default';
              }else if (json.count > 0){
                _class = 'liked';
              }else if (json.count < 0){
                _class = 'disliked';
              }
              if (json.types == 1){
                $this.attr('disabled','disabled').addClass('liked');
                $this.parent().children('button:eq(1)').remove();
                $this.next().attr('class',_class).html(json.count);
              }
              if (json.types == 0){
                $this.attr('disabled','disabled').addClass('disliked');
                $this.parent().children('button:eq(0)').remove();
                $this.prev().attr('class',_class).html(json.count);
              }


            },
            error: function(xhr, ajaxOptions, thrownError) {
              alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
              $('.page-header').after('<div id="import" class="container-fluid">'+ thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText +'</div>');
            } 
      });
    });
</script>
<script src="https://vjs.zencdn.net/7.1.0/video.js"></script>
<script src="{{ asset('js/ytb.js') }}"></script>

      <div class="col-md-4 col-4">
      	<div id="archive" class="left-col">
					<div class="arh-title"><span>АРХИВ НОВОСТЕЙ</span></div>
					@foreach ($posts_arch as $post)
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
								<button><a href="{{route('news')}}">Все новости</a></button>
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