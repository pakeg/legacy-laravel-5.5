<?php

namespace App\Http\Controllers\Commands;

use App\Team;
use App\Country;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;

class getTeam extends Controller
{
    public function handle()
    {
      set_time_limit(0);
      $country = Country::all();
      
      $queryApi = array(
        'token' => '10259-gnrWKBgQ6ioYqW',
        'sport_id' => '1',
        'LNG_ID' => '73'
      );

      $url = sprintf('https://api.betsapi.com/v1/team?%s', http_build_query($queryApi));
      $ch = curl_init($url);
      curl_setopt($ch, CURLOPT_TIMEOUT, 60);
      curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 60);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      $page = json_decode(curl_exec($ch));
      curl_close($ch);

      if (!isset($page->pager)){
        $page->pager->total = 1;
        $page->pager->per_page = 1;
      }

      $total = ceil($page->pager->total/$page->pager->per_page);

      for ($i=1; $i <= $total; $i++) {         
        $url = sprintf('https://api.betsapi.com/v1/team?%s&page=%d', http_build_query($queryApi), $i);
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 60);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $data = json_decode(curl_exec($ch));
        curl_close($ch);

        foreach ($data->results as $val) {
          Log::info('page - '. $i . 'команда - ' . $val->id);
          $team = Team::find($val->id);
          if ($team){ 
            continue;            
          } else {
            $team = new Team();
            if ( is_null($val->cc) ) {
              $team->country_id = 251;
            } else {
              if ($val->cc == 'n'){ $team->country_id = 102; 
              }else{
                $cc = $country->where('cc', $val->cc)->first();
                $team->country_id = $cc->id;
              }
            }
                        
            $team->id = $val->id;
            $team->name = $val->name;
            $team->image = $val->image_id;
            $team->save();
          }
        }
      }

      Log::info('Обновление команд - закончилось');
    }       
}
