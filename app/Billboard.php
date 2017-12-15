<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Billboard extends Model
{
    protected $table = 'billboard';

    protected $guarded = ['id'];
}
