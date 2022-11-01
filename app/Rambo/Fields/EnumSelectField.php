<?php

namespace App\Rambo\Fields;

use AngryMoustache\Rambo\Fields\SelectField;

class EnumSelectField extends SelectField
{
    public $bladeShowComponent = 'rambo::fields.show.enum';

    public function getShowValue()
    {
        return parent::getValue();
    }
}
