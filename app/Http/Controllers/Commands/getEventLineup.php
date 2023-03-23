<?php

namespace App\Http\Controllers\Commands;

use App\Event;
use App\EventLineup;
use Carbon\Carbon;
use App\Http\Controllers\Controller;

class getEventLineup extends Controller
{
    public function handle()
    {
        set_time_limit(0);
        $events = Event::where('time_status', 1)->get();

        foreach ($events as $event) {
            $url = 'https://api.betsapi.com/v1/event/lineup?token=10259-gnrWKBgQ6ioYqW&LNG_ID=73&event_id='.$event->event_id;
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_TIMEOUT, 60);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 60);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $data = json_decode(curl_exec($ch));
            curl_close($ch);

            $lineup = EventLineup::where('event_id', $event->event_id)->first();
            if ($lineup) continue;
            $lineup = new EventLineup();        
            $lineup->event_id = $event->event_id;
            $lineup->home_start = json_encode($data->results->home->startinglineup ?? [], JSON_UNESCAPED_UNICODE);
            $lineup->away_start = json_encode($data->results->away->startinglineup ?? [], JSON_UNESCAPED_UNICODE);
            $lineup->home_subs = json_encode($data->results->home->substitutes ?? [], JSON_UNESCAPED_UNICODE);
            $lineup->away_subs = json_encode($data->results->away->substitutes ?? [], JSON_UNESCAPED_UNICODE);
            $lineup->save();  
        }  
    }
}
