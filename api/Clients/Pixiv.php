<?php

namespace Api\Clients;

use Api\Entities\PixivIllust;
use App\Models\Origin;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class Pixiv
{
    public $token = null;
    public $url = null;

    public function __construct(public Origin $origin)
    {
        $this->url = 'https://app-api.pixiv.net/v1/';
    }

    public function refresh()
    {
        $response = Http::bodyFormat('form_params')
            ->post('https://oauth.secure.pixiv.net/auth/token', [
                'client_id' => config('clients.pixiv.client_id'),
                'client_secret' => config('clients.pixiv.client_secret'),
                'grant_type' => 'refresh_token',
                'include_policy' => true,
                'refresh_token' => config('clients.pixiv.refresh_token'),
            ])
            ->json();

        return $response['access_token'];
    }

    public function bookmarks()
    {
        $this->token = $this->refresh();

        $url = "{$this->url}user/bookmarks/illust?user_id={$this->origin->api_target}&restrict=public";
        $results = collect(['next_url' => $url]);
        $items = collect();

        while ($results['next_url']) {
            $url = $results['next_url'];
            $results = Http::withToken($this->token)->get($url)->collect();
            $items = $items->merge($results['illusts']);
        }

        return $items->mapInto(PixivIllust::class);
    }
}
