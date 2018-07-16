<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Foods extends Model
{
    protected $fillable = [
        'calories',
        'carbohydrate',
        'fat',
        'protein',
        'user',
        'name',
    ];
}
