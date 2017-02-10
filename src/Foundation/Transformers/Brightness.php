<?php

namespace Soda\Cms\Foundation\Transformers;

use Intervention\Image\Image;

class Brightness
{
    public function apply(Image $image, $amount)
    {
        return $image->brightness($amount);
    }
}
