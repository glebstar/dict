<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Repeat extends Model
{
    protected $table = 'repeat';

    public function user()
    {
        return $this->belongsTo(User::class, 'userId');
    }
}
