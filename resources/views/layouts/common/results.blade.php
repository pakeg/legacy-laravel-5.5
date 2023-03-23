@extends('layouts.main')

@section('content')
	
	<div class="container" id="main-block">
		<div class="row">
			{{-- ПРАВАЯ ПАНЕЛЬ--}}
			<div class="col-md-8 col-8">
				<div class="right-col" id="result">
					<div class="title">
						<div><h4>РЕЗУЛЬТАТЫ МАТЧЕЙ</h4></div>
						<div class="date"><input type="text" id="datepicker" placeholder="Дата" name="date"></div>
					</div>
					<div class="matches">
						<div class="loader">
						  <div class='sk-circle-bounce'>
						    <div class='sk-child sk-circle-1'></div>
						    <div class='sk-child sk-circle-2'></div>
						    <div class='sk-child sk-circle-3'></div>
						    <div class='sk-child sk-circle-4'></div>
						    <div class='sk-child sk-circle-5'></div>
						    <div class='sk-child sk-circle-6'></div>
						    <div class='sk-child sk-circle-7'></div>
						    <div class='sk-child sk-circle-8'></div>
						    <div class='sk-child sk-circle-9'></div>
						    <div class='sk-child sk-circle-10'></div>
						    <div class='sk-child sk-circle-11'></div>
						    <div class='sk-child sk-circle-12'></div>
						  </div>
						</div>
						<div class="matches-ajax">
							@foreach( $events as $events )
								<div class="match">
									<div class="match-head">
										<div>
											<img src="{{asset('images/flags')}}/{{$events->first()->league->country->cc}}.png" alt="">
											<span>{{$events->first()->league->name}}</span>
										</div>								
										<button><a href="{{route('lg-table', $events->first()->league->id)}}">Таблица</a></button>
									</div>
									@foreach ($events as $event)
										<div class="match-cont">
											<a class="{{($event->time_status == 3 or $event->time_status == 1) ? '' : 'not-href'}}" href="{{($event->time_status == 3 or $event->time_status == 1) ? route('match', $event->event_id) : '#'}}">
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
								<button><a href="#"><i class="fa fa-rss"></i></a></button>
							</div>
						</div>
				</div>				
			</div>
		</div>
	</div>
<script src="{{ asset('js/jquery-ui.min.js') }}"></script>
<script type="text/javascript">
	var date = new Date();
	if (date.getMonth()+1 < 10){
		m = '.0'+parseInt(date.getMonth()+1);
	}
	localStorage.setItem('date_old', date.getDate()+m+'.'+date.getFullYear());
	$( "#datepicker" ).attr('placeholder',date.getDate()+m+'.'+date.getFullYear());
	jQuery(function(e){e.datepicker.regional.ru={closeText:"Закрыть",prevText:'<i class="fas fa-caret-left"></i>',nextText:'<i class="fas fa-caret-right"></i>',currentText:"Сегодня",monthNames:["Январь","Февраль","Март","Апрель","Май","Июнь","Июль","Август","Сентябрь","Октябрь","Ноябрь","Декабрь"],monthNamesShort:["Январь","Февраль","Март","Апрель","Май","Июнь","Июль","Август","Сентябрь","Октябрь","Ноябрь","Декабрь"],dayNames:["воскресенье","понедельник","вторник","среда","четверг","пятница","суббота"],dayNamesShort:["вск","пнд","втр","срд","чтв","птн","сбт"],dayNamesMin:["Вс","Пн","Вт","Ср","Чт","Пт","Сб"],weekHeader:"Нед",dateFormat:"dd.mm.yy",firstDay:1,isRTL:!1,showMonthAfterYear:!1,yearSuffix:""},e.datepicker.setDefaults(e.datepicker.regional.ru)});
	$( function(){
		$( "#datepicker" ).datepicker({
			showAnim: 'slideDown',
			showOtherMonths: true,
      selectOtherMonths: true
		});
	});
</script>
<script type="text/javascript">
	$( "#datepicker" ).change(function(){		
		var date = $(this).val(),		
		date_old = localStorage.getItem('date_old');
		if (date == date_old) return false;
		
		$('.matches-ajax').empty();
		$('.loader').show();

		$.ajax({
			headers: {
        'X-CSRF-TOKEN': '{{ csrf_token() }}'
      },
			type: 'post',
			url: '{{route('r-datePicker')}}',
			dataType: 'json',
			data: 'date='+date,
			success: function(json){
				$('.loader').hide();
				$('.matches-ajax').html(json);
				localStorage.setItem('date_old', date);
			},
			error: function(xhr, ajaxOptions, thrownError) {
				$('.loader').hide();
			  $('.matches-ajax').html('<span class="error">Произошла ошибка попробуйте позже.</span>');
			}
		});
	});
</script>

@endsection