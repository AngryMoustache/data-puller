<?php

namespace App\Rambo;

use AngryMoustache\Rambo\Fields;
use AngryMoustache\Rambo\Fields\HabtmField;
use AngryMoustache\Rambo\Fields\SelectField;
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

            SelectField::make('origin_id', 'Origin')
                ->resource(Origin::class)
                ->hideFrom(['index'])
                ->rules('required'),

            Fields\TextField::make('source_url')
                ->hideFrom(['index']),

            Fields\HabtmField::make('videos')
                ->resource(Video::class)
                ->hideFrom([]),

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
