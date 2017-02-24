<?php

namespace Soda\Cms\Foundation\Uploads\Transformers;

use Intervention\Image\Image;
use Soda\Cms\Foundation\Uploads\Transformers\Traits\ConvertsPercentageDimensions;

class Fit
{
    use ConvertsPercentageDimensions;

    public function apply(Image $image, $dimensions)
    {
        list($width, $height) = $this->convertDimensions($image, $dimensions);

        return $image->resize($width, $height, function($constraint) use ($dimensions) {
            if (isset($dimensions['upsize']) && $dimensions['upsize'] == true) {
                $constraint->upsize();
            }
        });
    }
}
