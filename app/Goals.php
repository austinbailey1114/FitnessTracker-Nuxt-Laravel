<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Goals extends Model
{
    protected $fillable = [
        'user',
        'calories',
        'fat',
        'carbohydrate',
        'protein'
    ];
}
