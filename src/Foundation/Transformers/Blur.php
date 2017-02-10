<?php

namespace Soda\Cms\Foundation\Transformers;

use Intervention\Image\Image;

class Blur
{
    public function apply(Image $image, $amount)
    {
        return $image->blur($amount);
    }
}
