<?php

namespace App\Rambo;

use AngryMoustache\Rambo\Fields;
use AngryMoustache\Rambo\Resource;
use App\Enums;
use App\Rambo\Fields\EnumSelectField;

class Origin extends Resource
{
    public function displayNameField()
    {
        return 'iconName';
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

            Fields\AttachmentField::make('attachment_id', 'Avatar'),

            EnumSelectField::make('type')
                ->nullable()
                ->options(Enums\Origin::list())
                ->sortable()
                ->rules('required'),

            Fields\TextField::make('api_target')
                ->hideFrom(['index']),

            Fields\BooleanField::make('online')
                ->toggleable(),
        ];
    }
}
