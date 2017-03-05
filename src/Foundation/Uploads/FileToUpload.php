<?php

namespace Soda\Cms\Foundation\Uploads;

use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileToUpload
{
    protected $file;
    protected $transformConfig;
    protected $uploadTo = '/';

    public function __construct(UploadedFile $file)
    {
        $this->file = $file;
    }

    public function fileContents()
    {
        return file_get_contents($this->file->getRealPath());
    }

    /**
     * @return string
     */
    public function uploadPath()
    {
        return '/'.trim($this->getUploadTo(), '/').'/'.$this->generateFileName();
    }

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

    /**
     * @return $this
     */
    public function transform()
    {
        if ($this->transformConfig) {
            $transformer = new UploadedFileTransformer($this->transformConfig);
            $this->file = $transformer->transform($this->file);
        }

        return $this;
    }

    /**
     * @return string
     */
    protected function generateFileName()
    {
        $sha1Hash = sha1_file($this->file->getRealPath());
        $pathInfo = pathinfo($this->file->getClientOriginalName());

        $resultFilePath = $pathInfo['filename'].'__'.$sha1Hash;
        if ($pathInfo['extension']) {
            $resultFilePath .= '.'.$pathInfo['extension'];
        }

        return trim($resultFilePath, '/');
    }
}
