<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Journey extends Model
{
    protected $fillable = [
        'start_date',
        'data',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'data' => 'array',
    ];
}
