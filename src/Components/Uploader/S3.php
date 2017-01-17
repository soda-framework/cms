<?php

namespace Soda\Components\Uploader;

// This class
class S3 extends Uploader
{
    public $s3;

    public function __construct()
    {
        $this->s3 = \Storage::disk('s3');
    }

    // push file to S3
    public function pushFile($file, $filename)
    {
        //dd($file);
        $file = $file->move(storage_path().'/uploads/', $filename);

        $this->s3->put(config('soda.s3_upload_path').$filename, file_get_contents($file->getRealPath()), 'public');
        // TODO return a link to the S3 bucket NOT CLOUDFLARE
        dd('done');
    }

    // Remove file from S3
    public function deleteFile($file)
    {
        return $file;
    }
}
