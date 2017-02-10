<?php

namespace Soda\Cms\Foundation\Transformers;

use Intervention\Image\Image;

class Colorize
{
    public function apply(Image $image, $rgb)
    {
        $rgb = array_merge(['red' => 0, 'green' => 0, 'blue' => 0], $rgb);

        return $image->colorize($rgb['red'], $rgb['green'], $rgb['blue']);
    }
}
