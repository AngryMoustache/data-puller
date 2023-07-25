<?php

namespace App\Http\Livewire\Feed;

use Api\Entities\Media\Image;
use Api\Entities\Pullable;
use App\Enums\Origin as EnumsOrigin;
use App\Http\Livewire\Traits\CanToast;
use App\Http\Livewire\Traits\HasPagination;
use App\Http\Livewire\Traits\HasPreLoading;
use App\Models\Artist;
use App\Models\Origin;
use App\Models\Pull;
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

    public function render()
    {
        app('site')->title('Feed');

        if (! $this->loaded) {
            return $this->renderLoadingListContainer();
        }

        $pulls = Pull::pending()->latest()->get();

        $archived = Pull::offline()
            ->orderBy('verdict_at', 'desc')
            ->where(fn ($query) => $query->whereHas('attachments')->orWhereHas('videos'))
            ->latest()
            ->limit($this->page * $this->perPage)
            ->get();

        return view('livewire.feed.index', [
            'pulls' => $pulls,
            'archived' => $archived,
            'hasMore' => Pull::offline()->count() > $archived->count(),
        ]);
    }

    public function pullTweet()
    {
        $url = Str::replace(
            'https://twitter.com/',
            'https://nitter.net/',
            $this->tweetUrl
        );

        // Scrape the URL and get the "main-tweet" class
        $html = Cache::rememberForever($url, fn () => file_get_contents($url));
        $dom = new \DOMDocument();
        @$dom->loadHTML($html);
        $xpath = new \DOMXPath($dom);
        $attachments = $xpath->query('//div[@class="main-tweet"]//div[@class="attachments"]//img');

        $media = collect();
        foreach ($attachments as $attachment) {
            $imageUrl = Str::of($attachment->getAttribute('src'))
                ->replace('%3Dsmall%', '%3Dlarge%')
                ->prepend('https://nitter.net');

            // Decode stuff like %2F to /
            $imageUrl = urldecode($imageUrl);

            $media->push(Image::make()->source((string) $imageUrl));
        }

        $name = $xpath->query('//div[@class="main-tweet"]//div[@class="tweet-content media-body"]')
            ->item(0)
            ->textContent;

        $artist = Artist::guess(
            Str::betweenFirst($url, 'https://nitter.net/', '/status')
        );

        $origin = Origin::where('type', EnumsOrigin::TWITTER)->first();

        // Create a pull
        $pull = new Pullable;
        $pull->name = $pull->checkJapanese($name, 'Manual tweet');
        $pull->source = $this->tweetUrl;
        $pull->media = $media;
        $pull->artist = $artist;

        $pull->save($origin);

        $this->toast('Tweet has been pulled! Give it a minute to process');
    }
}
