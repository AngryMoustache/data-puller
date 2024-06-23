<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Slideshow extends Model
{
    protected $fillable = [
        'md5',
        'ids',
    ];

    public $casts = [
        'ids' => 'array',
    ];
}
