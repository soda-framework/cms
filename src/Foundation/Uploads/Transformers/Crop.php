<?php

namespace Soda\Cms\Foundation\Uploads\Transformers;

use Intervention\Image\Image;
use Soda\Cms\Foundation\Uploads\Transformers\Traits\ConvertsPercentageDimensions;

class Crop
{
    use ConvertsPercentageDimensions;

    public function apply(Image $image, $coordinates)
    {
        list($width, $height) = $this->convertDimensions($image, $coordinates);

        $x = isset($coordinates['x']) ? $coordinates['x'] : null;
        $y = isset($coordinates['y']) ? $coordinates['y'] : null;

        return $image->crop($width, $height, $x, $y);
    }

    protected function isPercent($number)
    {
        return strpos($number, '%') !== false;
    }

    protected function percentToDecimal($number)
    {
        return str_replace('%', '', $number) / 100;
    }
}
