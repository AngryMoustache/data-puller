<?php

namespace App;

class Site
{
    public null | string $title = null;

    public function getTitle()
    {
        return $this->title ?? config('app.name');
    }

    public function title(string $title)
    {
        $this->title = $title . ' | ' . config('app.name');
    }
}
