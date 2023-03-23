<?php

namespace App\Http\ViewComposers;

use App\User;
use UploadImage;
use Illuminate\View\View;

class UserComposer 
{
  public function compose(View $view)
  {
  	$name = session('name');
  	$user = User::where('name', $name)->firstOrFail();

        if ( (!empty($user->image)) || ($user->image != '')  ){
            $thumb = UploadImage::load('user', '250') . $user->image;
        } else {
            $thumb = asset('images/no_image.jpg');
        }
    $view->with(['user' => $user , 'thumb' => $thumb ]);
  }
}
