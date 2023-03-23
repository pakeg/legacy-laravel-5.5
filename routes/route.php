<?php

/*
|--------------------------------------------------------------------------
| Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// homePage
Route::get('/', 'Pages\MainPageController@index')->name('/');
	// rss tape
	Route::get('rss-tape', 'Pages\MainPageController@rssTape')->name('rss');

// list news
Route::get('news', 'Pages\NewsPageController@index')->name('news');
	// page news
	Route::get('news/{id}', 'Pages\NewsPageController@news')->name('newsPage');
		//addCommnet replyComment
		Route::post('news/{id}', 'Pages\NewsPageController@addComment');
		//likes , dislikes
		Route::post('news/{id}/likes', 'Pages\NewsPageController@addLikeDislike');
	// tags page
	Route::get('tags/{tags}', 'Pages\NewsPageController@tags')->name('tags');

// resultsPage
Route::get('results', 'Pages\ResultPageController@index')->name('results');
	//date picker
	Route::post('results', 'Pages\ResultPageController@datePicker')->name('r-datePicker');
	// 1 match results page
	Route::get('results/match/{id}', 'Pages\ResultPageController@matchResult')->name('match');
	//addCommnet replyComment
	Route::post('results/{event_id}', 'Pages\ResultPageController@addComment')->name('rst-addcom');
	//likes , dislikes
	Route::post('results/{event_id}/likes', 'Pages\ResultPageController@addLikeDislike')->name('rst-likedis');

// tablePage
Route::get('table/{id}', 'Pages\TablePageController@index')->name('lg-table');
	//table picker
	Route::post('table', 'Pages\TablePageController@tablePicker')->name('tablePicker');

// livescorePage
Route::get('livescore', 'Pages\LivescorePageController@index')->name('livescore');
	//date picker
	Route::post('livescore', 'Pages\LivescorePageController@datePicker')->name('l-datePicker');
	//team picker
	Route::post('livescore/team', 'Pages\LivescorePageController@teamPicker')->name('tm-Picker');
	//leage picker
	Route::post('livescore/leage', 'Pages\LivescorePageController@leaguePicker')->name('lg-Picker');
	//toplist league picker
	Route::post('livescore/{id}/{name}', 'Pages\LivescorePageController@toplistPicker')->name('toplist-Picker');
	//livescore cc
	Route::get('livescore/{id}/{name}', 'Pages\LivescorePageController@ccLivescore')->name('cc-livescore');

// list video
Route::get('video', 'Pages\VideoPageController@index')->name('video');
	// page video
		Route::get('video/{id}', 'Pages\VideoPageController@video')->name('page-video');
		//addCommnet replyComment
		Route::post('video/{id}', 'Pages\VideoPageController@addComment');
		//likes , dislikes
		Route::post('video/{id}/likes', 'Pages\VideoPageController@addLikeDislike');

// list photo gallery
Route::get('gallery', 'Pages\GalleryPageController@index')->name('gallery');
	// page gallery
		Route::get('gallery/{id}', 'Pages\GalleryPageController@gallery')->name('page-gallery');
		//addCommnet replyComment
		Route::post('gallery/{id}', 'Pages\GalleryPageController@addComment');
		//likes , dislikes
		Route::post('gallery/{id}/likes', 'Pages\GalleryPageController@addLikeDislike');

// page games statistic
Route::get('transfers', 'Pages\TransfersPageController@index')->name('transfers');

//list author articles || news
Route::get('authors', 'Pages\AuthorPageController@index')->name('authors');
	//list author articles
	Route::get('authors/{name}/articles', 'Pages\AuthorPageController@listArticles')->name('authors-articles');
	//list author nes
	Route::get('authors/{name}/news', 'Pages\AuthorPageController@listNews')->name('authors-news');
	// follow unfollow user
	Route::post('authors', 'Pages\AuthorPageController@ajaxFollow');



//auth user
Auth::routes();
//auth admin
Auth::routes_admin();

//user pannel
Route::group(['prefix' => 'user-pannel'], function () {

	// home
	Route::get('{name}' , 'User\Office\HomeController@index');
	Route::post('{id}' , 'User\Office\HomeController@setAjaxName');

	// posts
		// create
		Route::match(['get', 'post'], '{name}/create' , 'User\Office\HomeController@create');

		//edit
		Route::match(['get', 'post'], '{name}/edit/{id}' , 'User\Office\HomeController@edit');

		//list
		Route::get('{name}/list', 'User\Office\HomeController@list');

		// delete
		Route::get('{name}/delete/{id}', 'User\Office\HomeController@delete');
		Route::post('{name}/delete/{id}', 'User\Office\HomeController@delete');

	// comments
	Route::get('{name}/comments', 'User\Office\HomeController@listComment');

});

//admin home
Route::get('/admin-pannel', 'Admin\Office\HomeController@index');

// cron 
Route::get('/get-ended', 'Commands\getEnded@handle');
Route::get('/get-event-lineup', 'Commands\getEventLineup@handle');
Route::get('/get-event-trend', 'Commands\getEventTrend@handle');
Route::get('/get-event-view', 'Commands\getEventView@handle');
Route::get('/get-inplay', 'Commands\getInplay@handle');
Route::get('/get-league', 'Commands\getLeague@handle');
Route::get('/get-upcoming', 'Commands\getUpcoming@handle');
//***********************
Route::get('/get-team', 'Commands\getTeam@handle');
Route::get('/get-league-toplist', 'Commands\getLeagueToplist@handle');
Route::get('/get-player', 'Commands\getPlayer@handle');
Route::get('/get-transfer', 'Commands\getTransfers@handle');