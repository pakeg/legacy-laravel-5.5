@if ($table_num == '1')
	@if(isset($tables->table))
		@foreach($tables->table as $team)
			<h4 class="group-table">{{$team->name}}</h4>
			@foreach ($team->rows as $key => $team)
				<div class="lg-table-tm leage-tm">
					<span class="lg-num">{{$key+1}}</span>
					<span class="leage-name">{{$team->team->name}}</span>
					<span class="leage-game">{{$team->win+$team->draw+$team->loss}}</span>
					<span class="leage-win">{{$team->win}}</span>
					<span class="leage-draw">{{$team->draw}}</span>
					<span class="leage-lose">{{$team->loss}}</span>
					<span class="leage-goal">{{$team->goalsfor}}:{{$team->goalsagainst}}</span>
					<span class="leage-point">{{$team->points}}</span>
					<div class="lg-form">
						@if(!empty($team->history))
  						@foreach ($team->history as $ev)	
    						@if($ev->forma == 'Матч еще не состоялся')
    							<div class="tooltips">
    								<span class="cancel">-</span>
									  <div class="tooltiptexts">
													<span>{{$ev->homeT['name']}}</span>
													<span>{{$ev->scores_home}}:{{$ev->scores_away}}</span>
													<span>{{$ev->awayT['name']}}</span>
									  </div>
									</div>
    						@elseif ($ev->forma == 'loose')							    							
    							<div class="tooltips">
    								<span class="lose">П</span>
									  <div class="tooltiptexts">
													<span>{{$ev->homeT['name']}}</span>
													<span>{{$ev->scores_home}}:{{$ev->scores_away}}</span>
													<span>{{$ev->awayT['name']}}</span>
									  </div>
									</div>	
    						@elseif ($ev->forma == 'draw')							    							
    							<div class="tooltips">
    								<span class="draw">Н</span>	
									  <div class="tooltiptexts">
													<span>{{$ev->homeT['name']}}</span>
													<span>{{$ev->scores_home}}:{{$ev->scores_away}}</span>
													<span>{{$ev->awayT['name']}}</span>
									  </div>
									</div>
    						@elseif ($ev->forma == 'win')							    							
    							<div class="tooltips">
    								<span class="win">В</span>	
									  <div class="tooltiptexts">
													<span>{{$ev->homeT['name']}}</span>
													<span>{{$ev->scores_home}}:{{$ev->scores_away}}</span>
													<span>{{$ev->awayT['name']}}</span>
									  </div>
									</div>
    						@endif
							@endforeach
						@endif
					</div>        					        					
				</div>
			@endforeach
		@endforeach
	@else
		@foreach($tables as $key => $team)
			<div class="lg-table-tm leage-tm">
				<span class="lg-num">{{$key+1}}</span>
				<span class="leage-name">{{$team->team->name}}</span>
				<span class="leage-game">{{$team->win+$team->draw+$team->loss}}</span>
				<span class="leage-win">{{$team->win}}</span>
				<span class="leage-draw">{{$team->draw}}</span>
				<span class="leage-lose">{{$team->loss}}</span>
				<span class="leage-goal">{{$team->goalsfor}}:{{$team->goalsagainst}}</span>
				<span class="leage-point">{{$team->points}}</span>
				<div class="lg-form">
					@if(!empty($team->history))
						@foreach ($team->history as $ev)	
  						@if($ev->forma == 'Матч еще не состоялся')
  							<div class="tooltips">
  								<span class="cancel">-</span>
								  <div class="tooltiptexts">
												<span>{{$ev->homeT['name']}}</span>
												<span>{{$ev->scores_home}}:{{$ev->scores_away}}</span>
												<span>{{$ev->awayT['name']}}</span>
								  </div>
								</div>
  						@elseif ($ev->forma == 'loose')							    							
  							<div class="tooltips">
  								<span class="lose">П</span>
								  <div class="tooltiptexts">
												<span>{{$ev->homeT['name']}}</span>
												<span>{{$ev->scores_home}}:{{$ev->scores_away}}</span>
												<span>{{$ev->awayT['name']}}</span>
								  </div>
								</div>	
  						@elseif ($ev->forma == 'draw')							    							
  							<div class="tooltips">
  								<span class="draw">Н</span>	
								  <div class="tooltiptexts">
												<span>{{$ev->homeT['name']}}</span>
												<span>{{$ev->scores_home}}:{{$ev->scores_away}}</span>
												<span>{{$ev->awayT['name']}}</span>
								  </div>
								</div>
  						@elseif ($ev->forma == 'win')							    							
  							<div class="tooltips">
  								<span class="win">В</span>	
								  <div class="tooltiptexts">
												<span>{{$ev->homeT['name']}}</span>
												<span>{{$ev->scores_home}}:{{$ev->scores_away}}</span>
												<span>{{$ev->awayT['name']}}</span>
								  </div>
								</div>
  						@endif
						@endforeach
					@endif
				</div>       					        					
			</div>
		@endforeach	
	@endif
@else
	@if($tables != '')
		@if(isset($tables->table))
			@foreach($tables->table as $team)
				<h4 class="group-table">{{$team->name}}</h4>
				@foreach ($team->rows as $key => $team)
					<div class="lg-table-tm">    					
						<span class="lg-num">{{$key+1}}</span>
						<span class="lg-team">{{$team->team->name}}</span>
						<span class="lg-game">{{$team->win+$team->draw+$team->loss}}</span>
						<span class="lg-point">{{$team->points}}</span>
					</div>
				@endforeach
			@endforeach
		@else
			@foreach ($tables as $key => $team)
				<div class="lg-table-tm">    					
					<span class="lg-num">{{$key+1}}</span>
					<span class="lg-team">{{$team->team->name}}</span>
					<span class="lg-game">{{$team->win+$team->draw+$team->loss}}</span>
					<span class="lg-point">{{$team->points}}</span>
				</div>
			@endforeach
		@endif  
	@else
		<span>Данных еще нету</span>
	@endif
@endif