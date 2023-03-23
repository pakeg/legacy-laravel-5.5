<?php

namespace App\Console\Commands;

use App\League;
use Illuminate\Console\Command;

class getLeague extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:league';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'get league';

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
      $queryYandex = array(
        'key' => 'trnsl.1.1.20180808T155614Z.663e9d2ece78c46a.80a80fd40cefa538b68f80b326bcda36dfb3ff2d',
        'lang' => 'en-ru'
      );
      $queryApi = array(
        'token' => '10259-gnrWKBgQ6ioYqW',
        'sport_id' => '1',
        'LNG_ID' => '73'
      );

      $url = sprintf('https://api.betsapi.com/v1/league?%s', http_build_query($queryApi));
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
        $url = sprintf('https://api.betsapi.com/v1/league?%s&page=%d', http_build_query($queryApi), $i);
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 60);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $data = json_decode(curl_exec($ch));
        curl_close($ch);

        foreach ($data->results as $val) {
          $league = League::find($val->id);
          if ($league){             
          } else {
            $league = new League();
            if ( is_null($val->cc) ) {
              $league->country_id = 251;
            } else {
              $cc = Country::where('cc', $val->cc)->firstOrFail();
              $league->country_id = $cc->id;
            }
            if (empty($val->name)){
              $val->name = 'Не известно';
            }
            $trans = @file_get_contents(sprintf('https://translate.yandex.net/api/v1.5/tr.json/translate?%s&text=%s', http_build_query($queryYandex), urlencode($val->name)));
            $name = json_decode($trans);
            
            $league->id = $val->id;
            $league->name = $name->text[0];
            $league->save();
          }
        }
      }
    }       
}
