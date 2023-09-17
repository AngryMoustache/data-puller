<?php

namespace App\Models;

use App\Livewire\Wireables\FilterBag;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class DynamicFolder extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'filter_string',
    ];

    public function getPulls()
    {
        return (new FilterBag($this->filter_string))
            ->toPulls()
            ->fetch();
    }

    public function getAttachmentAttribute()
    {
        return $this->getPulls()->first()?->attachment;
    }

    public function route()
    {
        return route('pull.index', [
            'filterString' => $this->filter_string,
        ]);
    }

    public static function booted()
    {
        static::saving(function (self $folder) {
            $folder->slug = Str::slug($folder->name);
        });
    }
}
