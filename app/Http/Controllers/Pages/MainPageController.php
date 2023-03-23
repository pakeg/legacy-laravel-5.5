<?php

namespace App\Http\Controllers\Pages;

use App\User;
use App\Post;
use App\Video;
use App\Photo;
use App\Event;
use Auth;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use UploadImage;
use Dan\UploadImage\Exceptions\UploadImageException;
use App\Http\Controllers\Controller;

class MainPageController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $leagues = collect();
        $leagues[] = Event::select('*')->whereDate('time', Carbon::today())->whereIn('league_id', [1040, 1067, 5708, 5720])->leftJoin('leagues', 'events.league_id', '=', 'leagues.id')->orderBy('sort_order')->get();

        $leagues[] = Event::select('*')->leftJoin('leagues', 'events.league_id', '=', 'leagues.id')->leftJoin('countries', 'leagues.country_id', '=', 'countries.id')->whereDate('time', Carbon::today())->where('countries.status', 1)->orderBy('countries.sort_order')->orderBy('leagues.sort_order')->orderBy('time_status', 'desc')->orderBy('time')->get();   

        $leagues = $leagues->collapse();        
        $leagues = $leagues->groupBy('league_id')->map(function($item, $key){
            return $item->chunk(ceil(count($item)/2));
        });

        // news
        $post_slider = Post::where('type', '2')->orderBy('created_at', 'desc')->take(15)->get()->chunk(3);

        // news archive
        $posts_arch = Post::where('type', '2')->orderBy('created_at', 'desc')->skip(15)->take(15)->get();

        // news popular viewed
        $posts_pop_slider = Post::where('type', '2')->orderBy('viewed', 'desc')->take(15)->get()->chunk(3);

        // article
        $paper_slider = Post::where('type', '1')->orderBy('created_at', 'desc')->take(25)->get()->chunk(5);

        $videos = Post::where('type', '3')->orderBy('created_at', 'desc')->take(3)->get();
        $photos = Post::where('type', '4')->orderBy('created_at', 'desc')->take(10)->get();

        $thumb = UploadImage::load('post', '250');

        return view('layouts.home')->with(['posts'=>$post_slider, 'thumb'=>$thumb, 'posts_arch'=>$posts_arch,'papers'=>$paper_slider, 'videos'=>$videos, 'photos'=>$photos, 'posts_pop'=>$posts_pop_slider, 'leagues'=>$leagues]);
    }

    protected function rssTape()
    {
        $data = Post::whereIn('type', [1, 2])->orderBy('created_at', 'desc')->take(20)->get();

        $xml = new \DomDocument("1.0", "utf-8");
            $rss = $xml->appendChild($xml->createElement('rss'));
            $rss->setAttribute("version", '2.0');

                $channel = $rss->appendChild($xml->createElement('channel'));
                    $title = $channel->appendChild($xml->createElement('title', config('app.name')));
                    $link = $channel->appendChild($xml->createElement('link', route('/')));
                    $description = $channel->appendChild($xml->createElement('description', 'Rss лента'));
                    $language = $channel->appendChild($xml->createElement('language', 'ru-ru'));
                    $copyright = $channel->appendChild($xml->createElement('copyright', 'Все права защищены ' . config('app.name')));

                    foreach($data as $items) {
                        $item = $channel->appendChild($xml->createElement('item'));
                            $title = $item->appendChild($xml->createElement('title', $items->title));
                            $link = $item->appendChild($xml->createElement('link', route('newsPage' , ['id'=> $items->id])));
                            $description = $item->appendChild($xml->createElement('description', str_replace('&nbsp;', ' ', strip_tags(html_entity_decode($items->description, ENT_QUOTES, 'UTF-8')))));
                            if ($items->type == 1){
                                $category = $item->appendChild($xml->createElement('category', 'Статья'));
                            }elseif ($items->type == 2){
                                $category = $item->appendChild($xml->createElement('category', 'Новость'));
                            }
                            $author = $item->appendChild($xml->createElement('author', $items->user->name));
                            $pubDate = $item->appendChild($xml->createElement('pubDate', $items->created_at->format('D, d M Y H:i:s GMT')));
                    }
                    
        $xml->formatOutput = true;

        Storage::disk('public')->put('rss.xml', $xml->saveXML());
        sleep(1);        
        return response()->file(public_path('storage/rss.xml'), ['Content-Type'=>'application/xml']);
    }

}
