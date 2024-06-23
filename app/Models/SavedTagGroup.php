<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class SavedTagGroup extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'attachment_id',
    ];

    public $with = [
        'attachment',
        'tags',
    ];

    public function attachment()
    {
        return $this->belongsTo(Attachment::class);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }

    public static function booted()
    {
        static::saving(function (self $group) {
            $group->slug = Str::slug($group->name);
        });
    }
}
