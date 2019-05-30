<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Compare extends Model
{
    protected $guarded = [];

    public function result()
    {
        return $this->hasOne(Result::class);
    }
}
