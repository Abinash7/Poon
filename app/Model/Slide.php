<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Slide extends Model
{
    protected $fillable = [
        'image', 'title', 'description'
    ];
    
}
