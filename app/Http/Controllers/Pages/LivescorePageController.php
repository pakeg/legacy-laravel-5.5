<?php

namespace App\Http\Controllers\Pages;

use App\Post;
use App\Tag;
use App\Event;
use App\League;
use App\LeagueTable;
use App\Country;
use App\Team;
use App\ToplistGoal;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use UploadImage;
use Dan\UploadImage\Exceptions\UploadImageException;

class LivescorePageController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $events = collect();
        $events[] = Event::select('*')->whereDate('time', Carbon::today())->whereIn('league_id', [1040, 1067, 5708, 5720, 11990])->leftJoin('leagues', 'events.league_id', '=', 'leagues.id')->orderBy('sort_order')->get();

        $events[] = Event::select('*')->leftJoin('leagues', 'events.league_id', '=', 'leagues.id')->leftJoin('countries', 'leagues.country_id', '=', 'countries.id')->whereDate('time', Carbon::today())->whereNotIn('events.league_id', [1040, 1067, 5708, 5720, 11990])->orderBy('countries.sort_order')->orderBy('leagues.sort_order')->orderBy('time_status', 'desc')->orderBy('time')->get();

        $events = $events->collapse();
        $events = $events->groupBy('league_id');

        $news = Post::where('type', '2')->orderBy('created_at', 'desc')->take(15)->get();
        $articles = Post::where('type', '1')->orderBy('created_at', 'desc')->take(30)->get();
        $articles_slider = $articles->chunk(6);

        $thumb = UploadImage::load('post', '250');
        $toplist = false;
        return view('layouts.common.livescore', compact('news', 'articles_slider', 'thumb', 'events', 'toplist'));
    }

    //livescore 
    public function ccLivescore($id, Request $request)
    {      
      if ($request->query('league') == $id){
        $league = League::find($id);        
        if ( $id == 1040){
          $id = [(int)$id, 5708];
        }else if ($id == 1067) {
          $id = [(int)$id, 5720];
        }
        $id = [(int) $id];
        $events = Event::select('*')->whereDate('time', Carbon::today())->whereIn('league_id', $id)->leftJoin('leagues', 'events.league_id', '=', 'leagues.id')->orderBy('sort_order')->get();
        
        //tags
        $tag = Tag::where('name', 'like', $league->name)->first();
        // table 
        $id_league = $league->id;        

        $events = $events->groupBy('league_id');

        $toplist = false;

      }else{
        $cc = Country::find($id);
        $id = $id;
        $leagues = $cc->league;
        $events = collect();

        foreach ($leagues as $league) {
          $events[] = $league->event;
        }
        $events = $events->map(function ($item, $key) {
          return $item->where('time', '>', Carbon::today());
        })->filter(function ($item, $key) {
          return !$item->isEmpty();
        });

        //tags
        $tag = Tag::where('name', $cc->name)->first();
        
        // table 
        $id_league = $leagues->first()->id; 
        if ($request->query('page')) $id_league = session('league_id');
       
        //toplist country->league
        $toplist = ToplistGoal::select('toplist_goals.*', 'assists', 'red_cards', 'yellow_cards')->where('league_id', $id_league)->leftJoin('toplist_assists', 'toplist_assists.player_id', '=', 'toplist_goals.player_id')->leftJoin('toplist_cards', 'toplist_cards.player_id', '=', 'toplist_goals.player_id')->paginate(30);
      }

      $table = LeagueTable::where('league_id', $id_league)->first();
      $thumb = UploadImage::load('post', '250');

      if (!is_null($tag)){
        $news = $tag->posts->where('type', 2)->take(15);
        $articles_slider = $tag->posts->where('type', 1)->take(30)->chunk(6);
      }else {
        $news = false;
        $articles_slider = false;
      } 

      if (!is_null($table)) {
        $date_dif = $table->updated_at->diffInHours(Carbon::now()); 
      }else {
        $date_dif = 0;
      }
      if ($table && $date_dif < 4){       
        $tables = $this->forma(json_decode($table->overall));        
      }else{
        $tables = $this->newTable($id_league);
        $table = $tables;
        if ($tables === false){
          $tables = false;
        }else {
          $tables = $this->forma(json_decode($tables->overall));
        }
      }

      return view('layouts.common.livescore', compact('news', 'articles_slider', 'thumb', 'events','tag', 'tables', 'table', 'toplist'));
    }

    // forma
    private function forma($tables = array())
    {
      if (isset($tables->table)){        
        foreach ($tables->table as $table){
          foreach ($table->rows as &$team) {
          $h_e = Event::where('home', $team->team->id)->orderBy('time', 'desc')->take(5)->get();
          if (!$h_e->isEmpty()){
            foreach ($h_e as $ev) {
              if (in_array($ev->time_status, [0,1,2,4,5,6,7,8,9,99])){ $ev->forma = 'Матч еще не состоялся';}elseif ($ev->scores_home > $ev->scores_away){
                $ev->forma = 'win';
              }elseif ($ev->scores_home == $ev->scores_away){
                $ev->forma = 'draw';
              }elseif ($ev->scores_home < $ev->scores_away){
                $ev->forma = 'loose';
              }
            }
          }else $h_e == [];
          $a_e = Event::where('away', $team->team->id)->orderBy('time', 'desc')->take(5)->get();
          if(!$a_e->isEmpty()){
            foreach ($a_e as $ev) {
              if (in_array($ev->time_status, [0,1,2,4,5,6,7,8,9,99])){ $ev->forma = 'Матч еще не состоялся';}elseif ($ev->scores_home > $ev->scores_away){
                $ev->forma = 'loose';
              }elseif ($ev->scores_home == $ev->scores_away){
                $ev->forma = 'draw';
              }elseif ($ev->scores_home < $ev->scores_away){
                $ev->forma = 'win';
              }         
            }
          }else $a_e = [];

          $team->history = collect([$h_e, $a_e])->collapse()->sortByDesc('time')->take(5)->all();
          }
        }
      }
      else {
        foreach ($tables as $team) {
          $h_e = Event::where('home', $team->team->id)->orderBy('time', 'desc')->take(5)->get();
          if (!$h_e->isEmpty()){
            foreach ($h_e as $ev) {
              if (in_array($ev->time_status, [0,1,2,4,5,6,7,8,9,99])){ $ev->forma = 'Матч еще не состоялся';}elseif ($ev->scores_home > $ev->scores_away){
                $ev->forma = 'win';
              }elseif ($ev->scores_home == $ev->scores_away){
                $ev->forma = 'draw';
              }elseif ($ev->scores_home < $ev->scores_away){
                $ev->forma = 'loose';
              }
            }
          }else $h_e == [];
          $a_e = Event::where('away', $team->team->id)->orderBy('time', 'desc')->take(5)->get();
          if(!$a_e->isEmpty()){
            foreach ($a_e as $ev) {
              if (in_array($ev->time_status, [0,1,2,4,5,6,7,8,9,99])){ $ev->forma = 'Матч еще не состоялся';}elseif ($ev->scores_home > $ev->scores_away){
                $ev->forma = 'loose';
              }elseif ($ev->scores_home == $ev->scores_away){
                $ev->forma = 'draw';
              }elseif ($ev->scores_home < $ev->scores_away){
                $ev->forma = 'win';
              }         
            }
          }else $a_e = [];

          $team->history = collect([$h_e, $a_e])->collapse()->sortByDesc('time')->take(5)->all();
        }
      }
      return $tables;      
    }

    //date picker
    protected function datePicker(Request $request)
    {
        $date = Carbon::parse($request->date);        
        $events = collect();
        $events[] = Event::select('*')->whereDate('time', $date)->whereIn('league_id', [1040, 1067, 5708, 5720, 11990])->leftJoin('leagues', 'events.league_id', '=', 'leagues.id')->orderBy('sort_order')->get();

        $events[] = Event::select('*')->leftJoin('leagues', 'events.league_id', '=', 'leagues.id')->leftJoin('countries', 'leagues.country_id', '=', 'countries.id')->whereDate('time', $date)->whereNotIn('events.league_id', [1040, 1067, 5708, 5720, 11990])->orderBy('countries.sort_order')->orderBy('leagues.sort_order')->orderBy('time_status', 'desc')->orderBy('time')->get();

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
              $url = sprintf('https://api.betsapi.com/v2/events/upcoming?%s', http_build_query($queryApi));
          }

          $ch = curl_init($url);
          curl_setopt($ch, CURLOPT_TIMEOUT, 60);
          curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 60);
          curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
          $data = json_decode(curl_exec($ch));
          curl_close($ch);

          if (!isset($data->pager)){
              $data->pager->total = 1;
              $data->pager->per_page = 1;
          }
          $total = ceil($data->pager->total/$data->pager->per_page);
          for ($i=1; $i <= $total; $i++) {
              $ch = curl_init($url."&page=$i");
              curl_setopt($ch, CURLOPT_TIMEOUT, 60);
              curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 60);
              curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
              $data = json_decode(curl_exec($ch));
              curl_close($ch);    

              foreach ($data->results as $val) {
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
          $events[] = Event::select('*')->whereDate('time', $date)->whereIn('league_id', [1040, 1067, 5708, 5720, 11990])->leftJoin('leagues', 'events.league_id', '=', 'leagues.id')->orderBy('sort_order')->get();

          $events[] = Event::select('*')->leftJoin('leagues', 'events.league_id', '=', 'leagues.id')->leftJoin('countries', 'leagues.country_id', '=', 'countries.id')->whereDate('time', $date)->whereNotIn('events.league_id', [1040, 1067, 5708, 5720, 11990])->orderBy('countries.sort_order')->orderBy('leagues.sort_order')->orderBy('time_status', 'desc')->orderBy('time')->get();

          $events = $events->collapse();
          $events = $events->groupBy('league_id');
        }

      $html = view('layouts.common.resultspage._results', compact('date', 'events'))->render();

      return response()->json($html, 200);
    }

    //team picker 
    protected function teamPicker(Request $request)
    {
        $date = Carbon::parse($request->date);
        $events = Event::where('home', $request->team)->orWhere('away', $request->team)->whereDate('time', $date)->get();
        $events = $events->groupBy('league_id');

        if (!$events->isEmpty()){ 
        }else{
            return response()->json(['erorr'=>'<span>Результатов не найдено по дате '.$date->format('d.m.Y').'</span>'], 200);
        }

        $html = view('layouts.common.resultspage._results', compact('date', 'events'))->render();

        return response()->json($html, 200);
    }

    //league picker 
    protected function leaguePicker(Request $request)
    {
        $date = Carbon::parse($request->date);        
        $league = League::find($request->id)->event;
        $league = $league->filter( function($item, $key) use ($date){
                return data_get($item, 'time') >= $date; 
            })->sortBy('time');
        if (!$league->isEmpty()){            
        }else{
            return response()->json('<span>Результатов еще нету</span>', 200);
        }

        $html = view('layouts.common.resultspage._league', compact('league'))->render();

        return response()->json($html, 200);
    }

    //toplist league picker POST Request
    protected function toplistPicker(Request $request)
    {
      session(['league_id'=> (int) $request->id]);

      $toplist = ToplistGoal::select('toplist_goals.*', 'assists', 'red_cards', 'yellow_cards')->where('league_id', $request->id)->leftJoin('toplist_assists', 'toplist_assists.player_id', '=', 'toplist_goals.player_id')->leftJoin('toplist_cards', 'toplist_cards.player_id', '=', 'toplist_goals.player_id')->paginate(30);

      if ( $toplist->isEmpty() ) return response()->json('Данных статистики нету', 200);
      
      $html = view('layouts.common.resultspage._toplist', compact('toplist'))->render();

      return response()->json($html, 200);
    }

    private function newTable($id='')
    {
      $url = 'https://api.betsapi.com/v1/league/table?token=10259-gnrWKBgQ6ioYqW&LNG_ID=73&league_id='.$id;
      $ch = curl_init($url);
      curl_setopt($ch, CURLOPT_TIMEOUT, 60);
      curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 60);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      $data = json_decode(curl_exec($ch));
      curl_close($ch);

      if( is_null($data->results)){
        return false;
      }        
      $table = LeagueTable::firstOrNew(['league_id'=>$id]);
      $table->league_id = $id;
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

      return $table;
    }

}
