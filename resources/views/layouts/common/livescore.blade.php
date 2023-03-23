@extends('layouts.main')

@section('content')
	<div class="container" id="main-block">
		@if(session('flash'))
			<div class="flash-green">{{session('flash')}}</div>
  	@endif
    <div class="row">
    	{{-- ПРАВАЯ ПАНЕЛЬ --}}        	
      <div class="col-md-8 col-8">
      	<div class="tabs right-col" id="live">
  				<div class="tab-title">					
		        <ul class="nav nav-tabs thead" role="tablist">
							<li class="active">
								<a href="#date" class="active"  aria-controls="date" role="tab" data-toggle="tab"><h4>КАЛЕНДАРЬ ИГР</h4>
								</a>
							</li>
						</ul>
					</div>
					<div class="tab-content">
						{{-- КАЛЕНДАРЬ ИГР--}}
						<div role="tabpanel" class="tab-pane active" id="date">
							<div class="live-select">
								<div class="date"><input type="text" id="datepicker" placeholder="Дата" name="date"></div>
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
						</div>
					</div>
				</div>
				@if (Route::is('cc-livescore'))
					{{-- ПРАВАЯ ПАНЕЛЬ след. блок --}}		
					@if ($tables)	
						<div class="right-col" id="lg-table">
          		<div class="title">
          			<div class="">
          				<h4>ТУРНИРНАЯ ТАБЛИЦА</h4>
          			</div>
          			@if ($table)
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
								@endif
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
		    				<div class="lg-loader">
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
          @endif
      	@endif
      </div>

      {{-- ЛЕВАЯ ПАНЕЛЬ --}}
      <div class="col-md-4 col-4">
      	@if ($news)
        	<div id="archive" class="left-col">
						<div class="arh-title"><h4>НОВОСТИ. {{$tag->name ?? ''}}</h4></div>								
							@foreach ($news as $post)
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
				@endif
				
				{{-- ЛЕВАЯ ПАНЕЛЬ след. блок--}}
				@if ($articles_slider)
					<div id="archive" class="left-col archive">
						<div class="arh-title"><h4>СТАТЬИ. {{$tag->name ?? ''}}</h4></div>							
							@foreach ( $articles_slider as $key => $block)
								@if ( $key == 0 )	@php $class = ' actives'; @endphp @else 	@php $class = ''; @endphp @endif
								<div class="block{{ $class }}" data-id="{{$key}}">
									@foreach ( $block as $post)
										<div class="blc-post blc-new">
											<div class="blc-time">
												<img src="{{$thumb}}{{!empty($post->image) ? $post->image : 'no_image.jpg'}}" alt="">
											</div>
											<div class="blc-cont blc-cont-news">
												<div>
													<a href="{{url('news/')}}/{{$post->id}}">{{$post->title}}</a>
												</div>
												<div>
													<span>{{$post->created_at->format('d.m.y')}}</span>
													<div><i class="fa fa-eye" aria-hidden="true"></i>{{$post->viewed}}</div>
													<div><i class="fa fa-comments" aria-hidden="true"></i>{{$post->comments->count()}}</div>
												</div>
											</div>
										</div>
									@endforeach
								</div>
							@endforeach
							<div class="post-nav">
								<div>
									<ul>@php $row = count($articles_slider); @endphp
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
					</div>
				@endif
      </div>
    </div>

    {{-- TOPLIST --}}
    @if ($toplist)
	    <div class="row">
				<div class="col-md-12 col-12">
	  			<div id="toplist" class="right-col">
	  				<div class="title">
	  					<h4>СТАТИСТИКА</h4>
	  					<div class="leage-select">
								<label class="leage-label" for="lg_top">
									<select name="lg-name" id="lg_top">
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
	  				<div class="tabs">
	  					<div class="controls">					
				        <ul class="nav nav-tabs thead" role="tablist">
									<li class="active">
										<a href="#bombar" class="active" aria-controls="bombar" role="tab" data-toggle="tab">Бомбардиры
										</a>					
									</li>
									<li>
										<a href="#assists" aria-controls="assists" role="tab" data-toggle="tab">Передачи
										</a>
									</li>
									<li>
										<a href="#goalpass" aria-controls="goalpass" role="tab" data-toggle="tab">Гол+пас
										</a>
									</li>
									<li>
										<a href="#warnings" aria-controls="warnings" role="tab" data-toggle="tab">Предупреждения
										</a>
									</li>
								</ul>
							</div>
							<div class="lg-loader">
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
							<div class="tab-content">

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
										<a href="{{$toplist->previousPageUrl()}}"> <i class="fas fa-chevron-left"></i> </a>
									@else
										<a> <i class="fas fa-chevron-left"></i> </a>
									@endif
									@if ($toplist->lastPage() != $toplist->currentPage())
										<a href="{{$toplist->nextPageUrl()}}"> <i class="fas fa-chevron-right"></i> </a>
									@else
										<a> <i class="fas fa-chevron-right"></i> </a>
									@endif
				    		</div>
			  			</div>
		  			</div>	  				
	  			</div>
	  		</div>
	  	</div>
	  @endif
  </div>
<script src="{{ asset('js/jquery-ui.min.js') }}"></script>
<script src="{{ asset('js/mixitup.min.js') }}"></script>
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
	var date = new Date();
	if (date.getMonth()+1 < 10){
		m = '.0'+parseInt(date.getMonth()+1);
	}
	localStorage.setItem('date_old', date.getDate()+m+'.'+date.getFullYear());
	$( "#datepicker" ).val(date.getDate()+m+'.'+date.getFullYear());
	jQuery(function(e){e.datepicker.regional.ru={closeText:"Закрыть",prevText:'<i class="fas fa-caret-left"></i>',nextText:'<i class="fas fa-caret-right"></i>',currentText:"Сегодня",monthNames:["Январь","Февраль","Март","Апрель","Май","Июнь","Июль","Август","Сентябрь","Октябрь","Ноябрь","Декабрь"],monthNamesShort:["Январь","Февраль","Март","Апрель","Май","Июнь","Июль","Август","Сентябрь","Октябрь","Ноябрь","Декабрь"],dayNames:["воскресенье","понедельник","вторник","среда","четверг","пятница","суббота"],dayNamesShort:["вск","пнд","втр","срд","чтв","птн","сбт"],dayNamesMin:["Вс","Пн","Вт","Ср","Чт","Пт","Сб"],weekHeader:"Нед",dateFormat:"dd.mm.yy",firstDay:1,isRTL:!1,showMonthAfterYear:!1,yearSuffix:""},e.datepicker.setDefaults(e.datepicker.regional.ru)});
	$( function(){
		$( "#datepicker" ).datepicker({
			showAnim: 'slideDown',
			showOtherMonths: true,
      selectOtherMonths: true
		});
		setTimeout(function(){
			$('.flash-green').fadeOut().remove();
		}, 2500);
	});
</script>
<script type="text/javascript">
	//date picker
	$("#date").on('change', '#datepicker', function(){
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
			url: '{{route('l-datePicker')}}',
			dataType: 'json',
			data: 'date='+date,
			success: function(json){
				$('.loader').hide();
				$('#date').html(json);
				localStorage.setItem('date_old', date);
			},
			error: function(xhr, ajaxOptions, thrownError) {
				$('.loader').hide();
			  $('.matches-ajax').html('<span class="error">Произошла ошибка попробуйте позже.</span>');
			}
		});
	});

	//team picker
	$( "#date" ).on('change', '#lg-team', function(){
		var team = $(this).val(),
		date 	 = $("#datepicker").val(),
		team_old = localStorage.getItem('team_old');

		if (team == team_old ) return false;
		if (team == '0') return false;
		$('.matches-ajax').empty();
		$('.loader').show();

		$.ajax({
			headers: {
        'X-CSRF-TOKEN': '{{ csrf_token() }}'
      },
			type: 'post',
			url: '{{route('tm-Picker')}}',
			dataType: 'json',
			data: 'team='+team+'&date='+date,
			success: function(json){
				$('.loader').hide();
				$('#date').html(json);
				localStorage.setItem('team_old', team_old);
				if (json.erorr) {
					$('#date').html(json.erorr);
					setTimeout(function (){						
						location.reload();
					}, 2500);					
				}
			},
			error: function(xhr, ajaxOptions, thrownError) {
				$('.loader').hide();
			  $('.matches-ajax').html('<span class="error">Произошла ошибка попробуйте позже.</span>');
			}
		});
	});
	
	//leage picker
	$( "#date" ).on('change', '#lg-league', function(){
		var id = $(this).val(),
		date 	 = $("#datepicker").val(),
		id_old = localStorage.getItem('id_old');

		if (id == id_old ) return false;
		if (id == '0') return false;
		$('.matches-ajax').empty();
		$('.loader').show();

		$.ajax({
			headers: {
        'X-CSRF-TOKEN': '{{ csrf_token() }}'
      },
			type: 'post',
			url: '{{route('lg-Picker')}}',
			dataType: 'json',
			data: 'id='+id+'&date='+date,
			success: function(json){
				$('.loader').hide();
				$('.matches-ajax').html(json);
				localStorage.setItem('id_old', id_old);
			},
			error: function(xhr, ajaxOptions, thrownError) {
				$('.loader').hide();
			  $('.matches-ajax').html('<span class="error">Произошла ошибка попробуйте позже.</span>');
			}
		});
	});

	//table picker
	$('#lg').change(function(){
		var id = $(this).val(),
		id_old = localStorage.getItem('id_old');

		if (id == id_old ) return false;
		if (id == '0') return false;
		$('.lg-table-stats').empty();
		$('.lg-loader').show();

		$.ajax({
			headers: {
        'X-CSRF-TOKEN': '{{ csrf_token() }}'
      },
			type: 'post',
			url: '{{route('tablePicker')}}',
			dataType: 'json',
			data: 'id='+id+'&table=1',
			success: function(json){
				$('.lg-loader').hide();
				$('.lg-table-stats').html(json.html);
				localStorage.setItem('id_old', id_old);
				if(json.error){
					$('.lg-table-stats').html(json.error);
				}				
			},
			error: function(xhr, ajaxOptions, thrownError) {
				$('.lg-loader').hide();
			  $('.lg-table-stats').html('<span class="error">Произошла ошибка попробуйте позже.</span>');
			}
		});
	});

	@if (Route::is('cc-livescore'))
		//toplist league picker
		$('#lg_top').change(function(){
			var id = $(this).val(),
			id_old_top = localStorage.getItem('id_old_top');
			console.log($(this).text());
			if (id == id_old_top ) return false;
			if (id == '0') return false;
			$('#toplist .tab-content').empty();
			$('#toplist .lg-loader').show();

			$.ajax({
				headers: {
	        'X-CSRF-TOKEN': '{{ csrf_token() }}'
	      },
				type: 'post',
				url: '{{route('toplist-Picker', ['id'=>' ', 'name'=>' '])}}',
				dataType: 'json',
				data: 'id='+id,
				success: function(json){
					$('#toplist .lg-loader').hide();
					$('#toplist .tab-content').html(json);
					localStorage.setItem('id_old_top', id_old_top);				
				},
				error: function(xhr, ajaxOptions, thrownError) {
					$('#toplist .lg-loader').hide();
				  $('#toplist .tab-content').html('<span class="error">Произошла ошибка попробуйте позже.</span>');
				}
			});
		});
	@endif
</script>
@endsection