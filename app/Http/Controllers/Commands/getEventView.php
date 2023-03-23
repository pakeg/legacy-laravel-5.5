<?php

namespace App\Http\Controllers\Commands;

use App\Event;
use App\EventView;
use Carbon\Carbon;
use App\Http\Controllers\Controller;

class getEventView extends Controller
{
    public function handle()
    {
        set_time_limit(0);
        $events = Event::where('time_status', 1)->get()->chunk(10);

        foreach ($events as $key => $row) {
            foreach ($row as $keys => $event) {
                $events_id[$key][$keys] = $event->event_id;              
            }
            $events_id[$key] = implode(',', $events_id[$key]);
        }

        foreach ($events_id as $event_id) {
            $url = 'https://api.betsapi.com/v1/event/view?token=10259-gnrWKBgQ6ioYqW&LNG_ID=73&event_id='.$event_id;
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_TIMEOUT, 60);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 60);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $data = json_decode(curl_exec($ch));
            curl_close($ch);

            foreach ($data->results as $resul) {
                $event = EventView::firstOrNew(['event_id'=>$resul->id]);
                /*if ( ($event->time_status == 3) && ($event->updated_at > $event->created_at) ) continue;*/
                $event->event_id = $resul->id;
                $event->time = $resul->time;
                $event->time_status = $resul->time_status;
                $event->league_id = $resul->league->id;                
                $event->timer = $resul->timer->tm ?? '90';
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
            }            
        }
    }
}
