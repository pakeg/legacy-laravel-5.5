<?php

namespace App\Http\Controllers\Commands;

use App\Event;
use Carbon\Carbon;
use App\Http\Controllers\Controller;

class getInplay extends Controller
{
    public function handle()
    {
      set_time_limit(0);
      Event::where('time_status', 1)->delete();
      Event::where('time', '<=', Carbon::now())->where('time_status', 0)->delete();

      $queryApi = array(
          'token' => '10259-gnrWKBgQ6ioYqW',
          'sport_id' => '1',
          'LNG_ID' => '73'
      );

      $url = sprintf('https://api.betsapi.com/v1/events/inplay?%s', http_build_query($queryApi));
      $ch = curl_init($url);
      curl_setopt($ch, CURLOPT_TIMEOUT, 60);
      curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 60);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      $inplay = json_decode(curl_exec($ch));
      curl_close($ch);    

      foreach ($inplay->results as $val) {
        $new = new Event();
        $new->event_id = $val->id;
        $new->time = $val->time;
        $new->time_status = $val->time_status;
        $new->league_id = $val->league->id;
        $new->home = $val->home->id;
        $new->away = $val->away->id;
        if (isset($val->timer)) {
            $new->timer = $val->timer->tm;
        }else{
            $new->timer = '';
        }
        if (isset($val->scores)) {
            $new->scores_home = $val->scores->{'2'}->home;
            $new->scores_away = $val->scores->{'2'}->away;
        }else{
            $new->scores_home = 0;
            $new->scores_away = 0;
        }          
        $new->save();
      }
    }
}
