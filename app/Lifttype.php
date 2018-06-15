<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Lifttype extends Model
{
    protected $fillable = [
        'name',
        'user'
    ];
}
