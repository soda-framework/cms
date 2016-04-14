<?php

namespace Soda\Components\Uploader;

class Uploader
{

    public function __construct()
    {
        //
    }

    public function pushFile($file, $filename)
    {
        // Push the file to wherever it needs to be, this can be overridden in S3 to push to S3 instead of to the public storage path
        $file = $file->move(storage_path().'/uploads/', $filename);

        return $file;
    }

    public function deleteFile($file)
    {
        // TODO write this
        return $file;
    }

    public function upload($file)
    {
        // Move file from wherever it was uploaded to
        $file = $this->pushFile($file, $this->sanitizeFilename($file));

        return $file;
    }


    // TODO Need to name this better lol
    // takes in a file (for figuring out names etc)
    // returns a string of a nice filename
    public function sanitizeFilename($file)
    {
        //TODO Maybe figure out a better way to do this other than a regexp
        // remove the .EXT from file
        $filename = preg_replace('/\.'.$file->getClientOriginalExtension().'/', '', $file->getClientOriginalName());
        // Filter that name`
        $filename = $this->createSlug($filename);
        $filename = $this->addUUID($filename);
        $filename = $filename.".".$file->getClientOriginalExtension();

        return $filename;
    }

    function createSlug($string, $slug = '_', $extra = NULL)
    {
        return strtolower(trim(preg_replace('~[^0-9a-z'.preg_quote($extra, '~').']+~i', $slug, $this->normalize($string)), $slug));
    }

    function normalize($string)
    {
        if(strpos($string = htmlentities($string, ENT_QUOTES, 'UTF-8'), '&') !== FALSE)
        {
            $string = html_entity_decode(preg_replace('~&([a-z]{1,2})(?:acute|cedil|circ|grave|lig|orn|ring|slash|tilde|uml);~i', '$1', $string), ENT_QUOTES, 'UTF-8');
        }

        return $string;
    }

    function addUUID($string)
    {
        // append a _UUID to a file
        return uniqid($string."_");
    }

    public function compressPNG()
    {

    }

    public function compressJPG()
    {

    }

    public function compress($file)
    {
        // switch on extension type and use the appropriate compressJPG type 
        // do it with smart function calls if you wanna be fancy or just switch on the extension
    }
}