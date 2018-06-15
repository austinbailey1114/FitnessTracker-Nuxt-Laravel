<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Lift extends Model
{
    protected $fillable = [
        'user',
        'weight',
        'reps',
        'type',
        'date',
    ];
}
