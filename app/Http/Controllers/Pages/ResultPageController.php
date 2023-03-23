<?php

namespace App\Http\Controllers\Pages;

use App\Post;
use App\Country;
use App\Event;
use App\EventView;
use App\EventLineup;
use App\EventTrend;
use App\LeagueTable;
use App\CommentEvent;
use App\Team;
use Auth;
use Carbon\Carbon;
use UploadImage;
use Dan\UploadImage\Exceptions\UploadImageException;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ResultPageController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $events = collect();
        $events[] = Event::select('*')->whereDate('time', Carbon::today())->whereIn('league_id', [1040, 1067, 5708, 5720, 11990])->whereNotIn('time_status', [0, 1])->leftJoin('leagues', 'events.league_id', '=', 'leagues.id')->orderBy('sort_order')->get();

        $events[] = Event::select('*')->leftJoin('leagues', 'events.league_id', '=', 'leagues.id')->leftJoin('countries', 'leagues.country_id', '=', 'countries.id')->whereDate('time', Carbon::today())->whereNotIn('time_status', [0, 1])->whereNotIn('events.league_id', [1040, 1067, 5708, 5720, 11990])->orderBy('countries.sort_order')->orderBy('leagues.sort_order')->orderBy('time')->get();

        $events = $events->collapse();   
        $events = $events->groupBy('league_id');

        $posts_arch = Post::where('type', '2')->orderBy('created_at', 'desc')->take(15)->get();

        return view('layouts.common.results', compact('posts_arch', 'events'));  
    }

    public function matchResult($id)
    {
    		$event_com = Event::where('event_id', $id)->first();
    		$event = EventView::where('event_id', $id)->first();
    		$lineup = EventLineup::where('event_id', $id)->first();
    		$trend = EventTrend::where('event_id', $id)->first();

    		if ($event){    		
    			$table = $event->league->leagueTable;
    		}else{
	    		$url = 'https://api.betsapi.com/v1/event/view?token=10259-gnrWKBgQ6ioYqW&LNG_ID=73&event_id='.$id;
				$ch = curl_init($url);
				curl_setopt($ch, CURLOPT_TIMEOUT, 60);
				curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 60);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				$data = json_decode(curl_exec($ch));
				curl_close($ch);
	    		$resul = $data->results[0];

	    		$event = new EventView();
	    		$event->event_id = $id;
	    		$event->time = $resul->time;
	    		$event->time_status = $resul->time_status;
	    		$event->league_id = $resul->league->id;
	    		
                if ($resul->time_status == 0){
                    $event->timer = '0';
                }else $event->timer = $resul->timer->tm ?? '90';

	    		$event->stats = json_encode($resul->stats ?? [], JSON_UNESCAPED_UNICODE);
	    		$event->events = json_encode($resul->events ?? [], JSON_UNESCAPED_UNICODE);
	    		$event->away_m = json_encode($resul->extra->away_manager ?? [], JSON_UNESCAPED_UNICODE);
	    		$event->home_m = json_encode($resul->extra->home_manager ?? [], JSON_UNESCAPED_UNICODE);
	    		$event->referee = json_encode($resul->extra->referee ?? [], JSON_UNESCAPED_UNICODE);
	    		$event->round = $resul->extra->round ?? 1;
	    		if (isset($resul->extra->stadium_data) || isset($resul->extra->stadium)) {
	    			$event->stadium = json_encode($resul->extra->stadium_data ?? $resul->extra->stadium, JSON_UNESCAPED_UNICODE);
	    		}else {
	    			$event->stadium = json_encode([]);
	    		}
	    		$event->save();

	    		$table = $event->league->leagueTable;		
    		}

    		// lineup
    		if ($lineup){    			
    		}else{
	    		$url = 'https://api.betsapi.com/v1/event/lineup?token=10259-gnrWKBgQ6ioYqW&LNG_ID=73&event_id='.$id;
					$ch = curl_init($url);
					curl_setopt($ch, CURLOPT_TIMEOUT, 60);
					curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 60);
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
					$data = json_decode(curl_exec($ch));
					curl_close($ch);

	    		$lineup = new EventLineup();	    		
	    		$lineup->event_id = $id;
	    		$lineup->home_start = json_encode($data->results->home->startinglineup ?? [], JSON_UNESCAPED_UNICODE);
	    		$lineup->away_start = json_encode($data->results->away->startinglineup ?? [], JSON_UNESCAPED_UNICODE);
	    		$lineup->home_subs = json_encode($data->results->home->substitutes ?? [], JSON_UNESCAPED_UNICODE);
	    		$lineup->away_subs = json_encode($data->results->away->substitutes ?? [], JSON_UNESCAPED_UNICODE);
	    		$lineup->save();	
    		}
    		// trend
    		if ($trend){    			
    		}else{
	    		$url = 'https://api.betsapi.com/v1/event/stats_trend?token=10259-gnrWKBgQ6ioYqW&LNG_ID=73&event_id='.$id;
					$ch = curl_init($url);
					curl_setopt($ch, CURLOPT_TIMEOUT, 60);
					curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 60);
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
					$data = json_decode(curl_exec($ch));
					curl_close($ch);

	    		$trend = new EventTrend();
	    		$trend->event_id = $id;
	    		$trend->goals = json_encode($data->results->goals ?? [], JSON_UNESCAPED_UNICODE);
	    		$trend->yellowcards = json_encode($data->results->yellowcards ?? [], JSON_UNESCAPED_UNICODE);
	    		$trend->redcards = json_encode($data->results->redcards ?? [], JSON_UNESCAPED_UNICODE);
	    		$trend->substitutions = json_encode($data->results->substitutions ?? [], JSON_UNESCAPED_UNICODE);
	    		$trend->save();	
    		}
    		// league table
    		if (!is_null($table)) {
	        $date_dif = $table->updated_at->diffInHours(Carbon::now()); 
    	    }else {
    	        $date_dif = 0;
    	    }
    		if ($table && $date_dif < 4){  			
	  		}else{
	    		$url = 'https://api.betsapi.com/v1/league/table?token=10259-gnrWKBgQ6ioYqW&LNG_ID=73&league_id='.$event->league_id;
					$ch = curl_init($url);
					curl_setopt($ch, CURLOPT_TIMEOUT, 60);
					curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 60);
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
					$data = json_decode(curl_exec($ch));
					curl_close($ch);

    	        if( is_null($data->results)){
    	        	$table=[];
    	        }else{        
    	    		$table = LeagueTable::firstOrNew(['league_id'=>$event->league_id]);
    	    		$table->league_id = $event->league_id;
    	    		$table->start_time = $data->results->season->start_time;
    	    		$table->end_time = $data->results->season->end_time;

                    if(count($data->results->overall->tournaments) > 1){
                        foreach ($data->results->overall->tournaments as $key => $tour) {
                          $overall['table'][$key] = ['name'=>$tour->name, 'rows'=>[]];
                          foreach ($tour->rows as $row) {
                            if (!isset($row->team->id)) continue;
                            $row->team->name = Team::where('id', $row->team->id)->first()->name ?? $row->team->name;
                            $overall['table'][$key]['rows'][] = $row;
                          };
                        }         
                        $table->overall = json_encode($overall, JSON_UNESCAPED_UNICODE);
                    }else {
                        foreach ($data->results->overall->tournaments[0]->rows as &$row) {
                        	if (!isset($row->team->id)) continue;
                          $row->team->name = Team::where('id', $row->team->id)->first()->name ?? $row->team->name;
                        }        
                        $table->overall = json_encode($data->results->overall->tournaments[0]->rows ?? [], JSON_UNESCAPED_UNICODE);
                    }

		    		$table->home = json_encode($data->results->home->tournaments[0]->rows ?? [], JSON_UNESCAPED_UNICODE);
		    		$table->away = json_encode($data->results->away->tournaments[0]->rows ?? [], JSON_UNESCAPED_UNICODE);
		    		$table->season = Carbon::createFromTimestamp($data->results->season->start_time)->format('Y') . '/'. Carbon::createFromTimestamp($data->results->season->end_time)->format('Y');
		    		$table->save();
		    	}
	  		}

    		$data = array();
    		$data['event'] = $event;
    		$data['stats'] = json_decode($event->stats);
    		$data['events'] = json_decode($event->events);
    		$data['away_m'] = json_decode($event->away_m);
    		$data['home_m'] = json_decode($event->home_m);
    		$data['referee'] = json_decode($event->referee);  

    		$data['teams'] = [
    			'home_start' => json_decode($lineup->home_start),
    			'away_start' => json_decode($lineup->away_start),
                'home_subs'  => json_decode($lineup->home_subs),
                'away_subs'  => json_decode($lineup->away_subs)
    		];

    		$data['goals'] = json_decode($trend->goals);
    		$data['yellowcards'] = json_decode($trend->yellowcards);
    		$data['redcards'] = json_decode($trend->redcards);
    		$data['substitutions'] = json_decode($trend->substitutions);

    		$data['lg_table'] = json_decode($table->overall ?? '');

    		// comments
    		$image_user = UploadImage::load('user', '250');
            if($event_com){
                $comments = $event_com->comments;   

                // children
                $com = $comments->groupBy('parent_id');
                $com = $com->map(function ($item, $key) {
                    return $item->sortByDesc('created_at');
                });
            } else $com = null;	

    		// position teams abbr
    		foreach ($data['teams'] as $teams) {
    			foreach ($teams as $team) {
    				switch ($team->pos) {
    					case 'Goalkeeper':
    						$team->pos_ru = 'В';
    					continue 2;
    					case 'Guard':
    						$team->pos_ru = 'В';
    					continue 2;
    					case 'Right back':
    						$team->pos_ru = 'ПЗ';
    					continue 2;
    					case 'Central defender':
    						$team->pos_ru = 'ЦЗ';
    					continue 2;
    					case 'Defender':
    						$team->pos_ru = 'ЦЗ';
    					continue 2;
    					case 'Left back':
    						$team->pos_ru = 'ЛЗ';
    					continue 2;
    					case 'Central midfielder':
    						$team->pos_ru = 'ЦП';
    					continue 2;
    					case 'Midfielder':
    						$team->pos_ru = 'ЦП';
    					continue 2;
    					case 'Forward':
    						$team->pos_ru = 'НП';
    					continue 2;
    					case 'Right winger':
    						$team->pos_ru = 'АПП';
    					continue 2;
    					case 'Left winger':
    						$team->pos_ru = 'АПЛ';
    					continue 2;
    				}
    			}    			
    		}
    		// stats of events зборка
			if (!empty($data['goals'])) {
				foreach ($data['goals'] as $key => $team){
					foreach ($team as $goal) {							
						foreach ($data['events'] as $event) {
							if( (strstr($event->text, ($goal->time_str+1).'\'' ) || strstr($event->text, $goal->time_str.'\'' ) || strstr($event->text, ($goal->time_str-1).'\'' )) && strstr($event->text, 'Goal' )){
								$goal->event = str_replace(['(', ')'], '', strstr($event->text, '('));
							}								
						}
						if ( $key == 'home'){
							$goal->team = 'home';
						}else {
							$goal->team = 'away';
						}
					}
					if(!array_key_exists('home', $data['goals'])){
						$data['goals']->home=[];
					}
					if(!array_key_exists('away', $data['goals'])){
						$data['goals']->away=[];
					}														
				}
			}					
			if (!empty($data['yellowcards'])) {
				foreach ($data['yellowcards'] as $key => $team){
					foreach ($team as $card) {
						foreach ($data['events'] as $event) {
							if( (strstr($event->text, ($card->time_str+1).'\'') || strstr($event->text, $card->time_str.'\'') || strstr($event->text, ($card->time_str-1).'\'')) && strstr($event->text, 'Yello' )){
								$card->event = str_replace(['(', ')'], '', strstr($event->text, '('));
							}
						}
						if ( $key == 'home'){
							$card->team = 'home';
							$card->card = 'yellow';
						}else {
							$card->team = 'away';
							$card->card = 'yellow';
						}
					}														
				}
				if(!array_key_exists('home', $data['yellowcards'])){
					$data['yellowcards']->home=[];
				}
				if(!array_key_exists('away', $data['yellowcards'])){
					$data['yellowcards']->away=[];
				}
			}					
			if (!empty($data['redcards'])) {
				foreach ($data['redcards'] as $key => $team){
					foreach ($team as $card) {
						foreach ($data['events'] as $event) {
							if( (strstr($event->text, ($card->time_str+1).'\'') || strstr($event->text, $card->time_str.'\'') || strstr($event->text, ($card->time_str-1).'\'')) && strstr($event->text, 'Red' )){
								$card->event = str_replace(['(', ')'], '', strstr($event->text, '('));
							}
						}
						if ( $key == 'home'){
							$card->team = 'home';
							$card->card = 'red';
						}else {
							$card->team = 'away';
							$card->card = 'red';
						}
					}														
				}
				if(!array_key_exists('home', $data['redcards'])){
					$data['redcards']->home=[];
				}
				if(!array_key_exists('away', $data['redcards'])){
					$data['redcards']->away=[];
				}
			}					
			$data['stat'] = [
				'goals' => collect(array_merge($data['goals']->home ?? [], $data['goals']->away ?? []))->sortBy('time_str'),
				'cards' => collect(array_merge($data['yellowcards']->home ?? [], $data['yellowcards']->away ?? [], $data['redcards']->home ?? [], $data['redcards']->away ?? []))->sortBy('time_str')
			];

        return view('layouts.common.resultspage.match', compact('data', 'event_com', 'com', 'image_user'));
    }

    // date piker
    protected function datePicker(Request $request)
    {
    	$date = Carbon::parse($request->date);
    	$events = collect();
        $events[] = Event::select('*')->whereDate('time', $date)->whereIn('league_id', [1040, 1067, 5708, 5720, 11990])->whereNotIn('time_status', [0, 1])->leftJoin('leagues', 'events.league_id', '=', 'leagues.id')->orderBy('sort_order')->get();

        $events[] = Event::select('*')->leftJoin('leagues', 'events.league_id', '=', 'leagues.id')->leftJoin('countries', 'leagues.country_id', '=', 'countries.id')->whereDate('time', $date)->whereNotIn('time_status', [0, 1])->whereNotIn('events.league_id', [1040, 1067, 5708, 5720, 11990])->orderBy('countries.sort_order')->orderBy('leagues.sort_order')->orderBy('time')->get();

        $events = $events->collapse(); 

    	if(!$events->isEmpty()){            
            $events = $events->groupBy('league_id');
    	}else{
    		$queryApi = array(
            'token' => '10259-gnrWKBgQ6ioYqW',
            'sport_id' => '1',
            'LNG_ID' => '73',
            'day'=> $date->format('Ymd')
            );
    		if ($date < Carbon::today()){
    			$url = sprintf('https://api.betsapi.com/v2/events/ended?%s', http_build_query($queryApi));
	    	}else if ($date > Carbon::today()){
	    		return response()->json('<span>Результатов еще нету</span>', 200);
	    	}

            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_TIMEOUT, 60);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 60);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $ended = json_decode(curl_exec($ch));
            curl_close($ch);

            if (!isset($ended->pager)){
                $ended->pager->total = 1;
                $ended->pager->per_page = 1;
            }
            $total = ceil($ended->pager->total/$ended->pager->per_page);
            for ($i=1; $i <= $total; $i++) {
                $ch = curl_init($url."&page=$i");
                curl_setopt($ch, CURLOPT_TIMEOUT, 60);
                curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 60);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                $ended = json_decode(curl_exec($ch));
                curl_close($ch);    

                foreach ($ended->results as $val) {
                  $event = Event::firstOrNew(['event_id'=>$val->id]);
                  if ( (in_array($event->time_status, [2,3,4,5,6,7,8,9,99])) && ($event->updated_at > $event->created_at) ) continue;
                  $event->event_id = $val->id;
                  $event->time = $val->time;
                  $event->time_status = $val->time_status;
                  $event->league_id = $val->league->id;
                  $event->home = $val->home->id;
                  $event->away = $val->away->id;
                  if (isset($val->timer)) {
                      $event->timer = $val->timer->tm;
                  }else{
                      $event->timer = '';
                  }
                  if (isset($val->scores)) {
                      $event->scores_home = $val->scores->{'2'}->home;
                      $event->scores_away = $val->scores->{'2'}->away;
                  }else{
                      $event->scores_home = 0;
                      $event->scores_away = 0;
                  }          
                  $event->save();                            
                }
            }
            $events = collect();
            $events[] = Event::select('*')->whereDate('time', $date)->whereIn('league_id', [1040, 1067, 5708, 5720, 11990])->whereNotIn('time_status', [0, 1])->leftJoin('leagues', 'events.league_id', '=', 'leagues.id')->orderBy('sort_order')->get();

            $events[] = Event::select('*')->leftJoin('leagues', 'events.league_id', '=', 'leagues.id')->leftJoin('countries', 'leagues.country_id', '=', 'countries.id')->whereDate('time', $date)->whereNotIn('time_status', [0, 1])->whereNotIn('events.league_id', [1040, 1067, 5708, 5720, 11990])->orderBy('countries.sort_order')->orderBy('leagues.sort_order')->orderBy('time')->get();

            $events = $events->collapse(); 
            $events = $events->groupBy('league_id');
        	}

    	$html = view('layouts.common.resultspage._results', compact('date', 'events'))->render();

    	return response()->json($html, 200);
    }

    //comment
    public function addComment($event_id, Request $request)
    {
        $this->validate($request,[
                'text'  => 'required|string|min:3',
                ]);
        $comment = new CommentEvent();
        $comment->parent_id = $request->parent_id;
        $comment->text = htmlspecialchars($request->text);
        $comment->event_id = $event_id;
        Auth::user()->comments()->save($comment);
        $comment->save();

        //respone
        $data['text'] = htmlspecialchars($request->text);
        $data['created_at'] = Carbon::now()->format('d.m.y, H:i');
        $data['user_name'] = Auth::user()->name;
        $data['user_image'] = UploadImage::load('user', '250').Auth::user()->image;
        $data['id'] = $comment->id;

        $html = view('layouts.common.comments.new_comment')->with('data', $data)->render();

        return response()->json(['html'=>$html, 'parent_id'=>$request->parent_id], 200);
    }

    // like /?dislike
    public function addLikeDislike($event_id, Request $request)
    {
        $comment = CommentEvent::findOrFail($request->com_id);        

        if ( $request->type_like == 1 ){
            $comment->like(Auth::user()->id);
        }
        if ( $request->type_like == 0 ){
            $comment->dislike(Auth::user()->id);
        }
        $count = $comment->likesDiffDislikesCount;

        return response()->json(['types'=>$request->type_like, 'count'=>$count], 200);
    }

}