<?php

namespace App\Models;

use AngryMoustache\Media\Models\Attachment;
use Api\Clients;
use Api\Entities\Pullable;
use App\Enums;
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
        return $this->hasMany(Pull::class);
    }

    public function pendingPulls()
    {
        return $this->hasMany(Pull::class)->pending();
    }

    public function pull()
    {
        $id = $this->api_target;
        $items = match ($this->type) {
            Enums\Origin::TWITTER => (new Clients\Twitter($this, $id))->likes(),
            Enums\Origin::DEVIANTART => (new Clients\DeviantArt($this, $id))->favorites(),
        };

        $items->each(fn (Pullable $pull) => $pull->save($this));
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

    public static function boot()
    {
        parent::boot();

        self::addGlobalScope('online', fn ($query) => $query->where('online', 1));
    }
}
