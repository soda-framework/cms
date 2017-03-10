<?php

namespace Soda\Cms\Foundation\Uploads\Files;

abstract class AbstractUploadableFile
{
    protected $transformConfig;
    protected $uploadTo = '/';

    /**
     * @return string
     */
    public function getTransformConfig()
    {
        return $this->transformConfig;
    }

    /**
     * @param $transformConfig
     *
     * @return $this
     */
    public function setTransformConfig($transformConfig)
    {
        $this->transformConfig = $transformConfig;

        return $this;
    }

    /**
     * @return string
     */
    public function getUploadTo()
    {
        return $this->uploadTo;
    }

    /**
     * @param $uploadTo
     *
     * @return $this
     */
    public function setUploadTo($uploadTo)
    {
        $this->uploadTo = $uploadTo;

        return $this;
    }

    /**
     * @param $appendedPath
     *
     * @return $this
     */
    public function appendToUploadPath($appendedPath)
    {
        $this->uploadTo = trim($this->uploadTo.'/'.trim($appendedPath, '/'), '/');

        return $this;
    }
}
