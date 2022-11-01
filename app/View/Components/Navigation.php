<?php

namespace App\View\Components;

use App\Models\Pull;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\URL;
use Illuminate\View\Component;

class Navigation extends Component
{
    private Collection $items;

    public function __construct()
    {
        $this->items = collect([
            [
                'label' => 'Gallery',
                'route' => route('gallery.index'),
                'notification' => false,
            ], [
                'label' => 'Feed',
                'route' => route('feed.index'),
                'notification' => !! Pull::pending()->count(),
            ]
        ]);
    }

    public function render()
    {
        $current = URL::current();

        return view('components.navigation', [
            'items' => $this->items->map(function ($route) use ($current) {
                $route['active'] = ($current === $route['route']);

                return (object) $route;
            })
        ]);
    }
}
