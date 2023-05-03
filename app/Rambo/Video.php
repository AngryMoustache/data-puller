<?php

namespace App\Rambo;

use AngryMoustache\Rambo\Fields;
use AngryMoustache\Rambo\Fields\AttachmentField;
use AngryMoustache\Rambo\Resource;

class Video extends Resource
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

            AttachmentField::make('preview_id', 'Preview'),
        ];
    }
}
