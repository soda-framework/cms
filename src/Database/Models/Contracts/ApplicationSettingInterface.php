<?php

namespace Soda\Cms\Database\Models\Contracts;

use Illuminate\Http\Request;

interface ApplicationSettingInterface extends FieldInterface
{
    /**
     * @return \Soda\Cms\Database\Models\ApplicationSetting
     */
    public function getFieldValue();

    /**
     * @return \Soda\Cms\Database\Models\ApplicationSetting
     */
    public function parseField(Request $request);
}
