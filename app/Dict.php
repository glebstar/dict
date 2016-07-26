<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Dict extends Model
{
    const DICT_LIMIT = 10;
    const DICT_LIMIT_NO_AUTH = 20;

    protected $table = 'dict';
}
