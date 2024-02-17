<?php

namespace Api\Clients;

use Api\Entities\Media\Video;
use Api\Entities\Tweet;
use App\Models\Origin;
use App\Models\Pull;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class Twitter
{
    public array $options = [];
    public string $baseUrl;
    public string $apiKey;

    public array $userIds = [
        'ShibaraChan' => 1175441814171586560,
    ];

    public function __construct(public Origin $origin)
    {
        $this->baseUrl = 'https://twttrapi.p.rapidapi.com';
        $this->apiKey = config('clients.twitter.api_key');
    }

    public function call($url, $options = null)
    {
        return Cache::remember($url . json_encode($options), now()->addDay(), function () use ($url, $options) {
            return Http::withHeaders([
                    'X-RapidAPI-Host' => 'twttrapi.p.rapidapi.com',
                    'X-RapidAPI-Key' => $this->apiKey,
                ])
                ->baseUrl($this->baseUrl)
                ->get($url, $options ?? $this->options)
                ->json();
        });
    }

    public function likes()
    {
        $response = $this->call('/user-likes', ['user_id' => $this->userIds[$this->origin->api_target]], []);
        $tweets = collect($response['data']['user_result']['result']['timeline_response']['timeline']['instructions'][0]['entries'] ?? []);

        return collect($tweets)
            ->pluck('content.content.tweetResult.result')
            ->filter(fn (null | array $tweet) => count($tweet['legacy']['extended_entities']['media'] ?? []) > 0)
            ->mapInto(Tweet::class);
    }
}
