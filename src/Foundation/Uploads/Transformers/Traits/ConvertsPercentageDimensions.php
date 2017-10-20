<?php

namespace Soda\Cms\Foundation\Uploads\Transformers\Traits;

use Intervention\Image\Image;

trait ConvertsPercentageDimensions
{
    public function convertDimensions(Image $image, $dimensions)
    {
        $width = isset($dimensions['width']) ? $dimensions['width'] : '100%';
        $height = isset($dimensions['height']) ? $dimensions['height'] : '100%';

        $width = $this->convertWidth($image, $width);
        $height = $this->convertHeight($image, $height);

        return [$width, $height];
    }

    public function convertWidth(Image $image, $width)
    {
        if ($this->isPercent($width)) {
            $width = $image->width() * $this->percentToDecimal($width);
        }

        return $width;
    }

    public function convertHeight(Image $image, $height)
    {
        if ($this->isPercent($height)) {
            $height = $image->height() * $this->percentToDecimal($height);
        }

        return $height;
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
