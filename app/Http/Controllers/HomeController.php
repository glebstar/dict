<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Dict;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        $limit = 10;
        if (!Auth::user()) {
            $limit = 30;
        }

        $words = Dict::limit($limit)->orderBy('id')->get();
        session (['lastid' => $words[count($words)-1]->id]);

        return view('home.index', ['words' => $words]);
    }
}