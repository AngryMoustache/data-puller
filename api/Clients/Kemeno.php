<?php

namespace Api\Clients;

use Api\Entities\KemenoPost;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class Kemeno
{
    public static function fromUrl(string $url): KemenoPost
    {
        $url = Str::of($url)->after('https://kemono.su/')->prepend('https://kemono.su/api/v1/');

        return new KemenoPost(Http::get($url)->collect(), $url);
    }
}
