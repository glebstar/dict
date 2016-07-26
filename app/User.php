<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function learnings()
    {
        return $this->hasMany(Learning::class, 'userId');
    }

    public function getLearningsIds()
    {
        $result = [];

        $learnings = $this->hasMany(Learning::class, 'userId')->getResults();
        foreach ($learnings as $l) {
            $result[] = $l->dictId;
        }

        return $result;
    }

    public function getRepeatsIds()
    {
        $result = [];

        $repeats = $this->hasMany(Repeat::class, 'userId')->getResults();
        foreach ($repeats as $r) {
            $result[] = $r->dictId;
        }

        return $result;
    }
}
