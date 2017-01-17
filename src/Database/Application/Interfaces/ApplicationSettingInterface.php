<?php

namespace Soda\Cms\Database\Application\Interfaces;

use Illuminate\Http\Request;
use Soda\Cms\Database\Fields\Interfaces\FieldInterface;

interface ApplicationSettingInterface extends FieldInterface
{
    public function getFieldValue();

    public function parseField(Request $request);
}
