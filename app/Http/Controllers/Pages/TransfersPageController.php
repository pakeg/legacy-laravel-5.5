<?php

namespace App\Http\Controllers\Pages;

use App\Transfer;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TransfersPageController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $date = Carbon::today();
        $query = $request->query();

        if (isset($query['sortBy'])){
            if ($query['sortBy'] == 'start'){
                $transfers = Transfer::whereNotNull('from')->orderBy($query['sortBy'], $query['orderBy'])->paginate(50);
            }

            elseif ($query['sortBy'] == 'cost'){
                $transfers = Transfer::whereNotNull('from')->orderBy($query['sortBy'], $query['orderBy'])->paginate(50);
            }

            elseif ($query['sortBy'] == 'top'){
                $transfers = Transfer::whereNotNull('from')->orderBy($query['sortBy'], $query['orderBy'])->paginate(50);
            }else {
                return back();
            }

        }else {
            $transfers = Transfer::whereNotNull('from')->inRandomOrder()->paginate(50);
        }
        
        return view('layouts.common.transfers', compact('transfers', 'date'));
    }

}
