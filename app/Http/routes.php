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
use App\Repeat;

//Route::get('/', function () {
//    return view('welcome');
//});

Route::get('/', ['as'=>'home', 'uses' => 'HomeController@index']);

Route::post('/load', function(Request $request){
    $offset = $request->session ()->get('offsetid');
    $words = Dict::select([
                     'dict.*',
                     'repeat.id as repeatId'
                 ])
                 ->whereNotIn('dict.id', Auth::user()->getLearningsIds())
                 ->leftJoin('repeat', function($join){
                     $join->on('dict.id', '=', 'repeat.dictId')
                          ->where('repeat.userId', '=', Auth::user()->id);
                 })
                 ->limit(dict::DICT_LIMIT)
                 ->offset($offset)
                 ->orderBy('repeat.id', 'desc')
                 ->orderBy('dict.id')
                 ->get()
                 ->toArray();

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

        if (!Learning::where('userId', Auth::user()->id)->where('dictId', $dictId)->get()->first()) {
            $learning         = new Learning();
            $learning->userId = $user->id;
            $learning->dictId = $dictId;
            $learning->save ();
        }

        // убрать из повторяемых
        Repeat::where('userId', $user->id)->where('dictId', $dictId)->delete();
    }

    return response()->json([]);
});

Route::post('/torepeat', function(Request $request){
    $user = Auth::user();
    if ($user) {
        $dictId = $request->get('dictid');
        if (!Repeat::where('userId', Auth::user()->id)->where('dictId', $dictId)->get()->first()) {
            $repeat         = new Repeat();
            $repeat->userId = $user->id;
            $repeat->dictId = $dictId;
            $repeat->save ();
        }

        // убрать из изученных
        Learning::where('userId', $user->id)->where('dictId', $dictId)->delete();
    }

    return response()->json([]);
});

Route::post('/todict', function(Request $request){
    $user = Auth::user();
    if ($user) {
        $dictId = $request->get('dictid');

        Learning::where('userId', $user->id)->where('dictId', $dictId)->delete();
        Repeat::where('userId', $user->id)->where('dictId', $dictId)->delete();
    }

    return response()->json([]);
});

Route::auth();

Route::get('/learning', ['middleware' => 'auth', function(){
    $words = Dict::whereIn('id', Auth::user()->getLearningsIds())->orderBy('id')->get();
    return view('home.learning', ['words' => $words]);
}]);

Route::get('/repeat', ['middleware' => 'auth', function(){

    //$a = Learning::where('userId', Auth::user()->id)->where('dictId', 1)->get()->first();
    //var_dump ($a); exit;

    $words = Dict::select([
            'dict.*',
            'repeat.id as repeatId'
        ])
        ->whereIn('dict.id', Auth::user()->getRepeatsIds())
        ->leftJoin('repeat', function($join){
            $join->on('dict.id', '=', 'repeat.dictId')
                 ->where('repeat.userId', '=', Auth::user()->id);
        })
        ->orderBy('repeat.id', 'desc')
        ->get();

    return view('home.repeat', ['words' => $words]);
}]);

Route::get('/about', function(Request $request){
    return view('home.empty');
});

Route::get('/contact', function(Request $request){
    return view('home.empty');
});

//Route::get('/home', 'HomeController@index');
