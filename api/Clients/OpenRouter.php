<?php

namespace Api\Clients;

use App\Enums\OpenRouterTarget as Target;
use App\Models\Origin;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;

class OpenRouter
{
    public string $baseUrl;
    public string $apiKey;

    public PendingRequest $client;

    public function __construct(public Origin $origin)
    {
        $this->apiKey = config('clients.open-router.api_key');

        $this->baseUrl = 'https://openrouter.ai/api/v1';

        $this->client = Http::baseUrl($this->baseUrl)
            ->withHeaders(['Authorization' => "Bearer {$this->apiKey}"]);
    }

    public function completion(array $data = [], Target $target = Target::AUTO): null | string
    {
        $data = array_merge(['model' => $target->value], $data);

        $response = $this->client
            ->post('chat/completions', $data)
            ->collect();

        return $response['choices'][0]['message']['content']
            ?? dd($response);
    }
}
