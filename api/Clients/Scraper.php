<?php

namespace Api\Clients;

use Api\Entities\ScraperItem;
use App\Models\Origin;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class Scraper
{
    public $baseUrl = null;
    public $detailUrl = null;

    public function __construct(public Origin $origin)
    {
        $this->baseUrl = config('clients.scraper.base_url');
        $this->baseUrl .= $this->origin->api_target;

        $this->detailUrl = config('clients.scraper.detail_url');
    }

    public function favorites()
    {
        // Get the contents of the page and fetch the IDs from the a tags
        $response = Http::get($this->baseUrl)->body();
        preg_match_all('/id=(\d+)/', $response, $matches);

        return collect($matches[1])
            ->unique()
            ->map(fn ($id) => $this->detail($id))
            ->filter()
            ->mapInto(ScraperItem::class);
    }

    public function detail($id)
    {
        return Cache::rememberForever("scraper.{$id}", function () use ($id) {
            return Http::get($this->detailUrl . $id)->json()[0] ?? [];
        });
    }
}
