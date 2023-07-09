<?php

namespace App\Rambo;

use AngryMoustache\Rambo\Fields;
use AngryMoustache\Rambo\Resource;

class RatingCategory extends Resource
{
    public function displayNameField()
    {
        return 'name';
    }

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

            Fields\BooleanField::make('online')
                ->toggleable(),
        ];
    }
}
