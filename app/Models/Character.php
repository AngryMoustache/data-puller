<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Character extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'franchise_id',
    ];

    public function franchise()
    {
        return $this->belongsTo(Franchise::class);
    }
}
