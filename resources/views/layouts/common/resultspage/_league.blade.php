<div class="match">
	<div class="match-head">
		<div>
			<img src="{{asset('images/flags')}}/{{$league->first()->league->country->cc}}.png" alt="">
			<span>{{$league->first()->league->name}}</span>
		</div>								
		<button><a href="{{route('lg-table', $league->first()->league->id)}}">Таблица</a></button>
	</div>
	@foreach ($league as $event)
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