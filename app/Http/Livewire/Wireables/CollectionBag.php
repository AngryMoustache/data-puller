<?php

namespace App\Http\Livewire\Wireables;

use Illuminate\Support\Str;
use Livewire\Wireable;

class CollectionBag implements Wireable
{
    // Livewire stuff
    public function toLivewire()
    {
        return collect(get_object_vars($this))->map(function ($value) {
            return (array) $value;
        })->toArray();
    }

    public static function fromLivewire($values)
    {
        $bag = new static;

        foreach ($values as $key => $value) {
            if (is_array($value)) {
                $value = collect($value)->map(function ($item) {
                    if (is_string($item) && Str::contains($item, '::')) {
                        [$class, $value] = explode('::', $item);

                        return new $class(json_decode($value));
                    }

                    return $item;
                });
            }

            $bag->{$key} = $value;
        }

        return $bag;
    }
}
