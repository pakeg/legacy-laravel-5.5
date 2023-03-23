<?php

namespace App\Http\Controllers\Pages;

use App\User;
use App\Post;
use Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use UploadImage;
use Carbon\Carbon;
use Dan\UploadImage\Exceptions\UploadImageException;

class AuthorPageController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::where('type', 'author')->get();
        $thumb = UploadImage::load('user', '250');

        return view('layouts.common.authors', compact('users', 'thumb'));
    }

    //author page articles
    public function listArticles($name, Request $request)
    {
        $user = User::where('name', $name)->firstOrFail();
        $thumb = UploadImage::load('post', '250');
        //list articles
        $articles = Post::where([['user_id', $user->id], ['type', '1']])->orderBy('created_at', 'desc')->paginate(15);

        // list news
        $news = Post::where([['user_id', $user->id], ['type', '2']])->orderBy('created_at', 'desc')->take(15)->get();

        return view('layouts.common.authors.articles', compact('articles', 'news', 'user', 'thumb'));
    }

    //author page news
    public function listNews($name, Request $request)
    {
        $user = User::where('name', $name)->firstOrFail();
        $thumb = UploadImage::load('post', '250');

        //list news
        $news = Post::where([['user_id', $user->id], ['type', '2']])->orderBy('created_at', 'desc')->paginate(15);

        // list articles
        $articles = Post::where([['user_id', $user->id], ['type', '1']])->orderBy('created_at', 'desc')->take(15)->get();

        return view('layouts.common.authors.news', compact('news', 'articles', 'user', 'thumb'));
    }

    public function ajaxFollow(Request $request)
    {
        $user = Auth::user();
        $target = $request->target;

        if ( $request->type == 1 ){
            $user->follow($target);
        }
        if ( $request->type == 0 ){
            $user->unfollow($target);
        }

        return response()->json(['types'=>$request->type], 200);
    }


}
