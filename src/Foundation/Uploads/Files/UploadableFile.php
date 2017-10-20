<?php

namespace Soda\Cms\Foundation\Uploads\Files;

interface UploadableFile
{
    public function fileContents();

    public function uploadPath();

    public function getTransformConfig();

    public function setTransformConfig($transformConfig);

    public function getUploadTo();

    public function setUploadTo($uploadTo);

    public function appendToUploadPath($appendedPath);

    public function transform();
}
