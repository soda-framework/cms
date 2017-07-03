<?php

namespace Soda\Cms\Foundation\Uploads\Files;

use Intervention\Image\Image;
use Soda\Cms\Foundation\Uploads\UploadedFileTransformer;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class SymfonyFile extends AbstractUploadableFile implements UploadableFile
{
    protected $file;

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
        return '/'.trim(trim($this->getUploadTo(), '/').'/'.$this->generateFileName(), '/');
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
        $sha1Hash = $this->generateHash();
        $pathInfo = pathinfo($this->file->getClientOriginalName());

        $resultFilePath = $pathInfo['filename'].'__'.$sha1Hash;
        if (isset($pathInfo['extension']) && $pathInfo['extension']) {
            $resultFilePath .= '.'.$pathInfo['extension'];
        } elseif ($guessedExtenstion = $this->file->guessExtension()) {
            $resultFilePath .= '.'.$guessedExtenstion;
        }

        return trim($resultFilePath, '/');
    }

    protected function generateHash()
    {
        if($this->file instanceof Image) {
            return sha1($this->file->encoded);
        }
        
        return sha1_file($this->file->getRealPath());
    }
}
