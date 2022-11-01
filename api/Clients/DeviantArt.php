<?php

namespace Api\Clients;

use Api\Entities\Deviantion;
use Illuminate\Support\Facades\Http;

class DeviantArt
{
    public string $token;
    public string $url;
    public string $folder;

    public function __construct()
    {
        $this->url = 'https://www.deviantart.com/api/v1/oauth2';
        $this->folder = config('clients.deviant-art.folder-id');
        $this->token = Http::get('https://www.deviantart.com/oauth2/token', [
            'grant_type' => 'client_credentials',
            'client_id' => config('clients.deviant-art.client-id'),
            'client_secret' => config('clients.deviant-art.client-secret'),
        ])->json()['access_token'] ?? null;
    }

    public function favorites($page = 1, $limit = 24, $folder = null)
    {
        $limit ??= $this->limit;
        $folder ??= $this->folder;
        $maxLimit = 24;

        $items = [];
        for ($i = 0; $i < ceil($limit / 24); $i++) {
            $offset = (($page - 1) * $limit) + ($maxLimit * $i);

            $response = Http::withToken($this->token)->get("{$this->url}/collections/${folder}", [
                'username' => 'angrymoustache',
                'limit' => $maxLimit,
                'offset' => $offset,
                'mature_content' => true,
            ])->json();

            foreach ($response['results'] as $result) {
                $items[] = $result;
            }
        }

        return collect($items)
            ->filter(fn ($item) => isset($item['content']))
            ->mapInto(Deviantion::class);
    }
}
