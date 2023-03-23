<?php

namespace App\Console\Commands;

use App\Event;
use Illuminate\Console\Command;

class getInplay extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:inplay';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'get inplay games';

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
        $event = Event::where('time_status', 1)->delete();

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

        if (!isset($inplay->pager)){
            $inplay->pager->total = 1;
            $inplay->pager->per_page = 1;
        }
        $total = ceil($inplay->pager->total/$inplay->pager->per_page);
        for ($i=1; $i <= $total; $i++) {
            $url = sprintf('https://api.betsapi.com/v1/events/inplay?%s&page=%d', http_build_query($queryApi), $i);
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
              $new->home = $val->home->name;
              $new->away = $val->away->name;
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
}
