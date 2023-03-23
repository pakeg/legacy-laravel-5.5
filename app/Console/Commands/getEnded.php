<?php

namespace App\Console\Commands;

use App\Event;
use Carbon\Carbon;
use Illuminate\Console\Command;

class getEnded extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:ended';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'get ended events';

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
        $queryApi = array(
            'token' => '10259-gnrWKBgQ6ioYqW',
            'sport_id' => '1',
            'LNG_ID' => '73',
            'day'=> Carbon::today()->format('Ymd')
        );

        $url = sprintf('https://api.betsapi.com/v2/events/ended?%s', http_build_query($queryApi));
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 60);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $ended = json_decode(curl_exec($ch));
        curl_close($ch);

        if (!isset($ended->pager)){
            $ended->pager->total = 1;
            $ended->pager->per_page = 1;
        }
        $total = ceil($ended->pager->total/$ended->pager->per_page);
        for ($i=1; $i <= $total; $i++) {
            $url = sprintf('https://api.betsapi.com/v2/events/ended?%s&page=%d', http_build_query($queryApi), $i);
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_TIMEOUT, 60);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 60);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $ended = json_decode(curl_exec($ch));
            curl_close($ch);    

            foreach ($ended->results as $val) {
              $event = Event::firstOrNew(['event_id'=>$val->id]);
              $event->event_id = $val->id;
              $event->time = $val->time;
              $event->time_status = $val->time_status;
              $event->league_id = $val->league->id;
              $event->home = $val->home->name;
              $event->away = $val->away->name;
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
    }
}
