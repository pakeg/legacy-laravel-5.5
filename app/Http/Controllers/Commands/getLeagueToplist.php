<?php

namespace App\Http\Controllers\Commands;

use App\League;
use App\ToplistAssist;
use App\ToplistCard;
use App\ToplistGoal;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;

class getLeagueToplist extends Controller
{
  public function handle()
  {
    set_time_limit(0);

    $leagues = League::all();
    foreach ($leagues as $league) {   
      Log::info('ЛИГА - ' . $league->id);
      
      $url = "https://api.betsapi.com/v1/league/toplist?token=10259-gnrWKBgQ6ioYqW&LNG_ID=73&league_id=$league->id";
      $ch = curl_init($url);
      curl_setopt($ch, CURLOPT_TIMEOUT, 60);
      curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 60);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      $data = json_decode(curl_exec($ch));
      curl_close($ch);

      if (empty($data->results)) continue;
      if (is_null($data->results)) continue;

      if ( isset($data->results->topgoals)){
        foreach ($data->results->topgoals as $topgoals) {
          $toplist = ToplistGoal::firstOrNew(['player_id'=>$topgoals->player->id]);
          $toplist->country_id = $league->country->id;
          $toplist->league_id = $league->id;
          $toplist->goal_points = $topgoals->goal_points;
          $toplist->goals = $topgoals->goals;
          $toplist->penalties = $topgoals->penalties;
          $toplist->matches = $topgoals->matches;
          $toplist->minutes_played = $topgoals->minutes_played;
          $toplist->substituted_in = $topgoals->substituted_in;
          $toplist->player_id = $topgoals->player->id;
          if (isset($topgoals->team->id)){
            $toplist->team_id = $topgoals->team->id;
          }else $toplist->team_id = null;
          $toplist->save();
        }
      }

      if ( isset($data->results->topassists)){
        foreach ($data->results->topassists as $topassists) {
          $toplist = ToplistAssist::firstOrNew(['player_id'=>$topassists->player->id]);
          $toplist->assists = $topassists->assists;
          $toplist->player_id = $topassists->player->id;
          $toplist->save();
        }
      }

      if ( isset($data->results->topcards)){
        foreach ($data->results->topcards as $topcards) {
          $toplist = ToplistCard::firstOrNew(['player_id'=>$topcards->player->id]);
          $toplist->red_cards = $topcards->red_cards;
          $toplist->yellow_cards = $topcards->yellow_cards;
          $toplist->player_id = $topcards->player->id;
          $toplist->save();
        }
      }

    }
  }
}
