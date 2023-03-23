<?php

namespace App\Http\Controllers\User\Office;

use App\User;
use App\Post;
use App\Tag;
use App\Comment;
use App\Video;
use App\Photo;
use Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use UploadImage;
use Carbon\Carbon;
use Dan\UploadImage\Exceptions\UploadImageException;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($name)
    {
        $user = User::where('name', $name)->firstOrFail();
        session(['name' => $name]);

        $count_news     =  $user->posts->where('type', 2)->count();        
        $count_articles =  $user->posts->where('type', 1)->count();

        $thumb = UploadImage::load('user', '250') . $user->image;

        return view('user.office', compact('user', 'thumb', 'count_news', 'count_articles'));
    }

    // create post
    public function create($name, Request $request) 
    {
        if ( $request->isMethod('post') ){
            $this->validate($request,[
                'post_name'  => 'required|string|min:3',
                'post_image' => 'image|max:3000',
             ]);

            $post = new Post();
            $post->title = trim($request->post_name);
            $post->description = strip_tags($request->post_text);
            $post->type = $request->post_type;                
            $post->viewed = 0;

            if ( $request->has('post_date') ){
                $post->published_at = $request->post_date;
            }else{
                $post->published_at = Carbon::now();
            }

            if ( $request->hasFile('post_image') ) {
                $file = $request->file('post_image');
                $video = false;
                $watermark = false;
                $thumbnail = true;
                try {
                        $path = UploadImage::upload($file, 'post', $watermark, $video, $thumbnail)->getImageName();
                    } catch (UploadImageException $e) {
                        return back()->withInput()->withErrors(['image', $e->getMessage()]);
                    }
            $post->image = $path;
            } else {
            $post->image = '';
            }

            Auth::user()->posts()->save($post);
            $post->save();

            // save link video 
            if ($request->post_type == 3) {
                if ( $request->has('post_url') ){
                    $video = new Video();
                    $video->url = $request->post_url;
                    $video->post_id = $post->id;
                    Auth::user()->video()->save($video);
                    $video->save();
                }             
            }
            // save gallary photos 
            if ($request->post_type == 4) {                    
                if ( $request->hasFile('post_images') ) {
                    $files = $request->file('post_images');
                    $video = false;
                    $watermark = false;
                    $thumbnail = true;
                    foreach ($files as $file) {
                        $photo = new Photo();
                        try {
                                $path = UploadImage::upload($file, 'post', $watermark, $video, $thumbnail)->getImageName();
                            } catch (UploadImageException $e) {
                                return back()->withInput()->withErrors(['image', $e->getMessage()]);
                            }
                    $photo->image = $path;
                    $photo->post_id = $post->id;
                    $photo->save();
                    }
                }
            }

            if ( Auth::user()->type == 'user'){
                $type_change = User::findOrFail(Auth::user()->id);
                $type_change->update(['type' => 'author']);
            }
            //add tags and sync with post
            $tags = $request->post_tag;
            if (!empty($tags)) {
                $tagList = array_filter(explode(",", $tags));

                // Loop through the tag array that we just created
                foreach ($tagList as &$tags) {
                    $tags = trim($tags);
                    $tag = Tag::firstOrCreate(['name' => $tags]);
                }
                $tags = Tag::whereIn('name', $tagList)->get()->pluck('id');

                $post->tags()->sync($tags);
            }


            return redirect ('user-pannel/' . Auth::user()->name . '/list')->with('flash', 'Статья была создана');
        }
        return view('user.create');
    }

    // edit post
    public function edit($name, $id, Request $request)
    {
        if ( $request->isMethod('post') ){
            $this->validate($request,[
                'post_name'  => 'required|string|min:3',
                'post_image' => 'image|max:3000',
             ]);

            $post = Post::findOrFail($id);
            $post->title = $request->post_name;
            $post->description = strip_tags($request->post_text);
            $post->type = $request->post_type;
            $post->published_at = $request->post_date;

            if ( $request->hasFile('post_image') ) {
                UploadImage::delete($post->image, 'post');
                $file = $request->file('post_image');
                $video = false;
                $watermark = false;
                $thumbnail = true;
                try {
                        $path = UploadImage::upload($file, 'post', $watermark, $video, $thumbnail)->getImageName();
                    } catch (UploadImageException $e) {
                        return back()->withInput()->withErrors(['image', $e->getMessage()]);
                    }
            $post->image = $path;
            } else {
            $post->image = $post->image;
            }

            Auth::user()->posts()->save($post);
            $post->save();

            // update link video 
            if ($request->post_type == 3) {
                if ( $request->has('post_url') ){
                    $video = Video::where('post_id', $post->id)->first();
                    $video->url = $request->post_url;
                    $video->post_id = $post->id;
                    Auth::user()->video()->save($video);
                    $video->save();
                }             
            }

            // update gallary photos 
            if ($request->post_type == 4) {                    
                if ( $request->hasFile('post_images') ) {
                    $files = $request->file('post_images');
                    $video = false;
                    $watermark = false;
                    $thumbnail = true;
                    foreach ($files as $file) {
                        $photo = new Photo();
                        try {
                                $path = UploadImage::upload($file, 'post', $watermark, $video, $thumbnail)->getImageName();
                            } catch (UploadImageException $e) {
                                return back()->withInput()->withErrors(['image', $e->getMessage()]);
                            }
                    $photo->image = $path;
                    $photo->post_id = $post->id;
                    $photo->save();
                    }
                }
            }

            //add tags and sync with post
            $tags = $request->post_tag;
            if (!empty($tags)) {
                $tagList = array_filter(explode(",", $tags));

                // Loop through the tag array that we just created
                foreach ($tagList as &$tags) {
                    $tags = trim($tags);
                    $tag = Tag::firstOrCreate(['name' => $tags]);
                }
                $tags = Tag::whereIn('name', $tagList)->get()->pluck('id');

                $post->tags()->sync($tags);
            }


            return redirect ('user-pannel/' . Auth::user()->name . '/list')->with('flash', 'Статья была отредактирована');
        }
        // GET request
        $post = Post::findOrFail($id);
            if ($post->has('tags')) {
                $tags = $post->tags->pluck('name')->toArray();
                $tags = implode(', ', $tags);
            } else {
                $tags = "";
            }
        $thumb = UploadImage::load('post', '250');        

        return view('user.edit')->with(['post' => $post, 'thumb' => $thumb, 'tags' => $tags]);
    }

    //delete post
    public function delete($name, $id, Request $request)
    {
        //delete image gallary post req
        if ( $request->isMethod('post') ){
            $photo = Photo::where([['id', $request->image_id], ['post_id', $id]])->first();
            
            UploadImage::delete($photo->image, 'post');
            $photo->delete();

            return response()->json('', 200);
        }

        //delete post get req
        $post = Post::findOrFail($id);
        if( Auth::user()->id == $name )
        {
            $images = $post->photo;
            foreach ($images as $image) {
                UploadImage::delete($image->image, 'post');
                $image->delete();
            }
            UploadImage::delete($post->image, 'post');
            $post->delete();
            return redirect('user-pannel/' . Auth::user()->name . '/list')->with('flash' , 'Статья была удалена.');
        }
        $errors = "Что то пошло не так.";
        return redirect()->back()->withErrors($errors);        
    }

    // list posts
    public function list ()
    {
        $user  = Auth::user();
        $posts = $user->posts()->orderBy('created_at', 'desc')->get();
        $thumb = array();
        foreach ($posts as $post) {            
            if ( (!empty($post->image)) || ($post->image != '')  ){
                $thumb[] = UploadImage::load('post', '250') . $post->image;
            } else {
                $thumb[] = asset('images/no_image.jpg');
            }
        }
        $count_news     =  $user->posts->where('type', 2)->count();        
        $count_articles =  $user->posts->where('type', 1)->count();

        return view('user.list')->with(['posts' => $posts, 'thumb' => $thumb, 'count_news' => $count_news, 'count_articles' => $count_articles]);
    }

    //list comments 
    public function listComment($name)
    {
        $user = User::where('name', $name)->firstOrFail();
        $comments = Comment::where('user_id', $user->id)->orderBy('created_at','desc')->paginate(15);     
        $count_news     =  $user->posts->where('type', 2)->count();        
        $count_articles =  $user->posts->where('type', 1)->count();

        $thumb = UploadImage::load('user', '250') . $user->image;

        return view('user.comments', compact('comments', 'thumb', 'count_news', 'count_articles'));
    }


    //change name or image
    public function setAjaxName($id, Request $request)
    {
        $user = User::findOrFail($id);
        
        if ( $request->has('name') ){
            $this->validate($request,[
                'name'=> 'required|string|min:3',
             ]);
            session(['name' => $request->name]);

            $user->update(['name' => $request->name]);
        }    

        if ( $request->hasFile('image')){
            $this->validate($request,[
                'image'=> 'image|max:3000',
             ]);
            
            $file = $request->file('image');
            $video = false;
            $watermark = false;
            $thumbnail = true;
                try {
                        $path = UploadImage::upload($file, 'user', $watermark, $video, $thumbnail)->getImageName();
                    } catch (UploadImageException $e) {
                        return back()->withInput()->withErrors(['image', $e->getMessage()]);
                    }
            UploadImage::delete($user->image, 'user');
            $user->update(['image' => $path]);
        }

        return response()->json($request->name, 200);

    }
}
