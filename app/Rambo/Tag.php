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

            Fields\SelectField::make('parent_id', 'Parent')
                ->sortable()
                ->resource(self::class)
                ->rules('required'),

            Fields\TextField::make('name')
                ->sortable()
                ->searchable()
                ->rules('required'),

            Fields\SlugField::make('slug')
                ->hideFrom(['index'])
                ->searchable(),

            Fields\TextField::make('color')
                ->type('color')
                ->sortable()
                ->searchable(),
        ];
    }
}
