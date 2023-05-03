<?php

namespace App\Rambo;

use AngryMoustache\Rambo\Fields;
use AngryMoustache\Rambo\Resource;

class Tag extends Resource
{
    public $displayName = 'name';

    public function fields()
    {
        return [
            Fields\IDField::make(),

            Fields\TextField::make('name')
                ->sortable()
                ->searchable()
                ->rules('required'),

            Fields\SlugField::make('slug')
                ->hideFrom(['index'])
                ->searchable(),
        ];
    }
}
