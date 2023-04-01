<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Franchise extends Model
{
    protected $fillable = [
        'name',
        'slug',
    ];

    public function characters()
    {
        return $this->hasMany(Character::class);
    }
}
