<?php

namespace App\Http\Livewire\Traits;

trait HasPagination
{
    public int $page = 1;

    public function loadMore()
    {
        $this->page++;
    }
}
