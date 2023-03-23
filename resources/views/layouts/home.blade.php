@extends('layouts.main')

@section('content')
	<div class="container" id="main-block">
		{{-- matches --}}
		<div class="row">
			<div class="col-md-12 col-12">
				<div class="tabs right-col" id="live">
						<div id="result">
							<div class="matches">
								{{-- foreach--}}
								<div class="matches-ajax">
									@foreach( $leagues as $league )
										<div class="match">
											<div class="match-head">
												<div>
													<img src="{{asset('images/flags')}}/{{$league->first()->first()->league->country->cc}}.png" alt="">
													<span>{{$league->first()->first()->league->name}}</span>
												</div>								
												<button class="home-page-table"><a href="{{route('lg-table', $league->first()->first()->league->id)}}">Таблица</a></button>
											</div>
											<div class="match-group">
												@foreach ($league as $events)
													<div>
														@foreach ($events as $event)
															<div class="match-cont">
																<a class="{{in_array($event->time_status, [0,1,3]) ? '' : 'not-href'}}" href="{{ in_array($event->time_status, [0,1,3]) ? route('match', $event->event_id) : ''}}">
																	<span>{{$event->time->format('H:s')}}</span>
																	<span class="team-n">{{$event->homeT['name']}}</span>
																	<span class="{{ $event->time_status == 1 ? 'time-marg' : ''}}">{{$event->scores_home}}:{{$event->scores_away}}</span>
																	<span class="team-n">{{$event->awayT['name']}}</span>
																	@if ( $event->time_status == 3)
																		<span class="time-s">Окончен</span>
																	@elseif ( $event->time_status == 0 )
																		<span class="time-s">Не начался</span>
																	@elseif ( $event->time_status == 4 )
																		<span class="time-s">Отложен</span>
																	@elseif ( $event->time_status == 2 )
																		<span class="time-s">Будет исправлен</span>
																	@elseif ( $event->time_status == 5 )
																		<span class="time-s">Отменен</span>
																	@elseif ( $event->time_status == 6 )
																		<span class="time-s">Легкая победа</span>
																	@elseif ( $event->time_status == 7 )
																		<span class="time-s">Прерваный</span>
																	@elseif ( $event->time_status == 8 )
																		<span class="time-s">Заброшенный</span>
																	@elseif ( $event->time_status == 9 )
																		<span class="time-s">Отставной</span>
																	@elseif ( $event->time_status == 99 )
																		<span class="time-s">Удален</span>
																	@elseif ( $event->time_status == 1 )
																		@if ( $event->timer <= '45' )
																			<span class="time-s time-per">1-й Тайм</span>
																		@elseif ( $event->timer > '45' )
																			<span class="time-s time-per">2-й Тайм</span>
																		@endif
																	@endif
																</a>
															</div>
														@endforeach
													</div>
												@endforeach	
											</div>							
										</div>
									@endforeach			
								</div>								
							</div>
						</div>						
				</div>
			</div>
		</div>

		{{-- resors --}}
		<div class="row">
			{{-- ПРАВАЯ ПАНЕЛЬ--}}
			<div class="col-md-8 col-8">
				<div id="tabs-1" class="right-col">
					<div>					
		        <ul class="nav nav-tabs thead" role="tablist">
							<li class="active">
								<a href="#main-post" class="active" aria-controls="main-post" role="tab" data-toggle="tab">ГЛАВНЫЕ НОВОСТИ
								</a>					
							</li>
							<li>
								<a href="#popular-post" aria-controls="popular-post" role="tab" data-toggle="tab">ПОПУЛЯРНЫЕ НОВОСТИ
								</a>
							</li>
						</ul>
					</div>
					<div class="tab-content">
						<div role="tabpanel" class="tab-pane active main-posts" id="main-post">
							<div class="post-nav">
								<div>
									<ul>@php $row = count($posts); @endphp
										@for ($i = 0; $i <= $row-1; $i++)
											@if ( $i == 0 )	@php $class = 'actives'; @endphp @else @php $class = ''; @endphp @endif
											<li data-id="{{$i}}"><p class="{{$class}}"></p></li>
										@endfor
									</ul>
								</div>
								<div class="nav">
									<p class="prev"><i class="fas fa-chevron-left"></i></p><p class="next"><i class="fas fa-chevron-right"></i></p>
								</div>
							</div>
							@foreach ( $posts as $key => $post_block)
								@if ( $key == 0 )	@php $class = ' actives'; @endphp @else 	@php $class = ''; @endphp @endif
								<div class="block{{ $class }}" data-id="{{$key}}">
									@foreach ( $post_block as $post)
										<div>
											<div class="slpost-image">
												<img src="{{$thumb}}{{!empty($post->image) ? $post->image : 'no_image.jpg'}}" alt="">
											</div>
											<div class="slpost-cont">
												<div class="slpost-title"><a href="{{url('news/')}}/{{$post->id}}">{{$post->title}}</a></div>
												<div class="slpost-desc">{!! substr(strip_tags(html_entity_decode($post->description, ENT_QUOTES, 'UTF-8')), 0, 145)!!}..
												</div>
												<div class="slpost-data">
													<span>{{$post->created_at->format('d.m.y')}}</span>
													<div><i class="fa fa-eye" aria-hidden="true"></i>{{$post->viewed}}</div>
													<div><i class="fa fa-comments" aria-hidden="true"></i>{{$post->comments->count()}}</div>
												</div>
											</div>
										</div>
									@endforeach
								</div>
							@endforeach							
						</div>
						<div role="tabpanel" class="tab-pane main-posts" id="popular-post">
							<div class="post-nav">
								<div>
									<ul>@php $row = count($posts_pop); @endphp
										@for ($i = 0; $i <= $row-1; $i++)
											@if ( $i == 0 )	@php $class = 'actives'; @endphp @else @php $class = ''; @endphp @endif
											<li data-id="{{$i}}"><p class="{{$class}}"></p></li>
										@endfor
									</ul>
								</div>
								<div class="nav">
									<p class="prev"><i class="fas fa-chevron-left"></i></p><p class="next"><i class="fas fa-chevron-right"></i></p>
								</div>
							</div>
							@foreach ( $posts_pop as $key => $post_block)
								@if ( $key == 0 )	@php $class = ' actives'; @endphp @else 	@php $class = ''; @endphp @endif
								<div class="block{{ $class }}" data-id="{{$key}}">
									@foreach ( $post_block as $post)
										<div>
											<div class="slpost-image">
												<img src="{{$thumb}}{{!empty($post->image) ? $post->image : 'no_image.jpg'}}" alt="">
											</div>
											<div class="slpost-cont">
												<div class="slpost-title"><a href="{{url('news/')}}/{{$post->id}}">{{$post->title}}</a></div>
												<div class="slpost-desc">{!! substr(strip_tags(html_entity_decode($post->description, ENT_QUOTES, 'UTF-8')), 0, 145)!!}..
												</div>
												<div class="slpost-data">
													<span>{{$post->created_at->format('d.m.y')}}</span>
													<div><i class="fa fa-eye" aria-hidden="true"></i>{{$post->viewed}}</div>
													<div><i class="fa fa-comments" aria-hidden="true"></i>{{$post->comments->count()}}</div>
												</div>
											</div>
										</div>
									@endforeach
								</div>
							@endforeach							
						</div>
					</div>
				</div>

				{{-- ПРАВАЯ ПАНЕЛЬ След. Блок--}}
				<div class="right-col">
					<div  class="tab-pane main-posts" id="papers">
						<div class="pap-title">СТАТЬИ</div>
						<div class="post-nav">
							<div>
								<ul>@php $row = count($papers); @endphp
									@for ($i = 0; $i <= $row-1; $i++)
										@if ( $i == 0 )	@php $class = 'actives'; @endphp @else @php $class = ''; @endphp @endif
										<li data-id="{{$i}}"><p class="{{$class}}"></p></li>
									@endfor
								</ul>
							</div>
							<div class="nav">
								<p class="prev"><i class="fas fa-chevron-left"></i></p><p class="next"><i class="fas fa-chevron-right"></i></p>
							</div>
						</div>
						@foreach ( $papers as $key => $block)
							@if ( $key == 0 )	@php $class = ' actives'; @endphp @else 	@php $class = ''; @endphp @endif
							<div class="block{{ $class }}" data-id="{{$key}}">
								@foreach ( $block as $post)
									<div>
										<div class="slpost-image">
											<img src="{{$thumb}}{{!empty($post->image) ? $post->image : 'no_image.jpg'}}" alt="">
										</div>
										<div class="slpost-cont">
											<div class="slpost-title"><a href="{{url('news/')}}/{{$post->id}}">{{$post->title}}</a></div>
											<div class="slpost-desc">{!! substr(strip_tags(html_entity_decode($post->description, ENT_QUOTES, 'UTF-8')), 0, 193)!!}..
											</div>
											<div class="slpost-data">
												<span>{{$post->created_at->format('d.m.y')}}</span>
												<div><i class="fa fa-eye" aria-hidden="true"></i>{{$post->viewed}}</div>
												<div><i class="fa fa-comments" aria-hidden="true"></i>{{$post->comments->count()}}</div>
											</div>
										</div>
									</div>
								@endforeach
							</div>
						@endforeach							
					</div>
				</div>

				{{-- ПРАВАВАЯ ПАНЕЛЬ След. Блок --}}
				<div id="resors">					
					<div class="col-md-6 col-6 right-col videos">
						<h4 class="pap-title">Видео</h4>
						@foreach($videos as $video)
							<div>
								<div class="resors-title"><a href="{{route('page-video', $video->id)}}">{{$video->title}}</a></div>
								<div class="resors-cont">
									<div>{{$video->created_at->format('d.m.y')}}</div>
									<div><i class="fa fa-eye" aria-hidden="true"></i>{{$video->viewed}}</div>
									<div><i class="fa fa-comments" aria-hidden="true"></i>{{$video->comments->count()}}</div>
								</div>		
							</div>	
						@endforeach
					</div>
					<div class="col-md-6 col-6 right-col photos">
						<h4 class="pap-title">Фотогалерея</h4>
						<div>
							@foreach ($photos as $key => $photo)
								@if ( $key == 0 )	@php $class = ' actives'; @endphp @else 	@php $class = ''; @endphp @endif
								<div class="block{{ $class }}" data-id="{{$key+1}}">
									<a href="{{route('page-gallery', $photo->id)}}"><img src="{{$thumb}}{{!empty($photo->image) ? $photo->image : 'no_image.jpg'}}" alt=""></a>
									<div class="resors-title photos-title"><a href="{{route('page-gallery', $photo->id)}}">{{$photo->title}}</a></div>
								</div>
							@endforeach
							<div class="post-nav">
								<div>
									<ul>@php $row = count($photos); @endphp
										@for ($i = 1; $i <= $row; $i++)
											@if ( $i == 1 )	@php $class = 'actives'; @endphp @else @php $class = ''; @endphp @endif
											<li data-id="{{$i}}"><p class="{{$class}}">{{$i}}</p></li>
										@endfor
									</ul>
								</div>
								<div class="nav">
									<p class="prev"><i class="fas fa-chevron-left"></i></p><p class="next"><i class="fas fa-chevron-right"></i></p>
								</div>
							</div>
						</div>
					</div>					
				</div>
			</div>


			{{-- ЛЕВАЯ ПАНЕЛЬ--}}
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
								<button><a href="{{route('rss')}}" target="_blank"><i class="fa fa-rss"></i></a></button>
							</div>
						</div>
				</div>
		    <div id="subs">
		      <div class="left-col subs-con">
		        <div class="subs-title"><span>Хотите быть в курсе событий из Мира футбола?</span></div>
		        <div>Подписывайтесь на новостную рассылку от <strong>SoccerTimes</strong></div>
		        <div class="box-input">
		          <input type="text" name="sbs_mail" id="subs-in" required>
		          <label for="subs-in">Ваш e-mail</label>           
		        </div>
		        <div class="subs-but">
		          <button>Подписаться</button>
		          <img src="{{asset('images/mail-send.png')}}" alt="mail-send">
		        </div>
		      </div>
		    </div>				
			</div>

		</div>
	</div>
@endsection


	