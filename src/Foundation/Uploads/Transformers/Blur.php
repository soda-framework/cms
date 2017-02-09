<?php

namespace Soda\Cms\Foundation\Uploads\Transformers;

use Intervention\Image\Image;

class Blur
{
    public function apply(Image $image, $amount)
    {
        return $image->blur($amount);
    }
}
