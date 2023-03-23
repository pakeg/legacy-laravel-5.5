<?php

namespace App\Http\ViewComposers;

use App\Country;
use App\League;
use Illuminate\View\View;

class FooterComposer 
{
  public function compose(View $view)
  {
  	$cc = Country::where('status', 1)->orderBy('sort_order')->get();
  	$league = League::whereIn('id', [1040, 1067, 11990])->get();

    $view->with(['cc'=>$cc, 'league'=>$league]);
  }
}
