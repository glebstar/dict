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
use Illuminate\Support\Facades\Auth;
use App\Dict;
use App\Learning;

//Route::get('/', function () {
//    return view('welcome');
//});

Route::get('/', ['as'=>'home', 'uses' => 'HomeController@index']);

Route::post('/load', function(Request $request){
    $offset = $request->session ()->get('offsetid');
    $words = Dict::limit(9)->offset($offset)->orderBy('id')->get()->toArray();

    session (['offsetid' => $offset += Dict::DICT_LIMIT]);

    $data = [
        'end' => 0,
        'list' => $words
    ];

    return response()->json($data);
});

Route::post('/tolearning', function(Request $request){
    $user = Auth::user();
    if ($user) {
        $dictId = $request->get('dictid');

        $learning = new Learning();
        $learning->userId = $user->id;
        $learning->dictId = $dictId;
        $learning->save();
    }

    return response()->json([]);
});

Route::post('/todict', function(Request $request){
    $user = Auth::user();
    if ($user) {
        $dictId = $request->get('dictid');

        Learning::where('userId', $user->id)->where('dictId', $dictId)->delete();
    }

    return response()->json([]);
});

Route::auth();

Route::get('/learning', ['middleware' => 'auth', function(){
    $words = Dict::whereIn('id', Auth::user()->getLearningsIds())->orderBy('id')->get();
    return view('home.learning', ['words' => $words]);
}]);

Route::get('/repeat', ['middleware' => 'auth', function(){
    return view('home.empty');
}]);

Route::get('/about', function(Request $request){
    return view('home.empty');
});

//Route::get('/home', 'HomeController@index');
