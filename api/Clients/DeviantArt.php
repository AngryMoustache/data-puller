<?php

namespace Api\Clients;

use Api\Entities\Deviantion;
use App\Models\Origin;
use Illuminate\Support\Collection;
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
            'client_id' => config('clients.deviant_art.client_id'),
            'client_secret' => config('clients.deviant_art.client_secret'),
        ])->json()['access_token'];
    }

    public function favorites()
    {
        $items = [];

        $folders = Http::withToken($this->token)->get("{$this->url}/collections/folders", [
            'username' => $this->origin->api_target,
        ])->json();

        foreach (collect($folders['results'])->pluck('folderid') as $folder) {
            $response = Http::withToken($this->token)->get("{$this->url}/collections/${folder}", [
                'username' => $this->origin->api_target,
                'limit' => 24,
                'offset' => 0,
                'mature_content' => true,
            ])->json();

            foreach ($response['results'] as $result) {
                $items[] = $result;
            }
        }

        return collect($items)
            ->filter(fn ($item) => isset($item['content']) || isset($item['videos']))
            ->mapInto(Deviantion::class);
    }

    public function getTags(string $id): Collection
    {
        return Http::withToken($this->token)->get("{$this->url}/deviation/metadata", [
            'deviationids' => $id,
        ])->collect();
    }
}
