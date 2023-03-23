@extends('layouts.main')

@section('content')
  <div class="container" id="author">
      @foreach ( $users as $user)
        <div class="row">
          {{-- authors --}}
            <div class="col-md-3 col-3">
              <div class="image">
                <div><img src="{{$thumb}}{{$user->image}}" alt=""></div>
                <div><h4>{{$user->name}}</h4></div>
              </div>
              <div class="statistic">
                <div>Новостей: {{ $user->posts->where('type', '2')->count() }}</div>
                <div>Статей: {{ $user->posts->where('type', '1')->count() }}</div>
                @php $count_like = 0; $count_dislike = 0;
                  foreach ( $user->comments as $com) {
                    $count_like += $com->likesCount; 
                    $count_dislike += $com->dislikesCount;
                  }
                @endphp             
                <div>Лайки {{$count_like}} | Дизлайки {{$count_dislike}}</div>
              </div>
              <div class="buttons">
                @if(Auth::check())
                  @if (Auth::user()->id == $user->id)
                  @else
                     @if (Auth::user()->isFollowing($user->id))
                        <button data-follow="1" data-user-id="{{$user->id}}" class="folw hide">Подписаться на автора <i class="fas fa-envelope"></i></button>
                        <button data-follow="0" data-user-id="{{$user->id}}" class="unfolw">Отписаться от автора</button>
                      @else
                        <button data-follow="1" data-user-id="{{$user->id}}" class="folw">Подписаться на автора <i class="fas fa-envelope"></i></button>
                        <button data-follow="0" data-user-id="{{$user->id}}" class="unfolw hide">Отписаться от автора</button>
                      @endif
                  @endif 
                @endif               
                <button class="read"><a href="{{route('authors-articles', $user->name)}}">Читать все статьи автора</a></button>
              </div>
            </div>

           {{-- posts --}}
            <div class="col-md-9 col-9 ar-post">
              @foreach ( $user->posts->sortByDesc('created_at')->take(3) as $post)
              <div>
                <div class="title">{{$post->title}}</div>
                <div class="desc">
                  {!! substr(strip_tags(html_entity_decode($post->description, ENT_QUOTES, 'UTF-8')), 0, 739)!!}..
                </div>
                <div class="butt">
                  <button><a href="{{url('news/')}}/{{$post->id}}">Подробнее</a></button>
                </div>
              </div>
              @endforeach
            </div>
        </div>
      @endforeach
    </div>
    <script type="text/javascript">
      $('#author .buttons .folw, #author .buttons .unfolw').click(function(){
        var type   = $(this).attr('data-follow'),
            target = $(this).attr('data-user-id'),
            $this  = $(this);

        $.ajax({
             headers: {
              'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            type:'post',
            dataType:'json',
            url: '/authors',
            data: 'type=' + type + '&target=' + target,
            success: function(json){
              $this.addClass('hide');
              if (json.types == 1) {
                  $this.next().removeClass('hide');
              }
              if (json.types == 0) {
                $this.prev().removeClass('hide');
              }
            },
            error: function(xhr, ajaxOptions, thrownError) {
              alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
              $('.page-header').after('<div id="import" class="container-fluid">'+ thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText +'</div>');
            } 
        });
      });
    </script>
@endsection