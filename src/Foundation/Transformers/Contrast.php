<?php

namespace Soda\Cms\Foundation\Transformers;

use Intervention\Image\Image;

class Contrast
{
    public function apply(Image $image, $amount)
    {
        return $image->contrast($amount);
    }
}
