@extends('layouts.main')

@section('content')
	<div class="container" id="main-block">
        <div class="row">
        @if ($transfers)
        	<div id="transfers" class="right-col"> 
        		<div class="title">
        			<div><h4>ТРАНСФЕРЫ</h4></div>
        			<div class="tr-select">
		    				<label class="tr-label" for="tr">
		    					<select name="tr-name" id="tr" onchange="window.location = this.value;return false;">
		    						<option value="{{route('transfers')}}">-- Не выбрано --</option>
		    						<option value="{{route('transfers') . '?sortBy=start&orderBy=desc'}}" {{Request::query('sortBy') == 'start' ? 'selected' : ''}}>Последние трансферы</option>
		    						<option value="{{route('transfers') . '?sortBy=cost&orderBy=desc'}}" {{ (Request::query('sortBy') == 'cost') && ( Request::query('orderBy') == 'desc' ) ? 'selected' : ''}}>Стоимость>больше</option>
		    						<option value="{{route('transfers') . '?sortBy=cost&orderBy=asc'}}" {{ (Request::query('sortBy') == 'cost') && ( Request::query('orderBy') == 'asc' ) ? 'selected' : ''}}>Стоимость>меньше</option>
		    						<option value="{{route('transfers') . '?sortBy=top&orderBy=desc'}}" {{Request::query('sortBy') == 'top' ? 'selected' : ''}}>Топ трансферы</option>
		    					</select>
		    				</label>
		    			</div>		    			
        		</div>
        		<div class="transfers">
	    				<div class="tr-head">
	    					<div class="tr-name">Игрок</div>
	    					<div class="tr-age">Возраст</div>
	    					<div class="tr-ct">Нац.</div>
	    					<div class="tr-to">Откуда</div>
	    					<div class="tr-from">Куда</div>
	    					<div class="tr-date">Дата</div>
	    					<div class="tr-price">Стоимость</div>
	    					<div class="tr-state">Состояние</div>
	    				</div>
	    				@foreach ($transfers as $transfer)
		    				<div class="transfer">
		    					<div class="tr-name">{{$transfer->player->name}}</div>
		    					<div class="tr-age">{{$transfer->player->birthdate->diffInYears($date)}}</div>
		    					<div class="tr-ct"><img src="{{asset('images/flags')}}/{{$transfer->player->country->cc}}.png" alt=""></div>
		    					<div class="tr-flex tr-from">
		    						<div>
		    							<img src="https://assets.b365api.com/images/team/m/{{$transfer->teamFrom->image}}.png" alt="">
		    						</div>
		    						<div class="tr-city">
		    							<span>{{$transfer->teamFrom->name}}</span>
		    							<div>
		    								<img src="{{asset('images/flags')}}/{{$transfer->teamFrom->country->cc}}.png" alt="">
		    								<span>{{$transfer->teamFrom->country->name}}</span>
		    							</div>
		    						</div>
		    					</div>
		    					<div class="tr-flex tr-to">
		    						<div>
		    							<img src="https://assets.b365api.com/images/team/m/{{$transfer->teamTo->image}}.png" alt="">
		    						</div>
		    						<div class="tr-city">
		    							<span>{{$transfer->teamTo->name}}</span>
		    							<div>
		    								<img src="{{asset('images/flags')}}/{{$transfer->teamTo->country->cc}}.png">
		    								<span>{{$transfer->teamTo->country->name}}</span>
		    							</div>
		    						</div>
		    					</div>
		    					<div class="tr-date">{{!is_null($transfer->start)?$transfer->start->format('d.m.y'): ''}}</div>
		    					<div class="tr-price">{{$transfer->cost . '$'}}</div>
		    					<div class="tr-state">Свободн.</div>
		    				</div>
		    			@endforeach		    						    			
		    		</div>
		    		<div class="trans-pag">
			    		{{$transfers->links()}}
			    		@if ($transfers->currentPage() > 1)
								<a href="{{$transfers->previousPageUrl()}}"> <i class="fas fa-chevron-left"></i> </a>
							@else
								<a> <i class="fas fa-chevron-left"></i> </a>
							@endif
							@if ($transfers->lastPage() != $transfers->currentPage())
								<a href="{{$transfers->nextPageUrl()}}"> <i class="fas fa-chevron-right"></i> </a>
							@else
								<a> <i class="fas fa-chevron-right"></i> </a>
							@endif
	      		</div>
        	</div>
        @endif
        </div>
    </div>
@endsection