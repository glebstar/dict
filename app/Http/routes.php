<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

use Illuminate\Http\Request;
use App\Dict;

//Route::get('/', function () {
//    return view('welcome');
//});

Route::get('/', ['as'=>'home', 'uses' => 'HomeController@index']);

Route::post('/load', function(Request $request){
    $words = Dict::where('id', '>', $request->session ()->get('lastid'))->limit(20)->orderBy('id')->get()->toArray();
    session (['lastid' => $words[count($words)-1]['id']]);

    $data = [
        'end' => 0,
        'list' => $words
    ];

    return response()->json($data);
});
