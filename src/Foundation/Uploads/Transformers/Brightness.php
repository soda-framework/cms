<?php

namespace Soda\Cms\Foundation\Uploads\Transformers;

use Intervention\Image\Image;

class Brightness
{
    public function apply(Image $image, $amount)
    {
        return $image->brightness($amount);
    }
}
