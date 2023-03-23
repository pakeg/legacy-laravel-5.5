<?php

namespace App\Http\Controllers\Commands;

use App\Country;
use App\Team;
use App\Player;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;

class getPlayer extends Controller
{
    public function handle()
    {
      set_time_limit(0);

      $cc = Country::all();
      $teams = Team::skip(25303)->take(3000)->get();
      $i=25303;
      foreach ($teams as $team) {
        $i++;
        Log::info('SKIP - '. $i . ' TEAM - '. $team->id);
        
        $url = "https://api.betsapi.com/v1/team/squad?token=10259-gnrWKBgQ6ioYqW&LNG_ID=73&team_id=$team->id";
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 60);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $data = json_decode(curl_exec($ch));
        curl_close($ch);

        if (empty($data->results)) continue;

        foreach ($data->results as $result) {
          $player = Player::firstOrNew(['id'=>$result->id]);
          $player->id = $result->id;
          $player->name = $result->name;

            if ( is_null($result->cc) ) {
              $player->country_id = 251;
            } else {
              if ($result->cc == 'n'){ $player->country_id = 102; 
              }else{
                $player->country_id = $cc->where('cc', $result->cc)->first()->id;
              }
            }

          $player->team_id = $team->id;
          $player->birthdate = Carbon::parse($result->birthdate);
          $player->position = $result->position;
          $player->height = $result->height;
          $player->weight = $result->weight;
          $player->foot = $result->foot;
          $player->shirtnumber = !empty($result->shirtnumber) ? $result->shirtnumber : null;
          $player->save();
        }
      }      
      dd($i);
    }
}