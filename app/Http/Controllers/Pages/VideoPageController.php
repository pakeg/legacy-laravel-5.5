<?php

namespace App\Http\Controllers\Pages;

use App\Post;
use App\Comment;
use Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use UploadImage;
use Dan\UploadImage\Exceptions\UploadImageException;

class VideoPageController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
    	$video_arch = Post::where('type', '3')->orderBy('created_at', 'desc')->take(20)->get();

    	$videos = Post::where('type', '3')->orderBy('created_at', 'desc')->paginate(5);

        return view('layouts.common.video', compact('videos', 'video_arch'));
    }

    /* 1-video page */
    public function video($id)
    {
        $post = Post::findOrFail($id);
        $post->update(['viewed'=>$post->viewed+1]);
        $posts = Post::where('type', '2')->orderBy('created_at', 'desc')->take(15)->get();

        $thumb = UploadImage::load('post', '250');
        $image_user = UploadImage::load('user', '250');
       
        // comments
        if($post){
            $comments = $post->comments;
   
            // children
            $com = $comments->groupBy('parent_id');

            $com = $com->map(function ($item, $key) {
              return $item->sortByDesc('created_at');
            });

        } else $com = null;

        return view('layouts.common.videopage._video')->with(['post'=>$post, 'thumb'=>$thumb, 'image_user'=>$image_user, 'posts_arch'=>$posts, 'com'=>$com]);
    }

    public function addComment($post_id, Request $request)
    {
        $this->validate($request,[
                'text'  => 'required|string|min:3',
                ]);
        $comment = new Comment();
        $comment->parent_id = $request->parent_id;
        $comment->text = strip_tags($request->text);
        $comment->post_id = $post_id;
        Auth::user()->comments()->save($comment);
        $comment->save();

        //respone
        $data['text'] = strip_tags($request->text);
        $data['created_at'] = Carbon::now()->format('d.m.y, H:i');
        $data['user_name'] = Auth::user()->name;
        $data['user_image'] = UploadImage::load('user', '250').Auth::user()->image;
        $data['id'] = $comment->id;

        $html = view('layouts.common.comments.new_comment')->with('data', $data)->render();

        return response()->json(['html'=>$html, 'parent_id'=>$request->parent_id], 200);

    }

    public function addLikeDislike($id, Request $request)
    {
        $comment = Comment::findOrFail($request->com_id);        

        if ( $request->type_like == 1 ){
            $comment->like(Auth::user()->id);
        }
        if ( $request->type_like == 0 ){
            $comment->dislike(Auth::user()->id);
        }
        $count = $comment->likesDiffDislikesCount;

        return response()->json(['types'=>$request->type_like, 'count'=>$count], 200);
    }

    
}
