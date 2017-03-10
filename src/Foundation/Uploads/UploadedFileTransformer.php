<?php

namespace Soda\Cms\Foundation\Uploads;

use Intervention\Image\Facades\Image;
use Soda\Cms\Foundation\Uploads\Transformers\Fit;
use Soda\Cms\Foundation\Uploads\Transformers\Blur;
use Soda\Cms\Foundation\Uploads\Transformers\Crop;
use Soda\Cms\Foundation\Uploads\Transformers\Resize;
use Soda\Cms\Foundation\Uploads\Transformers\Colorize;
use Soda\Cms\Foundation\Uploads\Transformers\Contrast;
use Soda\Cms\Foundation\Uploads\Transformers\Greyscale;
use Soda\Cms\Foundation\Uploads\Transformers\Brightness;

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

    public function __construct(array $config = [])
    {
        $this->setConfig($config);
    }

    public function transform($image)
    {
        $image = Image::make($image);
        foreach ($this->config as $transformer => $transformConfig) {
            $transformer = strtolower($transformer);
            if (isset($this->transformers[$transformer])) {
                $transformer = new $this->transformers[$transformer];
                $image = $transformer->apply($image, $transformConfig);
            }
        }

        return $image->save(null, isset($this->config['quality']) ? $this->config['quality'] : 100);
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
