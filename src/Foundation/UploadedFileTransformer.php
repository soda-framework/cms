<?php

namespace Soda\Cms\Foundation;

use Intervention\Image\Facades\Image;
use Soda\Cms\Foundation\Transformers\Fit;
use Soda\Cms\Foundation\Transformers\Blur;
use Soda\Cms\Foundation\Transformers\Crop;
use Soda\Cms\Foundation\Transformers\Resize;
use Soda\Cms\Foundation\Transformers\Colorize;
use Soda\Cms\Foundation\Transformers\Contrast;
use Soda\Cms\Foundation\Transformers\Greyscale;
use Soda\Cms\Foundation\Transformers\Brightness;

class UploadedFileTransformer
{
    protected $config;
    protected $transformers = [
        'blur'       => Blur::class,
        'brightness' => Brightness::class,
        'colorize'   => Colorize::class,
        'contrast'   => Contrast::class,
        'crop'       => Crop::class,
        'fit'        => Fit::class,
        'greyscale'  => Greyscale::class,
        'resize'     => Resize::class,
    ];

    public function __construct($config = [])
    {
        $this->setConfig($config);
    }

    public function transform($file)
    {
        $image = Image::make($file);
        $changed = false;

        foreach ($this->config as $transformer => $transformConfig) {
            $transformer = strtolower($transformer);
            if (isset($this->transformers[$transformer])) {
                $transformer = new $this->transformers[$transformer];
                $image = $transformer->apply($image, $transformConfig);
                $changed = true;
            }
        }

        return $changed || (isset($this->config['quality']) && $this->config['quality'] != 100) ? $image->save(null, isset($this->config['quality']) ? $this->config['quality'] : 100) : $file;
    }

    public function setConfig($config)
    {
        $this->config = $config;
    }

    public function getConfig()
    {
        return $this->config;
    }
}
