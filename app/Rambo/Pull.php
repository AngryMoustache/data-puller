<?php

namespace App\Rambo;

use AngryMoustache\Rambo\Fields;
use AngryMoustache\Rambo\Fields\HabtmField;
use AngryMoustache\Rambo\Resource;
use App\Enums;
use App\Rambo\Fields\EnumSelectField;

class Pull extends Resource
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

            Fields\ManyAttachmentField::make('attachments')
                ->sortField('sort_order'),

            EnumSelectField::make('origin')
                ->nullable()
                ->options(Enums\Origin::list())
                ->sortable()
                ->rules('required'),

            Fields\TextField::make('source_url')
                ->hideFrom(['index']),

            Fields\HabtmField::make('videos')
                ->resource(Video::class)
                ->hideFrom(['index']),

            EnumSelectField::make('status')
                ->nullable()
                ->options(Enums\Status::list())
                ->sortable(),

            HabtmField::make('tags')
                ->resource(Tag::class)
                ->hideFrom(['index']),
        ];
    }
}
