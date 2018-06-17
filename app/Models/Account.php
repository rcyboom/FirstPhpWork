<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Account  extends Model
{
    //
    public function task()
    {
        return $this->hasOne('App\Models\Task');
    }
}