<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Learning extends Model
{
    protected $table = 'learning';

    public function user()
    {
        return $this->belongsTo(User::class, 'userId');
    }
}
