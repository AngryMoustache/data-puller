<?php

namespace App\Models;

use App\Models\Attachment;
use Api\Entities\Pullable;
use App\Enums;
use App\Enums\Origin as EnumsOrigin;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\HtmlString;

class Origin extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'type',
        'attachment_id',
        'api_target',
        'online',
    ];

    public $casts = [
        'type' => Enums\Origin::class,
        'online' => 'boolean',
    ];

    public function attachment()
    {
        return $this->belongsTo(Attachment::class);
    }

    public function pulls()
    {
        return $this->hasMany(Pull::class)->online();
    }

    public function pendingPulls()
    {
        return $this->hasMany(Pull::class)->pending();
    }

    public function pull()
    {
        $this->type->pull($this)?->each(function (Pullable $pull) {
            $pull->save($this);
        });
    }

    public function scopePrompts($query)
    {
        return $query->where('type', EnumsOrigin::PROMPT);
    }

    public function getIconNameAttribute()
    {
        return new HtmlString(sprintf(
            '<span class="tag" style="%s">
                <i class="fab fa-%s" style="margin-right: .5rem;"></i> %s
            </span>',
            $this->type->style(),
            $this->type->icon(),
            $this->name
        ));
    }

    public function scopeOnline($query)
    {
        return $query->where('online', 1);
    }
}
