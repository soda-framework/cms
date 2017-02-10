<?php

namespace Soda\Cms\Foundation\Transformers;

use Intervention\Image\Image;
use Soda\Cms\Foundation\Transformers\Traits\ConvertsPercentageDimensions;

class Fit
{
    use ConvertsPercentageDimensions;

    public function apply(Image $image, $dimensions)
    {
        list($width, $height) = $this->convertDimensions($image, $dimensions);

        return $image->resize($width, $height, function ($constraint) use ($dimensions) {
            if (isset($dimensions['upsize']) && $dimensions['upsize'] == true) {
                $constraint->upsize();
            }
        });
    }
}
