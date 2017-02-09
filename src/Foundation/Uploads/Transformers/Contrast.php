<?php

namespace Soda\Cms\Foundation\Uploads\Transformers;

use Intervention\Image\Image;

class Contrast
{
    public function apply(Image $image, $amount)
    {
        return $image->contrast($amount);
    }
}
