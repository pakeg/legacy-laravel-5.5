{{-- Бомардиры --}}
<div role="tabpanel" class="tab-pane active" id="bombar">
	<div class="toplist-head">
		<div class="player">
			<span>Игрок</span>
			<div>
				<button data-mixitup-control type="button" data-sort="player:asc"><i class="fas fa-caret-up"></i></button><button data-mixitup-control type="button" data-sort="player:desc"><i class="fas fa-caret-down"></i></button>
			</div>
		</div>
		<div class="team">
			<span>Команда</span>
			<div>
				<button data-mixitup-control type="button" data-sort="team:asc"><i class="fas fa-caret-up"></i></button><button data-mixitup-control type="button" data-sort="team:desc"><i class="fas fa-caret-down"></i></button>
			</div>
		</div>
		<div class="pos">
			<span>Амплуа</span>
			<div>
				<button data-mixitup-control type="button" data-sort="pos:asc"><i class="fas fa-caret-up"></i></button><button data-mixitup-control type="button" data-sort="pos:desc"><i class="fas fa-caret-down"></i></button>
			</div>
		</div>
		<div class="marg-r">
			<span>Голы</span>
			<div>
				<button data-mixitup-control type="button" data-sort="goals:asc"><i class="fas fa-caret-up"></i></button><button data-mixitup-control type="button" data-sort="goals:desc"><i class="fas fa-caret-down"></i></button>
			</div>
		</div>
		<div class="marg-r">
			<span>Пенальти</span>
			<div>
				<button data-mixitup-control type="button" data-sort="penal:asc"><i class="fas fa-caret-up"></i></button><button data-mixitup-control type="button" data-sort="penal:desc"><i class="fas fa-caret-down"></i></button>
			</div>
		</div>
		<div class="marg-r">
			<span>Мин./гол</span>
			<div>
				<button data-mixitup-control type="button" data-sort="golmin:asc"><i class="fas fa-caret-up"></i></button><button data-mixitup-control type="button" data-sort="golmin:desc"><i class="fas fa-caret-down"></i></button>
			</div>
		</div>
		<div class="marg-r">
			<span>Минуты</span>
			<div>
				<button data-mixitup-control type="button" data-sort="mint:asc"><i class="fas fa-caret-up"></i></button><button data-mixitup-control type="button" data-sort="mint:desc"><i class="fas fa-caret-down"></i></button>
			</div>
		</div>
		<div class="marg-r">
			<span>Игры</span>
			<div>
				<button data-mixitup-control type="button" data-sort="match:asc"><i class="fas fa-caret-up"></i></button><button data-mixitup-control type="button" data-sort="match:desc"><i class="fas fa-caret-down"></i></button>
			</div>
		</div>
	</div>
	<div class="toplist bombar">
		@foreach ($toplist as $list)
			<div class="mix" data-player="{{$list->player['name']}}" data-team="{{$list->team->name}}" data-pos="{{$list->player['position']}}" data-goals="{{$list->goals}}" data-penal="{{$list->penalties}}" data-golmin="{{round($list->minutes_played/$list->goals)}}" data-mint="{{$list->minutes_played}}" data-match="{{$list->matches}}">
				<div class="player"><img src="{{asset('images/flags')}}/{{$list->player['country']['cc']}}.png" alt=""><span>{{$list->player['name']}}</span></div>
				<div class="team"><img src="https://assets.b365api.com/images/team/m/{{$list->team->image}}.png" alt=""><span>{{$list->team->name}}</span></div>
				<div class="pos"><span>{{$list->player['position']}}</span></div>
				<div class="goals"><span>{{$list->goals}}</span></div>
				<div class="penal"><span>{{$list->penalties}}</span></div>
				<div class="gol-min"><span>{{round($list->minutes_played/$list->goals)}}</span></div>
				<div class="mint"><span>{{$list->minutes_played}}</span></div>
				<div class="match"><span>{{$list->matches}}</span></div>
			</div>
		@endforeach
	</div>
</div>

{{-- Передачи --}}
<div role="tabpanel" class="tab-pane" id="assists">
	<div class="toplist-head">
		<div class="player">
			<span>Игрок</span>
			<div>
				<button data-mixitup-control1 type="button" data-sort="player1:asc"><i class="fas fa-caret-up"></i></button><button data-mixitup-control1 type="button" data-sort="player1:desc"><i class="fas fa-caret-down"></i></button>
			</div>
		</div>
		<div class="team">
			<span>Команда</span>
			<div>
				<button data-mixitup-control1 type="button" data-sort="team1:asc"><i class="fas fa-caret-up"></i></button><button data-mixitup-control1 type="button" data-sort="team1:desc"><i class="fas fa-caret-down"></i></button>
			</div>
		</div>
		<div class="pos">
			<span>Амплуа</span>
			<div>
				<button data-mixitup-control1 type="button" data-sort="posa:asc"><i class="fas fa-caret-up"></i></button><button data-mixitup-control1 type="button" data-sort="posa:desc"><i class="fas fa-caret-down"></i></button>
			</div>
		</div>
		<div class="marg-r">
			<span>Игры</span>
			<div>
				<button data-mixitup-control1 type="button" data-sort="match1:asc"><i class="fas fa-caret-up"></i></button><button data-mixitup-control1 type="button" data-sort="match1:desc"><i class="fas fa-caret-down"></i></button>
			</div>
		</div>
		<div class="marg-r">
			<span>Передачи</span>
			<div>
				<button data-mixitup-control1 type="button" data-sort="assist1:asc"><i class="fas fa-caret-up"></i></button><button data-mixitup-control1 type="button" data-sort="assist1:desc"><i class="fas fa-caret-down"></i></button>
			</div>
		</div>
	</div>
	<div class="toplist assists">
		@foreach ($toplist as $list)
			<div class="mix" data-player1="{{$list->player['name']}}" data-team1="{{$list->team->name}}" data-posa="{{$list->player['position']}}" data-match1="{{$list->matches}}" data-assist1="{{$list->assists}}">
				<div class="player"><img src="{{asset('images/flags')}}/{{$list->player['country']['cc']}}.png" alt=""><span>{{$list->player['name']}}</span></div>
				<div class="team"><img src="https://assets.b365api.com/images/team/m/{{$list->team->image}}.png" alt=""><span>{{$list->team->name}}</span></div>
				<div class="pos"><span>{{$list->player['position']}}</span></div>
				<div class="match"><span>{{$list->matches}}</span></div>
				<div class="assist"><span>{{$list->assists}}</span></div>
			</div>
		@endforeach
	</div>
</div>

{{-- Гол+пас --}}
<div role="tabpanel" class="tab-pane" id="goalpass">
	<div class="toplist-head">
		<div class="player">
			<span>Игрок</span>
			<div>
				<button data-mixitup-control2 type="button" data-sort="player2:asc"><i class="fas fa-caret-up"></i></button><button data-mixitup-control2 type="button" data-sort="player2:desc"><i class="fas fa-caret-down"></i></button>
			</div>
		</div>
		<div class="team">
			<span>Команда</span>
			<div>
				<button data-mixitup-control2 type="button" data-sort="team2:asc"><i class="fas fa-caret-up"></i></button><button data-mixitup-control2 type="button" data-sort="team2:desc"><i class="fas fa-caret-down"></i></button>
			</div>
		</div>
		<div class="pos">
			<span>Амплуа</span>
			<div>
				<button data-mixitup-control2 type="button" data-sort="posg:asc"><i class="fas fa-caret-up"></i></button><button data-mixitup-control2 type="button" data-sort="posg:desc"><i class="fas fa-caret-down"></i></button>
			</div>
		</div>
		<div class="marg-r">
			<span>Гол+пас</span>
			<div>
				<button data-mixitup-control2 type="button" data-sort="goalpass2:asc"><i class="fas fa-caret-up"></i></button><button data-mixitup-control2 type="button" data-sort="goalpass2:desc"><i class="fas fa-caret-down"></i></button>
			</div>
		</div>
		<div class="marg-r">
			<span>Игры</span>
			<div>
				<button data-mixitup-control2 type="button" data-sort="match2:asc"><i class="fas fa-caret-up"></i></button><button data-mixitup-control2 type="button" data-sort="match2:desc"><i class="fas fa-caret-down"></i></button>
			</div>
		</div>
	</div>
	<div class="toplist goalpass">
		@foreach ($toplist as $list)
			<div class="mix" data-player2="{{$list->player['name']}}" data-team2="{{$list->team->name}}" data-posg="{{$list->player['position']}}" data-goalpass2="{{$list->goals+$list->assists?? 0}}" data-match2="{{$list->matches}}">
				<div class="player"><img src="{{asset('images/flags')}}/{{$list->player['country']['cc']}}.png" alt=""><span>{{$list->player['name']}}</span></div>
				<div class="team"><img src="https://assets.b365api.com/images/team/m/{{$list->team->image}}.png" alt=""><span>{{$list->team->name}}</span></div>
				<div class="pos"><span>{{$list->player['position']}}</span></div>
				<div class="goal-pass"><span>{{$list->goals+$list->assists?? 0}}</span></div>
				<div class="match"><span>{{$list->matches}}</span></div>
			</div>
		@endforeach
	</div>
</div>

{{-- Предупреждения --}}
<div role="tabpanel" class="tab-pane" id="warnings">
	<div class="toplist-head">
		<div class="player">
			<span>Игрок</span>
			<div>
				<button data-mixitup-control3 type="button" data-sort="player3:asc"><i class="fas fa-caret-up"></i></button><button data-mixitup-control3 type="button" data-sort="player3:desc"><i class="fas fa-caret-down"></i></button>
			</div>
		</div>
		<div class="team">
			<span>Команда</span>
			<div>
				<button data-mixitup-control3 type="button" data-sort="team3:asc"><i class="fas fa-caret-up"></i></button><button data-mixitup-control3 type="button" data-sort="team3:desc"><i class="fas fa-caret-down"></i></button>
			</div>
		</div>
		<div class="pos">
			<span>Амплуа</span>
			<div>
				<button data-mixitup-control3 type="button" data-sort="posw:asc"><i class="fas fa-caret-up"></i></button><button data-mixitup-control3 type="button" data-sort="posw:desc"><i class="fas fa-caret-down"></i></button>
			</div>
		</div>
		<div class="marg-r">
			<span>Игры</span>
			<div>
				<button data-mixitup-control3 type="button" data-sort="match3:asc"><i class="fas fa-caret-up"></i></button><button data-mixitup-control3 type="button" data-sort="match3:desc"><i class="fas fa-caret-down"></i></button>
			</div>
		</div>
		<div class="marg-r">
			<span>Желтые карточки</span>
			<div>
				<button data-mixitup-control3 type="button" data-sort="yellow3:asc"><i class="fas fa-caret-up"></i></button><button data-mixitup-control3 type="button" data-sort="yellow3:desc"><i class="fas fa-caret-down"></i></button>
			</div>
		</div>
		<div class="marg-r">
			<span>Красные карточки</span>
			<div>
				<button data-mixitup-control3 type="button" data-sort="red3:asc"><i class="fas fa-caret-up"></i></button><button data-mixitup-control3 type="button" data-sort="red3:desc"><i class="fas fa-caret-down"></i></button>
			</div>
		</div>
	</div>
	<div class="toplist warnings">
		@foreach ($toplist as $list)
			<div class="mix" data-player3="{{$list->player['name']}}" data-team3="{{$list->team->name}}" data-posw="{{$list->player['position']}}" data-match3="{{$list->matches}}" data-yellow3="{{$list->yellow_cards}}" data-red3="{{$list->red_cards}}">
				<div class="player"><img src="{{asset('images/flags')}}/{{$list->player['country']['cc']}}.png" alt=""><span>{{$list->player['name']}}</span></div>
				<div class="team"><img src="https://assets.b365api.com/images/team/m/{{$list->team->image}}.png" alt=""><span>{{$list->team->name}}</span></div>
				<div class="pos"><span>{{$list->player['position']}}</span></div>
				<div class="match"><span>{{$list->matches}}</span></div>
				<div class="yellow-card"><span>{{$list->yellow_cards}}</span></div>
				<div class="red-card"><span>{{$list->red_cards}}</span></div>
			</div>
		@endforeach
	</div>
</div>

{{-- pagination --}}
<div class="toplist-pag">
	{{$toplist->links()}}
	@if ($toplist->currentPage() > 1)
		<a href="{{$toplist->previousPageUrl()}}"> < </a>
	@else
		<a> < </a>
	@endif
	@if ($toplist->lastPage() != $toplist->currentPage())
		<a href="{{$toplist->nextPageUrl()}}"> > </a>
	@else
		<a> > </a>
	@endif
</div>
<script type="text/javascript">
	var mixer_bombar = mixitup('.bombar', {
		selectors: {
        control: '[data-mixitup-control]'
    }
	}),
	mixer_assist = mixitup('.assists', {
		selectors: {
        control: '[data-mixitup-control1]'
    }
	}),
	mixer_goalpass = mixitup('.goalpass', {
		selectors: {
        control: '[data-mixitup-control2]'
    }
	}),
	mixer_warnings = mixitup('.warnings', {
		selectors: {
        control: '[data-mixitup-control3]'
    }
	});
</script>