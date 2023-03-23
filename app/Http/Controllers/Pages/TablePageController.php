<?php

namespace App\Http\Controllers\Pages;

use App\Country;
use App\Event;
use App\LeagueTable;
use App\Team;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TablePageController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
    	$table = LeagueTable::where('league_id', $id)->first();
      if (!is_null($table)) {
        $date_dif = $table->updated_at->diffInHours(Carbon::now()); 
      }else {
        $date_dif = 0;
      }
    	if ($table && $date_dif < 4){
  		}else{
    		$table = $this->newTable($id);
        if ($table === false){
          return redirect()->back()->with('flash', 'Данных еще нету');
        }
  		}

      $tables = $this->forma(json_decode($table->overall));
      
    	$cc = Country::where('status', 1)->orderBy('sort_order')->get();

      return view('layouts.common.table', compact('cc', 'tables', 'table'));
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

    // check a table
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


    protected function tablePicker(Request $request)
    {
      $table = LeagueTable::where('league_id', $request->id)->first();
      $table_num = $request->table;

      if (!is_null($table)) {
        $date_dif = $table->updated_at->diffInHours(Carbon::now()); 
      }else {
        $date_dif = 0;
      }
      if ($table && $date_dif < 4){
      }else{
        $table = $this->newTable($request->id);
        if ($table === false){
          return response()->json(['error'=>'Данных еще нету'], 200);
        }
      }

      $tables = $this->forma(json_decode($table->overall));
      $html = view('layouts.common.resultspage._table', compact('tables', 'table_num'))->render();

      return response()->json(['html'=>$html, 'tb_id'=>$table->id], 200);
    }
}
