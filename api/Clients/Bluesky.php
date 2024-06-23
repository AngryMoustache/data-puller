<?php

namespace Api\Clients;

use Api\Entities\BlueskyPost;
use App\Models\Origin;
use Illuminate\Support\Facades\Http;

class Bluesky
{
    public string $baseUrl;
    public string $username;
    public string $password;

    public array $jwt;

    public function __construct(public Origin $origin)
    {
        $this->baseUrl = 'https://bsky.social';
        $this->username = config('clients.bluesky.username');
        $this->password = config('clients.bluesky.password');
        $this->login();
    }

    public function likes()
    {
        $likes = $this->call('/xrpc/app.bsky.feed.getActorLikes', [
            'actor' => $this->origin->api_target,
        ])['feed'];

        return collect($likes)
            ->pluck('post')
            ->mapInto(BlueskyPost::class);
    }

    public function call($url, $options = null)
    {
        return Http::baseUrl($this->baseUrl)
            ->withHeader('Authorization', 'Bearer ' . $this->jwt['accessJwt'])
            ->get($url, $options)
            ->json();
    }

    private function login(): void
    {
        $this->jwt = Http::baseUrl($this->baseUrl)
            ->post('/xrpc/com.atproto.server.createSession', [
                'identifier' => $this->username,
                'password' => $this->password,
            ])
            ->json();
    }
}
