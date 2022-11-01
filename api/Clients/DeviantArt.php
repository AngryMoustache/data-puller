<?php

namespace Api\Clients;

use Api\Entities\Deviantion;
use App\Models\Origin;
use Illuminate\Support\Facades\Http;

class DeviantArt
{
    public string $token;
    public string $url;

    public function __construct(public Origin $origin)
    {
        $this->url = 'https://www.deviantart.com/api/v1/oauth2';
        $this->token = Http::get('https://www.deviantart.com/oauth2/token', [
            'grant_type' => 'client_credentials',
            'client_id' => config('clients.deviant-art.client-id'),
            'client_secret' => config('clients.deviant-art.client-secret'),
        ])->json()['access_token'] ?? null;
    }

    public function favorites($page = 1, $limit = 24)
    {
        $limit ??= $this->limit;
        $maxLimit = 24;

        $items = [];
        for ($i = 0; $i < ceil($limit / 24); $i++) {
            $offset = (($page - 1) * $limit) + ($maxLimit * $i);

            $folders = Http::withToken($this->token)->get("{$this->url}/collections/folders", [
                'username' => $this->origin->api_target,
            ])->json();

            foreach (collect($folders['results'])->pluck('folderid') as $folder) {
                $response = Http::withToken($this->token)->get("{$this->url}/collections/${folder}", [
                    'username' => $this->origin->api_target,
                    'limit' => $maxLimit,
                    'offset' => $offset,
                    'mature_content' => true,
                ])->json();
            }

            foreach ($response['results'] as $result) {
                $items[] = $result;
            }
        }

        return collect($items)
            ->filter(fn ($item) => isset($item['content']))
            ->mapInto(Deviantion::class);
    }
}
