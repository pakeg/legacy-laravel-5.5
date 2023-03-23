@extends('layouts.main')

@section('content')
	<div class="container" id="main-block">
    <div class="row">
    	{{-- ПРАВАЯ ПАНЕЛЬ--}}
    	<div class="col-md-8 col-8">
    		<div class="right-col" id="match">
    			<div class="title">
    				<div><h4>РЕЗУЛЬТАТ МАТЧА/LIFESCORE</h4></div>
    				<div><span>{{$data['event']->league->name}}</span></div>
    			</div>
    			<div class="info">
    				<div>{{$data['event']->time->format('d.m.y, H:s')}}</div>
    				<div>{{$data['event']->round}}-й Тур</div>
    				<div><strong>{{$data['event']->timer}}'</strong></div>
    			</div>
    			<div class="match">
    				<div>
    					<div><img src="https://assets.b365api.com/images/team/b/{{$data['event']->event->homeT['image']}}.png" alt=""></div>
    					<div><strong>{{$data['event']->event->homeT['name']}}</strong></div>
    					<div><p>{{$data['event']->event->homeT->country->name}}</p></div>
    				</div>
    				<div>
    					<span>{{$data['stats']->goals['0'] ?? '0'}}</span>
    					<span>:</span>
    					<span>{{$data['stats']->goals['1'] ?? '0'}}</span>
    				</div>
    				<div>
    					<div><img src="https://assets.b365api.com/images/team/b/{{$data['event']->event->awayT['image']}}.png" alt=""></div>
    					<div><strong>{{$data['event']->event->awayT['name']}}</strong></div>
    					<div><p>{{$data['event']->event->awayT->country->name}}</p></div>
    				</div>
    			</div>
    			<div class="timeline">
    				<div class="home">
    					<img src="https://assets.b365api.com/images/team/m/{{$data['event']->event->homeT['image']}}.png" alt="">
    					<ul>
    						@php $sum = 90; @endphp
    						@if (isset($data['goals']->home))
    							@foreach ($data['goals']->home as $goal)
    								<li style="left: {{($goal->time_str*100)/$sum}}%"><img data-toggle="tooltip" data-placement="top" title="{{$goal->time_str}}' {{isset($goal->event) ? $goal->event : ''}}" src="{{asset('images/goal.png')}}" alt=""></li>	
    							@endforeach		
    						@endif
    						@if (isset($data['yellowcards']->home))  						
    							@foreach ($data['yellowcards']->home as $card)
    								<li style="left: {{($card->time_str*100)/$sum}}%"><img data-toggle="tooltip" data-placement="top" title="{{$card->time_str}}' {{isset($card->event) ? $card->event : ''}}" src="{{asset('images/yellow-card.png')}}" alt=""></li>	
    							@endforeach		
    						@endif
    						@if (isset($data['redcards']->home))
    							@foreach ($data['redcards']->home as $card)
    								<li style="left: {{($card->time_str*100)/$sum}}%"><img data-toggle="tooltip" data-placement="top" title="{{$card->time_str}}' {{isset($card->event) ? $card->event : ''}}" src="{{asset('images/red-card.png')}}" alt=""></li>	
    							@endforeach		
    						@endif
    					</ul>
    				</div>
    				<div class="time">
    					<div style="width: {{($data['event']->timer*100)/$sum}}%" class="progress"></div>
    					<div class="half">45'</div>
    					<div class="full">90'</div>
    				</div>
    				<div class="away">
    					<img src="https://assets.b365api.com/images/team/m/{{$data['event']->event->awayT['image']}}.png" alt="">
    					<ul>
    						@if (isset($data['goals']->away))
    							@foreach ($data['goals']->away as $goal)
    								<li style="left: {{($goal->time_str*100)/$sum}}%"><img data-toggle="tooltip" data-placement="bottom" title="{{$goal->time_str}}' {{isset($goal->event) ? $goal->event : ''}}" src="{{asset('images/goal.png')}}" alt=""></li>	
    							@endforeach		
    						@endif
    						@if (isset($data['yellowcards']->away))
    							@foreach ($data['yellowcards']->away as $card)
    								<li style="left: {{($card->time_str*100)/$sum}}%"><img data-toggle="tooltip" data-placement="bottom" title="{{$card->time_str}}' {{isset($card->event) ? $card->event : ''}}" src="{{asset('images/yellow-card.png')}}" alt=""></li>	
    							@endforeach		
    						@endif
    						@if (isset($data['redcards']->away))
    							@foreach ($data['redcards']->away as $card)
    								<li style="left: {{($card->time_str*100)/$sum}}%"><img data-toggle="tooltip" data-placement="bottom" title="{{$card->time_str}}' {{isset($card->event) ? $card->event : ''}}" src="{{asset('images/red-card.png')}}" alt=""></li>	
    							@endforeach		
    						@endif
    					</ul>
    				</div>
    			</div>
    			<div class="tabs">
    				<div>					
			        <ul class="nav nav-tabs thead" role="tablist">
								<li class="active">
									<a href="#protocol" class="active" aria-controls="protocol" role="tab" data-toggle="tab">ПРОТОКОЛ
									</a>					
								</li>
								<li>
									<a href="#stream" aria-controls="stream" role="tab" data-toggle="tab">ТРАНСЛЯЦИЯ
									</a>
								</li>
								<li>
									<a href="#comm" aria-controls="comm" role="tab" data-toggle="tab">КОММЕНТАРИИ
									</a>
								</li>
							</ul>
						</div>
						<div class="tab-content">									
							{{-- ПРОТОКОЛ --}}
							<div role="tabpanel" class="tab-pane active" id="protocol">
								@if (!empty($data['stat']['goals']))
									<div class="title_tab"><strong>Голы</strong></div>
									<div class="team_st">
										@foreach ($data['stat']['goals'] as $goal)
											<div class="{{$goal->team}}">
												<div class="team_gl_event"><img src="{{asset('images/goal-s.png')}}" alt=""><span>{{isset($goal->event) ? $goal->event : ''}}</span></div>
												<div class="team_gl_timer">{{$goal->time_str}}'</div>
											</div>
										@endforeach
									</div>
								@endif
								@if (!empty($data['stat']['cards']))
									<div class="title_tab"><strong>Наказания</strong></div>
									<div class="team_st">
										@foreach ($data['stat']['cards'] as $card)
											<div class="{{$card->team}}">
												<div class="team_gl_event"><img src="{{asset('images/')}}/{{$card->card}}.png" alt="">{{isset($card->event) ? $card->event : '' }}</div>
												<div class="team_gl_timer">{{$card->time_str}}'</div>
											</div>
										@endforeach
									</div>
								@endif
								@if(!empty($data['teams']['home_start']) || !empty($data['teams']['away_start']))
									<div class="title_tab"><strong>Составы команд</strong></div>
									<div class="field-match">
										<img class="field" src="{{asset('images/field.jpg')}}" alt="">
										@foreach($data['teams']['home_start'] as $start)
											<div class="home home_{{strtolower(str_replace(' ', '_', $start->pos))}}">
												<div>
													<img src="{{asset('images/forma/')}}/{{($start->pos == 'Goalkeeper' or $start->pos == 'Guard') ? 'home-f-gl' : 'home-f'}}.png" alt="">
													<span>{{$start->shirtnumber}}</span>
												</div>
												<span>{{$start->player->name}}</span>
											</div>
										@endforeach
										@foreach($data['teams']['away_start'] as $start)
											<div class="away away_{{strtolower(str_replace(' ', '_', $start->pos))}}">
												<div>
													<img src="{{asset('images/forma/')}}/{{($start->pos == 'Goalkeeper' or $start->pos == 'Guard') ? 'away-f-gl' : 'away-f'}}.png" alt="">
													<span>{{$start->shirtnumber}}</span>
												</div>
												<span>{{$start->player->name}}</span>
											</div>
										@endforeach
									</div>

									<div class="team_sq">
										<div class="team_home">
											@foreach ($data['teams']['home_start'] as $start)
												<div>
													<span class="shirt-nb">{{$start->shirtnumber}}</span>
													<span class="pos">{{$start->pos_ru}}</span>
													<img src="{{asset('images/flags')}}/{{$start->player->cc}}.png" alt="">
													<span>{{$start->player->name}}</span>
												</div>
											@endforeach
										</div>
										<div class="team_away">
											@foreach ($data['teams']['away_start'] as $start)
												<div>
													<span class="shirt-nb">{{$start->shirtnumber}}</span>
													<span class="pos">{{$start->pos_ru}}</span>
													<img src="{{asset('images/flags')}}/{{$start->player->cc}}.png" alt="">
													<span>{{$start->player->name}}</span>
												</div>
											@endforeach
										</div>
									</div>

									<div class="title_tab"><strong>Запасные</strong></div>
									<div class="team_sq">
										<div class="team_home">
											@foreach ($data['teams']['home_subs'] as $subs)
												<div>
													<span class="pos">{{$subs->shirtnumber}}</span>
													<img src="{{asset('images/flags')}}/{{$subs->player->cc}}.png" alt="">
													<span>{{$subs->player->name}}</span>
												</div>
											@endforeach
										</div>
										<div class="team_away">
											@foreach ($data['teams']['away_subs'] as $subs)
												<div>
													<span class="pos">{{$subs->shirtnumber}}</span>
													<img src="{{asset('images/flags')}}/{{$subs->player->cc}}.png" alt="">
													<span>{{$subs->player->name}}</span>
												</div>
											@endforeach
										</div>
									</div>
								@endif
								@if (!empty($data['home_m']) || !empty($data['away_m']))
									<div class="title_tab"><strong>Главные тренеры</strong></div>
									<div class="team_sq">
										<div class="team_home">
											<div>
												@if (!empty($data['home_m']))
													<img src="{{asset('images/flags')}}/{{$data['home_m']->cc}}.png" alt="">
													<span>{{$data['home_m']->name}}</span>
												@endif
											</div>												
										</div>
										<div class="team_away">
											<div>
												@if (!empty($data['away_m']))
													<img src="{{asset('images/flags')}}/{{$data['away_m']->cc}}.png" alt="">
													<span>{{$data['away_m']->name}}</span>
												@endif
											</div>											
										</div>
									</div>
								@endif
								@if ($data['stats'])
									<div class="title_tab"><strong>Статистика</strong></div>
									<div class="title_stats">
										<div><strong>{{$data['event']->event->homeT['name']}}</strong></div>
										<div><strong>{{$data['event']->event->awayT['name']}}</strong></div>
									</div>
									<div class="stats">
										{{-- Статистика --}}
										@foreach ($data['stats'] as $stats => $stat)
											@php if ($stat[0] == '') $stat[0] = 0; if ($stat[1] == '') $stat[1] = 0; @endphp
											<div>
												{{--  Владение мячем --}}			
												@if ($stats == 'possession_rt')
												<div class="stats-attr">
													<span>{{$stat[0]}}</span>
													<span>Владение мячем(%)</span>
													<span>{{$stat[1]}}</span>
												</div>																			
													<div class="progs-stats">
														<div style="width: {{$stat[0]}}%" class="stats-home"></div>
														<div style="width: {{$stat[1]}}%" class="stats-away"></div>
													</div>
												@else
												{{--  Остальная статистика --}}
													<div class="stats-attr">
														<span>{{$stat[0]}}</span>
															@if ($stats == 'corners')
																<span>Угловые</span>
															@endif
															@if ($stats == 'yellowcards')
																<span>Желтые карточки</span>
															@endif
															@if ($stats == 'redcards')
																<span>Красные карточки</span>
															@endif
															@if ($stats == 'penalties')
																<span>Пенальти</span>
															@endif
															@if ($stats == 'substitutions')
																<span>Замены</span>
															@endif
															@if ($stats == 'attacks')
																<span>Атаки</span>
															@endif
															@if ($stats == 'dangerous_attacks')
																<span>Опасные атаки</span>
															@endif
															@if ($stats == 'on_target')
																<span>По цели</span>
															@endif
															@if ($stats == 'fouls')
																<span>Фолы</span>
															@endif
															@if ($stats == 'goals')
																<span>Голы</span>
															@endif
															@if ($stats == 'corner_f')
																<span>corner_f</span>
															@endif
															@if ($stats == 'corner_h')
																<span>corner_h</span>
															@endif
															@if ($stats == 'offsides')
																<span>Оффсайды</span>
															@endif
															@if ($stats == 'ball_safe')
																<span>Отбитые мячи</span>
															@endif														
															@if ($stats == 'goalattempts')
																<span>Голевые моменты</span>
															@endif
															@if ($stats == 'injuries')
																<span>Травмы</span>
															@endif
															@if ($stats == 'shots_blocked')
																<span>Ударов заблокировано</span>
															@endif
															@if ($stats == 'saves')
																<span>Отбито мячей</span>
															@endif
															@if ($stats == 'off_target')
																<span>Вне цели</span>
															@endif
															@if ($stats == 'yellowred_cards')
																<span>yellowred_cards</span>
															@endif
														<span>{{$stat[1]}}</span>
													</div>
													@if ($stat[0] == '0' && $stat[1] == '0')
														<div class="progs-stats">
														</div>
													@else
														<div class="progs-stats">
															@php $sum = $stat[0]+$stat[1]  @endphp
															<div style="width: {{($stat[0]*100)/$sum}}%" class="stats-home"></div>
															<div style="width: {{($stat[1]*100)/$sum}}%" class="stats-away"></div>
														</div>
													@endif
												@endif
												</div>
										@endforeach
									</div>
								@endif
							</div>

							{{-- ТРАНСЛЯЦИЯ --}}
							<div role="tabpanel" class="tab-pane" id="stream">
								
							</div>

							{{-- КОММЕНТАРИИ --}}
							<div role="tabpanel" class="tab-pane " id="comm">
								<div id="comments-block">
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
                		<button class="sign-in"><img src="{{ asset('images/log-in.png') }}" alt="">Войти</button>
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
					</div>
    		</div>
    	</div>   

    	{{-- ЛЕВАЯ ПАНЕЛЬ--}}   
    	<div class="col-md-4 col-4">
    		<div class="left-col" id="lg-table">
    			<div><h4>ТУРНИРНАЯ ТАБЛИЦА</h4></div>
    			<div class="lg-select">
    				<label class="lg-label" for="lg">
    					<select name="lg-name" id="lg">
    						@foreach($data['event']->league->country->league as $league)
    							@if ($data['event']->league->id == $league->id)
    								<option value="{{$league->id}}" selected>{{$league->name}}</option>
    							@else
    								<option value="{{$league->id}}">{{$league->name}}</option>
    							@endif
    						@endforeach
    					</select>
    				</label>
    			</div>
    			<div class="lg-table">
    				<div class="lg-table-hd">
    					<span class="lg-num">№</span>
    					<span class="lg-team"><strong>Команда</strong></span>
    					<span class="lg-game"><strong>Игр</strong></span>
    					<span class="lg-point"><strong>Очков</strong></span>
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
	    				@if($data['lg_table'] != '')
		    				@if(isset($data['lg_table']->table))
		    					@foreach($data['lg_table']->table as $team)
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
			    				@foreach ($data['lg_table'] as $key => $team)
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
		    		</div>
    			</div>
    			<div class="lg-butt">
    				<button><a href="{{route('lg-table', $data['event']->league->id)}}">Подробная таблица</a></button>
    			</div>
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
            url: '{{route('rst-addcom', $event_com->id)}}',
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
            url: '{{route('rst-likedis', $event_com->id)}}',
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
			data: 'id='+id+'&table=2',
			success: function(json){
				$('.loader').hide();
				$('.lg-table-stats').html(json.html);
				$('.lg-butt a').attr('href', '{{url('table')}}/'+json.tb_id);
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