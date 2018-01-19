<?php
/*
|--------------------------------------------------------------------------
| Routes File
|--------------------------------------------------------------------------
|
| Here is where you will register all of the routes in an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::group(['middleware' => 'cors', 'prefix' => 'api/v1'], function(){

	Route::get('/teams', 'HomeController@teams');
	Route::get('/holders', 'HomeController@holders');

	Route::get('/test', 'HomeController@test');
	
	Route::get('contest/list', 'ContestController@getContestList');
	Route::get('contest/{contest_id}', 'ContestController@getContest');
	Route::get('contest/{contest_id}/players', 'ContestController@getContestPlayers');
	Route::post('contest/create', 'ContestController@createContest');
	
	Route::post('lineup/enter', 'LineupController@createContestEntry');
	Route::get('lineup', 'LineupController@getLineup');
	Route::get('lineups', 'LineupController@getUserLineUps');
	Route::get('lineup/{contest_id}/users/{page}/{limit}', 'LineupController@getLineupUsers');

	Route::get('playerGameLog','HomeController@playerGameLog');
});

Route::group(['middleware' => 'cors', 'prefix' => 'api/v1'], function(){
    Route::resource('authenticate', 'AuthenticateController', ['only' => ['index']]);
    Route::post('authenticate', 'AuthenticateController@authenticate');
    Route::get('authenticate/user', 'AuthenticateController@getAuthenticatedUser');
    Route::get('user/logout', 'AuthenticateController@logout');
});

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| This route group applies the "web" middleware group to every route
| it contains. The "web" middleware group is defined in your HTTP
| kernel and includes session state, CSRF protection, and more.
|
*/

Route::group(['middleware' => ['web']], function () {
    //
});
