@extends('layouts.main')

@section('content')
	<div class="container" id="main-block">
        <div class="row">
        	{{-- ПРАВАЯ ПАНЕЛЬ--}}
          <div class="col-md-8 col-8">
          	<div class="right-col" id="lg-table">
          		<div class="title">
          			<div class="">
          				<h4>ТУРНИРНАЯ ТАБЛИЦА</h4>
          			</div>
          			<div class="leage-select">
									<label class="leage-label" for="lg">
										<select name="lg-name" id="lg">
											@foreach($table->league->country->league as $league)
			    							@if ($table->league_id == $league->id)
			    								<option value="{{$league->id}}" selected>{{$league->name}}</option>
			    							@else
			    								<option value="{{$league->id}}">{{$league->name}}</option>
			    							@endif
			    						@endforeach
										</select>
									</label>
								</div>
          		</div>          		
          		<div class="lg-table">
		    				<div class="lg-table-hd leage-hd">
		    					<span class="lg-num">№</span>
		    					<span class="leage-name"><strong>Команда</strong></span>
		    					<span class="leage-game"><strong>И</strong></span>
		    					<span class="leage-win"><strong>В</strong></span>
		    					<span class="leage-draw"><strong>Н</strong></span>
		    					<span class="leage-lose"><strong>П</strong></span>
		    					<span class="leage-goal"><strong>Г</strong></span>
		    					<span class="leage-point"><strong>О</strong></span>
		    					<span class="leage-forma"><strong>Форма</strong></span>
		    				</div>
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
		    				<div class="lg-table-stats">
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
					    	</div>	    				
		    			</div>
		    			<div class="conditional">Условные обозначения</div>
		    			<div class="row">
			    			<div class="col-md-6 col-6 rede">
			    				<div><div class="lg-chemp"></div> <span>Лига Чемпионов</span></div>
			    				<div><div class="lg-chemp-up"></div> <span>Квалификация Лиги Чемпионов</span></div>
			    				<div><div class="lg-uefa"></div> <span>UEFA Лига Европы. Квалификация</span></div>
			    				<div><div class="lg-play-off"></div> <span>Плей-офф на вылет</span></div>
			    				<div><div class="lg-gab"></div> <span>Вылет</span></div>
			    			</div>
			    			<div class="col-md-6 col-6 lg-form rede">
			    				<div><div class="win">В</div> <span>Выигранный матч</span></div>
			    				<div><div class="draw">Н</div> <span>Ничья</span></div>
			    				<div><div class="lose">П</div> <span>Проигранный матч</span></div>
			    				<div><div class="cancel">-</div> <span>Матч еще не состоялся</span></div>
			    			</div>
		    			</div>
          	</div>
          </div>

          {{-- ЛЕВАЯ ПАНЕЛЬ--}}
          <div class="col-md-4 col-4">
          	<div class="left-col" id="menu" >
          		<div class="title">
          			<h4>МЕНЮ</h4>
          		</div>
          		<div class="comp">
          			@foreach ($cc as $cc)
          				<div>
          					<img src="{{asset('images/flags/')}}/{{$cc->cc}}.png" alt="">
          					<a href="{{route('cc-livescore', ['id'=>$cc->id, 'name'=>str_slug($cc->name)])}}"><strong>{{$cc->name}}</strong></a>
          				</div>
          			@endforeach
          		</div>
          	</div>          	
          </div>
        </div>
    </div>
<script type="text/javascript">
	//table picker
	$('#lg').change(function(){
		var id = $(this).val(),
		id_old = localStorage.getItem('id_old');

		if (id == id_old ) return false;
		if (id == '0') return false;
		$('.lg-table-stats').empty();
		$('.loader').show();

		$.ajax({
			headers: {
        'X-CSRF-TOKEN': '{{ csrf_token() }}'
      },
			type: 'post',
			url: '{{route('tablePicker')}}',
			dataType: 'json',
			data: 'id='+id+'&table=1',
			success: function(json){
				$('.loader').hide();
				$('.lg-table-stats').html(json.html);
				localStorage.setItem('id_old', id_old);
				if(json.error){
					$('.lg-table-stats').html(json.error);
				}				
			},
			error: function(xhr, ajaxOptions, thrownError) {
				$('.loader').hide();
			  $('.lg-table-stats').html('<span class="error">Произошла ошибка попробуйте позже.</span>');
			}
		});
	});
</script>
@endsection