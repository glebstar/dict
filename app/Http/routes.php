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
    $firstLang = $request->session ()->get('firstlang');
    if (! $firstLang) {
        $firstLang = 'en';
    }

    if ('en' == $firstLang) {
        $selectDict = [
            'dict.*',
            'repeat.id AS repeatId',
        ];
    } else {
        $selectDict = [
            'dict.id',
            'dict.en as ru',
            'dict.ru as en',
            'dict.trans',
            'dict.description',
            'repeat.id AS repeatId',
        ];
    }

    $offset = $request->session ()->get('offsetid');
    $words = Dict::select($selectDict)
                 ->whereNotIn('dict.id', Auth::user()->getLearningsIds())
                 ->leftJoin('repeat', function($join){
                     $join->on('dict.id', '=', 'repeat.dictId')
                          ->where('repeat.userId', '=', Auth::user()->id);
                 })
                 ->limit(dict::DICT_LIMIT)
                 ->offset($offset)
                 ->orderBy('repeat.id', 'desc')
                 ->orderBy('dict.order')
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

Route::post('/addword', ['middleware' => 'auth', function(Request $request){
    $data = [
        'result' => 'ok',
    ];

    $v = Validator::make($request->all(), [
        'en' => 'required|max:120',
        'ru' => 'required|max:255',
    ]);

    if ($v->fails()) {
        $errors = $v->errors()->all();

        $data = [
            'result' => 'er',
            'errors' => [],
        ];

        foreach ($errors as $e) {
            $data['errors'][] = $e;
        }
    } else {
        $user = Auth::user();

        $word = new Dict();
        $word->en = str_replace ('"', '&#34;', $request->en);
        $word->ru = str_replace ('"', '&#34;', $request->ru);
        $word->trans = str_replace ('"', '&#34;', $request->trans);
        $word->description = str_replace ('"', '&#34;', $request->description);
        $word->save();

        $repeat         = new Repeat();
        $repeat->userId = $user->id;
        $repeat->dictId = $word->id;
        $repeat->save ();
    }

    return response()->json($data);
}]);

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

Route::post('/changefirst', function(Request $request){
    $firstlang = $request->get('first');
    if ('ru' == $firstlang) {
        session (['firstlang' => 'ru']);
    } else {
        session (['firstlang' => 'en']);
    }

    return response()->json([]);
});

Route::auth();

Route::get('/learning', ['middleware' => 'auth', function(Request $request){
    $firstLang = $request->session ()->get('firstlang');
    if (! $firstLang) {
        $firstLang = 'en';
    }

    if ('en' == $firstLang) {
        $selectDict = [
            'dict.*',
        ];
    } else {
        $selectDict = [
            'dict.id',
            'dict.en as ru',
            'dict.ru as en',
            'dict.trans',
            'dict.description',
        ];
    }

    $words = Dict::select($selectDict)->whereIn('id', Auth::user()->getLearningsIds())->orderBy('id')->get();
    return view('home.learning', ['words' => $words, 'firstlang' => $firstLang]);
}]);

Route::get('/repeat', ['middleware' => 'auth', function(Request $request){
    $firstLang = $request->session ()->get('firstlang');
    if (! $firstLang) {
        $firstLang = 'en';
    }

    if ('en' == $firstLang) {
        $selectDict = [
            'dict.*',
            'repeat.id as repeatId',
        ];
    } else {
        $selectDict = [
            'dict.id',
            'dict.en as ru',
            'dict.ru as en',
            'dict.description',
            'dict.trans',
            'repeat.id as repeatId',
        ];
    }

    $words = Dict::select($selectDict)
        ->whereIn('dict.id', Auth::user()->getRepeatsIds())
        ->leftJoin('repeat', function($join){
            $join->on('dict.id', '=', 'repeat.dictId')
                 ->where('repeat.userId', '=', Auth::user()->id);
        })
        ->orderBy('repeat.id', 'desc')
        ->get();

    return view('home.repeat', ['words' => $words, 'firstlang' => $firstLang]);
}]);

Route::post('/editword', ['middleware' => 'auth', function(Request $request){
    $data = [
        'result' => 'ok',
    ];

    if (Gate::denies('editor')) {
        // как ни в чем не бывало )
        return response()->json($data);
    }

    $v = Validator::make($request->all(), [
        'id' => 'required',
        'en' => 'required|max:120',
        'ru' => 'required|max:255',
        'trans' => 'required',
    ]);

    if ($v->fails()) {
        $errors = $v->errors()->all();

        $data = [
            'result' => 'er',
            'errors' => [],
        ];

        foreach ($errors as $e) {
            $data['errors'][] = $e;
        }
    } else {
        $order = (int)$request->get('order');

        $word = Dict::find($request->id);;
        $word->en = str_replace ('"', '&#34;', $request->en);
        $word->ru = str_replace ('"', '&#34;', $request->ru);
        $word->trans = str_replace ('"', '&#34;', $request->trans);
        $word->description = str_replace ('"', '&#34;', $request->description);
        if ($order > 0) {
            $word->order = $order;
        }
        $word->save();
    }

    return response()->json($data);

}]);

Route::get('/contact', function(Request $request){
    return view('home.empty');
});

Route::group(['prefix' => 'cms', 'middleware' => 'cms'], function(){
    Route::get('/', ['as' => 'cms', 'uses' =>'\GlebStarSimpleCms\Controllers\AdminController@index']);
    Route::match(['get', 'post'], '/add', '\GlebStarSimpleCms\Controllers\AdminController@add');
    Route::match(['get', 'post'], '/edit/{id}', '\GlebStarSimpleCms\Controllers\AdminController@edit');
    Route::delete('/delete/{id}', '\GlebStarSimpleCms\Controllers\AdminController@delete');
});

Route::get('{path}', '\GlebStarSimpleCms\Controllers\CmsController@index')->where('path', '([A-z\d-\/_.]+)?');
