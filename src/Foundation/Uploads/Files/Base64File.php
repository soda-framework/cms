<?php

namespace Soda\Cms\Foundation\Uploads\Files;

use Soda\Cms\Foundation\Uploads\Uploader;

class Base64File extends AbstractUploadableFile implements UploadableFile
{
    protected $fileName;
    protected $fileContents;

    public function __construct($fileContents, $fileName = null)
    {
        $this->fileContents = $fileContents;
        $this->fileName = $this->generateFileName($fileName);
    }

    public function fileContents()
    {
        return $this->fileContents;
    }

    /**
     * @return string
     */
    public function uploadPath()
    {
        return '/'.trim(trim($this->getUploadTo(), '/').'/'.$this->fileName, '/');
    }

    /**
     * @return $this
     */
    public function transform()
    {
        if ($this->transformConfig) {
            $transformer = new UploadedFileTransformer($this->transformConfig);
            $this->fileContents = $transformer->transform($this->fileContents);
        }

        return $this;
    }

    /**
     * @param null $fileName
     *
     * @return string
     */
    protected function generateFileName($fileName = null)
    {
        $sha1Hash = $fileName === null ? sha1($this->fileContents) : $fileName;

        $resultFilePath = $sha1Hash;
        if ($fileType = $this->guessFileType()) {
            $resultFilePath .= '.'.$fileType;
        }

        return trim($resultFilePath, '/');
    }

    protected function guessFileType()
    {
        // Strip redundant data from base64 string
        $image = str_replace('data:image/jpeg;base64,', '', $this->fileContents);
        $image = str_replace(' ', '+', $image);

        // Determine the mimetype of the image
        $mimeType = finfo_buffer(finfo_open(), base64_decode($image), FILEINFO_MIME_TYPE);

        // Guess the extension for the image, based off the mimetype
        return Uploader::guessFileExtensionByMimeType($mimeType);
    }
}
