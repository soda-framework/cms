<?php

namespace Soda\Cms\Foundation\Uploads\Transformers;

use Intervention\Image\Image;

class Greyscale
{
    public function apply(Image $image, $settings)
    {
        return $image->brightness();
    }
}
