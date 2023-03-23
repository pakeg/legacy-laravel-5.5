<?php

namespace App\Http\Controllers\Commands;

use App\Country;
use App\Player;
use App\Transfer;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;

class getTransfers extends Controller
{
    public function handle()
    {
      set_time_limit(0);
      session(['team_id'=>null]);
      $cc = Country::all();
      $players = Player::select('id')->distinct('id')->skip(134309)->take(3000)->get();

      $i=134309; 
      foreach ($players as $player) {
        $i++;
        Log::info('SKIP - '. $i . ' PLAYER - '. $player->id);
        $url = "https://api.betsapi.com/v1/player?token=10259-gnrWKBgQ6ioYqW&LNG_ID=73&player_id=$player->id";
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 60);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $data = json_decode(curl_exec($ch));
        curl_close($ch);

        if (empty($data->results->transfers)) continue;

        foreach ($data->results->transfers as $key => $result) {
          $transfer = new Transfer();
          $transfer->player_id = $player->id;
          $transfer->start = $result->start != 0 ? $result->start : null;
          $transfer->end = $result->end != 0 ? $result->end : null;
          $transfer->type_id = $result->type_id;
          $transfer->active = $result->active;

          if (!isset($result->team->id))  continue;

          $transfer->to = $result->team->id;
          $transfer->from = session('team_id', null);
          $transfer->cost = rand();
          $transfer->save();
          if ($key > 0) session(['team_id'=>$result->team->id]);
        }
        session(['team_id'=>null]);
      }      
      dd($i);
    }
}
