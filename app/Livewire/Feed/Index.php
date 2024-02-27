<?php

namespace App\Livewire\Feed;

use Api\Clients\Kemeno;
use Api\Clients\OpenAI;
use Api\Entities\Media\Image;
use Api\Entities\Pullable;
use App\Enums\Origin as EnumsOrigin;
use App\Livewire\Traits\CanToast;
use App\Livewire\Traits\HasPagination;
use App\Livewire\Traits\HasPreLoading;
use App\Models\Origin;
use App\Models\Pull;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Livewire\Component;

class Index extends Component
{
    use HasPreLoading;
    use HasPagination;
    use CanToast;

    public int $perPage = 6;

    public null | string $tweetUrl = '';
    public array $scrape = [
        'url' => '',
        'limit' => 0,
    ];

    public array $kemeno = [
        'url' => '',
    ];

    public function render()
    {
        app('site')->title('Feed');

        if (! $this->loaded) {
            return $this->renderLoadingListContainer();
        }

        $pulls = Pull::pending()
            ->with('attachments', 'videos', 'artist')
            ->take($this->perPage * $this->page)
            ->latest()
            ->get();

        return view('livewire.feed.index', [
            'pulls' => $pulls,
            'hasMore' => Pull::pending()->count() > $pulls->count(),
            'origins' => Origin::online()->get(),
            'archiveCount' => Pull::offline()->count(),
        ]);
    }

    public function pullScrape(
        null|string $url = null,
        null|Collection &$media = null,
        int $failsafe = 0,
    )  {
        $url ??= $this->scrape['url'];
        $limit = $this->scrape['limit'] ?? 0;
        if (empty($limit)) {
            $limit = 999;
        }

        $media ??= collect();

        $html = Cache::rememberForever("{$url}qsfddsf", fn () => file_get_contents($url));
        $dom = new \DOMDocument();
        @$dom->loadHTML($html);
        $xpath = new \DOMXPath($dom);
        $image = $xpath->query('//img[@id="img"]')->item(0);

        $media->push(Image::make()->source(
            optional($image)->getAttribute('src')
        ));

        // Check for another page
        $next = optional($xpath->query('//div[@id="i3"]//a')->item(0))->getAttribute('href');
        if ($next === $url || $media->count() >= $limit) {
            $name = Str::limit(OpenAI::translateToEnglish(
                $xpath->query('//h1')->item(0)->textContent
            ), 250);

            $origin = Origin::where('type', EnumsOrigin::SCRAPER)->first();

            // Create a pull
            $pull = new Pullable;
            $pull->name = $name;
            $pull->source = $url;
            $pull->media = $media;

            $pull->save($origin);

            $this->toast('E-Hentai has been pulled! Give it a minute to process');

            return;
        }

        // Pull next page
        $this->pullScrape($next, $media, $failsafe + 1);
    }

    public function pullKemeno()
    {
        $url = $this->kemeno['url'] ?? null;

        if (blank($url)) {
            return;
        }

        $origin = Origin::where('type', EnumsOrigin::KEMENO)->first();
        Kemeno::fromUrl($url)?->save($origin);

        $this->toast('Kemeno has been pulled! Give it a minute to process');
        $this->kemeno['url'] = '';
    }

    public function syncOrigin(null | Origin $origin = null)
    {
        if (! $origin->id) {
            Origin::online()->get()->each(fn (Origin $origin) => $origin->pull());
            $this->toast('All origins are syncing! Check back later');

            return;
        }

        $this->toast("{$origin->name} is syncing! Check back later");
        $origin->pull();
    }
}
