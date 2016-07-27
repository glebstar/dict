<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Dict;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index(Request $request)
    {
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
                'repeat.id AS repeatId',
            ];
        }

        $limit = Dict::DICT_LIMIT;
        if (!Auth::user()) {
            $limit = Dict::DICT_LIMIT_NO_AUTH;
            $words = Dict::limit($limit)->orderBy('id')->get();
        } else {
            $words = Dict::select($selectDict)
                ->whereNotIn('dict.id', Auth::user()->getLearningsIds())
                ->leftJoin('repeat', function($join){
                    $join->on('dict.id', '=', 'repeat.dictId')
                        ->where('repeat.userId', '=', Auth::user()->id);
                })
                ->limit($limit)
                ->orderBy('repeat.id', 'desc')
                ->orderBy('dict.id')
                ->get();

            session (['offsetid' => Dict::DICT_LIMIT]);
        }

        return view('home.index', ['words' => $words, 'firstlang' => $firstLang]);
    }
}