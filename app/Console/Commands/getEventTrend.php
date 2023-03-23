<?php

namespace App\Console\Commands;

use App\Event;
use App\EventTrend;
use Carbon\Carbon;
use Illuminate\Console\Command;

class getEventTrend extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:eventtrend';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'get event trend';

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
            $url = 'https://api.betsapi.com/v1/event/stats_trend?token=10259-gnrWKBgQ6ioYqW&LNG_ID=73&event_id='.$event->event_id;
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_TIMEOUT, 60);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 60);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $data = json_decode(curl_exec($ch));
            curl_close($ch);

            $trend = EventTrend::firstOrNew(['event_id'=>$event->event_id]); 
            $trend->event_id = $event->event_id;
            $trend->goals = json_encode($data->results->goals ?? [], JSON_UNESCAPED_UNICODE);
            $trend->yellowcards = json_encode($data->results->yellowcards ?? [], JSON_UNESCAPED_UNICODE);
            $trend->redcards = json_encode($data->results->redcards ?? [], JSON_UNESCAPED_UNICODE);
            $trend->substitutions = json_encode($data->results->substitutions ?? [], JSON_UNESCAPED_UNICODE);
            $trend->save();
        }
    }
}
