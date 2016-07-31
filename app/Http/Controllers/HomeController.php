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
        $search = $request->get('search');
        //var_dump ($search); exit;

        $firstLang = $request->session ()->get('firstlang');
        if (! $firstLang) {
            $firstLang = 'en';
        }

        if ('en' == $firstLang) {
            $selectDict = [
                'dict.*',
            ];

            if (Auth::user()) {
                $selectDict[] = 'repeat.id AS repeatId';
            }
        } else {
            $selectDict = [
                'dict.id',
                'dict.en as ru',
                'dict.ru as en',
                'dict.trans',
                'dict.description',
            ];

            if (Auth::user()) {
                $selectDict[] = 'repeat.id AS repeatId';
            }
        }

        $whereSearch = 'dict.id';
        $whereSearchOp = '>';
        $whereSearchVal = '0';
        if ($search) {
            $whereSearch = 'dict.en';
            if (strlen ($search) < 3) {
                $whereSearchOp = '=';
                $whereSearchVal = $search;
            } else {
                $whereSearchOp = 'like';
                $whereSearchVal = $search . '%';
            }
        }

        $limit = Dict::DICT_LIMIT;
        if (!Auth::user()) {
            $limit = Dict::DICT_LIMIT_NO_AUTH;
            $words = Dict::select($selectDict)
                ->where($whereSearch, $whereSearchOp, $whereSearchVal)
                ->limit($limit)
                ->orderBy('order')
                ->orderBy('id')
                ->get();
        } else {
            $words = Dict::select($selectDict)
                ->whereNotIn('dict.id', Auth::user()->getLearningsIds())
                ->where($whereSearch, $whereSearchOp, $whereSearchVal)
                ->leftJoin('repeat', function($join){
                    $join->on('dict.id', '=', 'repeat.dictId')
                        ->where('repeat.userId', '=', Auth::user()->id);
                })
                ->limit($limit)
                ->orderBy('repeat.id', 'desc')
                ->orderBy('dict.order')
                ->orderBy('dict.id')
                ->get();

            session (['offsetid' => Dict::DICT_LIMIT]);
        }

        return view('home.index', ['words' => $words, 'firstlang' => $firstLang, 'search' => $search]);
    }
}