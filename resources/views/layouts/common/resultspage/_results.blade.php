@if (!Request::is('results'))
	<div class="live-select">
		<div class="date"><input type="text" id="datepicker" value="{{$date->format('d.m.Y')}}" placeholder="Дата" name="date"></div>
		<div class="lg-select">
			<label class="lg-label" for="lg-team">
				<select name="lg-team" id="lg-team">
					<option value="0">Выбрать команду</option>
					@foreach ($events as $leage)
						@foreach ($leage as $event)
							<option value="{{$event->home}}">{{$event->homeT['name']}}</option>
							<option value="{{$event->away}}">{{$event->awayT['name']}}</option>
						@endforeach
					@endforeach
				</select>
			</label>
		</div>
		<div class="lg-select">
			<label class="lg-label" for="lg-league">
				<select name="lg-league" id="lg-league">
					<option value="0">Выбрать турнир</option>
					@foreach ($events as $leage)
						<option value="{{$leage->first()->league->id}}">{{$leage->first()->league->name}}</option>
					@endforeach
				</select>
			</label>
		</div>
	</div>
@endif
<div id="result">
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
		{{-- foreach--}}
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
</div>
<script type="text/javascript">
	jQuery(function(e){e.datepicker.regional.ru={closeText:"Закрыть",prevText:'<i class="fas fa-caret-left"></i>',nextText:'<i class="fas fa-caret-right"></i>',currentText:"Сегодня",monthNames:["Январь","Февраль","Март","Апрель","Май","Июнь","Июль","Август","Сентябрь","Октябрь","Ноябрь","Декабрь"],monthNamesShort:["Январь","Февраль","Март","Апрель","Май","Июнь","Июль","Август","Сентябрь","Октябрь","Ноябрь","Декабрь"],dayNames:["воскресенье","понедельник","вторник","среда","четверг","пятница","суббота"],dayNamesShort:["вск","пнд","втр","срд","чтв","птн","сбт"],dayNamesMin:["Вс","Пн","Вт","Ср","Чт","Пт","Сб"],weekHeader:"Нед",dateFormat:"dd.mm.yy",firstDay:1,isRTL:!1,showMonthAfterYear:!1,yearSuffix:""},e.datepicker.setDefaults(e.datepicker.regional.ru)});
	$( function(){
		$( "#datepicker" ).datepicker({
			showAnim: 'slideDown',
			showOtherMonths: true,
      selectOtherMonths: true
		});
	});
</script>