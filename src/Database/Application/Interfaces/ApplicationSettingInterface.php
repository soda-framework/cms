<?php

namespace Soda\Cms\Database\Application\Interfaces;

use Illuminate\Http\Request;
use Soda\Cms\Database\Fields\Interfaces\FieldInterface;

interface ApplicationSettingInterface extends FieldInterface
{
    /**
     * @return \Soda\Cms\Database\Application\Models\ApplicationSetting
     */
    public function getFieldValue();

    /**
     * @return \Soda\Cms\Database\Application\Models\ApplicationSetting
     */
    public function parseField(Request $request);
}
