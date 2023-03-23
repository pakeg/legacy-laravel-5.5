<?php

namespace App\Console\Commands;

use App\Country;
use App\Event;
use App\EventView;
use Carbon\Carbon;
use Illuminate\Console\Command;

class getEventView extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:eventview';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'get event view';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        set_time_limit(0);
        $events = Event::whereDate('time', Carbon::today())->whereIn('time_status', [1, 3])->get();

        foreach ($events as $event) {
            $url = 'https://api.betsapi.com/v1/event/view?token=10259-gnrWKBgQ6ioYqW&LNG_ID=73&event_id='.$event->event_id;
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_TIMEOUT, 60);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 60);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $data = json_decode(curl_exec($ch));
            curl_close($ch);
            $resul = $data->results[0];

            $event = EventView::firstOrNew(['event_id'=>$event->event_id]);
            $event->event_id = $event->event_id;
            $event->time = $resul->time;
            $event->time_status = $resul->time_status;
            $event->league_id = $resul->league->id;

            $home = $resul->home;
            $cc = Country::where('cc', $home->cc)->first();
            $home->cc_name = $cc->name ?? '';
            $event->home = json_encode($home, JSON_UNESCAPED_UNICODE);

            $away = $resul->away;
            $cc = Country::where('cc', $away->cc)->first();
            $away->cc_name = $cc->name ?? '';
            $event->away = json_encode($away, JSON_UNESCAPED_UNICODE);
            
            $event->timer = $resul->timer->tm ?? '90';
            $event->stats = json_encode($resul->stats, JSON_UNESCAPED_UNICODE);
            $event->events = json_encode($resul->events, JSON_UNESCAPED_UNICODE);
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
