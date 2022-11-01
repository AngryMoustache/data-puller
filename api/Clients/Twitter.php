<?php

namespace Api\Clients;

use Api\Entities\Tweet;
use Illuminate\Support\Facades\Http;

class Twitter
{
    public array $options = [];
    public string $baseUrl;
    public string $apiKey;
    public string $secretKey;
    public string $userId;
    public string $bearerToken;

    public function __construct()
    {
        $this->baseUrl = 'https://api.twitter.com/2';
        $this->apiKey = config('clients.twitter.api_key');
        $this->secretKey = config('clients.twitter.secret_key');
        $this->bearerToken = config('clients.twitter.bearer_token');
        $this->userId = config('clients.twitter.user_id');

        $this->options = [
            'media.fields' => 'media_key,duration_ms,width,height,preview_image_url,type,url,alt_text,variants',
            'expansions' => 'attachments.media_keys,author_id',
            'tweet.fields' => 'attachments,author_id,context_annotations,conversation_id,created_at,entities,geo,id,in_reply_to_user_id,lang,public_metrics,possibly_sensitive,referenced_tweets,reply_settings,source,text,withheld',
            'user.fields' => 'username',
        ];
    }

    public function call($url)
    {
        $url = str_replace('/:id/', "/{$this->userId}/", $url);

        return Http::withToken($this->bearerToken)
            ->baseUrl($this->baseUrl)
            ->get($url, $this->options)
            ->json();
    }

    public function likes($page = 1)
    {
        // Get the Twitter API data
        for ($i = 0; $i < $page; $i++) {
            if ($this->options['pagination_token'] ?? null || $i === 0) {
                $tweets = $this->call('/users/:id/liked_tweets');
                $this->options['pagination_token'] = $tweets['meta']['next_token'] ?? null;
            }
        }

        // Remove unneeded tweets
        $data = collect($tweets['data'] ?? [])->reject(function ($tweet) {
            return ! isset($tweet['attachments']['media_keys']);
        });

        // Link the media to the tweets
        $media = collect($tweets['includes']['media'] ?? []);
        $tweets = $data->map(function ($tweet) use ($media) {
            $tweet['attachments'] = collect($tweet['attachments']['media_keys'])
                ->map(fn ($item) => $media->where('media_key', $item)->first());

            return $tweet;
        });

        // Create entities and return them
        return $tweets->mapInto(Tweet::class);
    }
}
