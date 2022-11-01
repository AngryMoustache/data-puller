<?php

namespace Api\Entities;

use App\Enums\Origin;

class Tweet extends Pullable
{
    public Origin $origin = Origin::TWITTER;

    public function __construct($pull)
    {
        $this->name = $pull['id'];
        $this->source = $pull['entities']['urls'][0]['url'];
        $this->media = $pull['attachments'];

        $this->media = $this->media->map(function ($media, $key) {
            $item = new Media($media);
            $item->name = $this->name;

            if ($this->media->count() > 1) {
                $item->name .= ' - ' . ($key + 1);
            }

            return $item;
        });
    }
}
