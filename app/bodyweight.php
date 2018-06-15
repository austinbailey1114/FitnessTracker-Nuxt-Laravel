<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class bodyweight extends Model
{
    protected $fillable = [
        'weight',
        'user',
        'date'
    ];
}
