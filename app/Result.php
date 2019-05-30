<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Result extends Model
{   
    protected $guarded = [];

    protected $casts = ['content'=>'array'];
}
