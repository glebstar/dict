<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Dict;

class HomeController extends Controller
{
    public function index()
    {
        $words = Dict::limit(20)->orderBy('id')->get();
        session (['lastid' => $words[count($words)-1]->id]);

        return view('home.index', ['words' => $words]);
    }
}