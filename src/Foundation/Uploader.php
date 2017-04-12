<?php

namespace Soda\Cms\Foundation;

use Soda\Cms\Models\Media;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class Uploader
{
    public function uploadBase64($fileContents, $fileName, $transformConfig = [])
    {
        // Name the file and place in correct directory
        $fileName = $fileName === null ? sha1($fileContents) : $fileName;

        // Strip redundant data from base64 string
        $imageContents = str_replace('data:image/jpeg;base64,', '', $fileContents);
        $imageContents = str_replace(' ', '+', $imageContents);

        // Determine the mimetype of the image
        $mimeType = finfo_buffer(finfo_open(), base64_decode($imageContents), FILEINFO_MIME_TYPE);

        // Guess the extension for the image, based off the mimetype
        $fileExtension = static::guessFileExtensionByMimeType($mimeType);

        // Manipulate file before upload
        if ($transformConfig) {
            $transformer = new UploadedFileTransformer($transformConfig);
            $transformer->transform($fileContents);
        }

        $uploadFilePath = $this->urlPrefix().'/'.$fileName.($fileExtension ? '.'.$fileExtension : '');

        // Upload the file
        $uploaded = $this->driver()->put($uploadFilePath, $fileContents, 'public');

        // Generate return information
        if ($uploaded) {
            return config('soda.upload.driver') == 'soda.public' ? '/uploads/'.ltrim($uploadFilePath, '/') : $this->driver()->url($uploadFilePath);
        }

        return false;
    }

    public function uploadFile(UploadedFile $file, $transformConfig = [])
    {
        if ($file->isValid()) {
            // Name the file and place in correct directory
            $filePath = $file->getRealPath();
            $fileExtension = $file->getExtension() ?: $file->guessExtension();

            // Manipulate file before upload
            if ($transformConfig) {
                $transformer = new UploadedFileTransformer($transformConfig);
                $transformer->transform($file);
            }

            $uploadFilePath = $this->urlPrefix().'/'.$this->generateFileName($filePath, $file->getClientOriginalName(), $fileExtension);

            // Upload the file
            $uploaded = $this->driver()->put($uploadFilePath, file_get_contents($filePath), 'public');

            // Generate return information
            if ($uploaded) {
                return config('soda.upload.driver') == 'soda.public' ? '/uploads/'.ltrim($uploadFilePath, '/') : $this->driver()->url($uploadFilePath);
            }
        }

        return false;
    }

    public function fancyUpload(Request $request, $transformConfig = [])
    {
        if ($request->hasFile('file')) {
            foreach ($request->file('file') as $file) {
                if ($file->isValid()) {
                    $uploadedFile = $this->uploadFile($file, $transformConfig);

                    // Generate return information
                    if ($uploadedFile) {
                        return $this->generateFancyUploadResponse($request, $uploadedFile);
                    }
                }
            }

            return response()->json(['error' => 'File not valid.']);
        }

        return response()->json(['error' => 'No valid files to upload.']);
    }

    protected function driver()
    {
        $driver = config('soda.upload.driver');

        return Storage::disk($driver);
    }

    protected function urlPrefix()
    {
        return trim(config('soda.upload.folder'), '/');
    }

    protected function generateFileName($filePath, $fileName, $fileExtension)
    {
        $sha1Hash = sha1_file($filePath);
        $pathInfo = pathinfo($fileName);

        $resultFilePath = $pathInfo['filename'].'__'.$sha1Hash;
        if ($fileExtension) {
            $resultFilePath .= '.'.$fileExtension;
        } elseif (! isset($pathInfo['extension']) && $pathInfo['extension']) {
            $resultFilePath .= '.'.$pathInfo['extension'];
        }

        return trim($resultFilePath, '/');
    }

    protected function generateFancyUploadResponse(Request $request, $uploadedFilePath)
    {
        $mimeType = static::detectByFilename($uploadedFilePath);
        $isMulti = $request->input('multi') && $request->input('multi') == 'true';
        $return = [
            'append'               => $isMulti ? true : false,
            'initialPreview'       => [$uploadedFilePath], // todo: not always an image
            'initialPreviewAsData' => true,
            'initialPreviewCount'  => 1,
            'initialPreviewConfig' => [
                [
                    'filetype' => $mimeType,
                    'type'     => static::guessFileTypeByMimeType($mimeType),
                    'width'    => '120px',
                ],
            ],
        ];

        $table = $request->input('related_table');
        $field = $request->input('related_field');
        $id = $request->input('related_id');

        if ($isMulti) {
            $media = Media::create([
                'related_id'    => $id,
                'related_table' => $table,
                'related_field' => $field,
                'position'      => $request->input('file_id'),
                'media'         => $uploadedFilePath,
                'media_type'    => 'image', // todo: autodetect
            ]);

            $return['initialPreviewConfig'][0]['key'] = $media->id;
            $return['initialPreviewConfig'][0]['extra'] = [
                'key'           => $media->id,
                'related_table' => $table,
                'related_field' => $field,
                'related_id'    => $id,
            ];
        } else {
            DB::table($table)->where('id', $id)->update([
                $field => $uploadedFilePath,
            ]);

            $return['initialPreviewConfig'][0]['key'] = null;
            $return['initialPreviewConfig'][0]['extra'] = [
                'related_table' => $table,
                'related_field' => $field,
                'related_id'    => $id,
            ];
        }

        return $return;
    }
    /**
     * Detects MIME Type based on file extension.
     *
     * @param string $extension
     *
     * @return string|null MIME Type or NULL if no extension detected
     */
    public static function detectByFileExtension($extension)
    {
        static $extensionToMimeTypeMap;
        if (! $extensionToMimeTypeMap) {
            $extensionToMimeTypeMap = static::getExtensionToMimeTypeMap();
        }
        if (isset($extensionToMimeTypeMap[$extension])) {
            return $extensionToMimeTypeMap[$extension];
        }

        return 'text/plain';
    }

    /**
     * @param string $filename
     *
     * @return string
     */
    public static function detectByFilename($filename)
    {
        $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

        return empty($extension) ? 'text/plain' : static::detectByFileExtension($extension);
    }

    public static function guessFileExtensionByMimeType($mimeType)
    {
        static $mimeTypes = null;

        if ($mimeTypes === null) {
            foreach (static::getExtensionToMimeTypeMap() as $extension => $extensionMimeType) {
                $mimeTypes[$extensionMimeType][] = $extension;
            }
        }

        if (isset($mimeTypes[$mimeType])) {
            return $mimeTypes[$mimeType][0];
        }
    }

    /**
     * @param string $mimeType
     *
     * @return string
     */
    public static function guessFileTypeByMimeType($mimeType)
    {
        switch ($mimeType) {
            case 'text/html':
                return 'html';
            case 'text/css':
            case 'text/plain':
            case 'text/richtext':
            case 'text/rtf':
            case 'text/x-comma-separated-values':
            case 'application/xml':
            case 'application/json':
                return 'text';
            case 'application/pdf':
                return 'pdf';
            case 'audio/midi':
            case 'audio/mpeg':
            case 'audio/x-aiff':
            case 'audio/x-pn-realaudio':
            case 'audio/x-pn-realaudio-plugin':
            case 'audio/x-realaudio':
            case 'audio/x-wav':
            case 'audio/x-m4a':
            case 'audio/x-acc':
                return 'audio';
            case 'image/jpeg':
            case 'image/png':
            case 'image/gif':
            case 'image/bmp':
            case 'image/tiff':
            case 'image/svg+xml':
            case 'audio/x-au':
            case 'audio/ac3':
            case 'audio/x-flac':
            case 'audio/ogg':
            case 'audio/x-ms-wma':
                return 'image';
            case 'video/mpeg':
            case 'video/quicktime':
            case 'video/x-msvideo':
            case 'video/x-sgi-movie':
            case 'video/vnd.rn-realvideo':
            case 'video/3gpp2':
            case 'video/3gp':
            case 'video/mp4':
            case 'video/webm':
            case 'application/vnd.mpegurl':
            case 'video/x-ms-wmv':
                return 'video';
        }

        return 'other';
    }

    /**
     * @return array Map of file extension to MIME Type
     */
    public static function getExtensionToMimeTypeMap()
    {
        return [
            'hqx'   => 'application/mac-binhex40',
            'cpt'   => 'application/mac-compactpro',
            'csv'   => 'text/x-comma-separated-values',
            'bin'   => 'application/octet-stream',
            'dms'   => 'application/octet-stream',
            'lha'   => 'application/octet-stream',
            'lzh'   => 'application/octet-stream',
            'exe'   => 'application/octet-stream',
            'class' => 'application/octet-stream',
            'psd'   => 'application/x-photoshop',
            'so'    => 'application/octet-stream',
            'sea'   => 'application/octet-stream',
            'dll'   => 'application/octet-stream',
            'oda'   => 'application/oda',
            'pdf'   => 'application/pdf',
            'ai'    => 'application/pdf',
            'eps'   => 'application/postscript',
            'ps'    => 'application/postscript',
            'smi'   => 'application/smil',
            'smil'  => 'application/smil',
            'mif'   => 'application/vnd.mif',
            'xls'   => 'application/vnd.ms-excel',
            'ppt'   => 'application/powerpoint',
            'pptx'  => 'application/vnd.openxmlformats-officedocument.presentationml.presentation',
            'wbxml' => 'application/wbxml',
            'wmlc'  => 'application/wmlc',
            'dcr'   => 'application/x-director',
            'dir'   => 'application/x-director',
            'dxr'   => 'application/x-director',
            'dvi'   => 'application/x-dvi',
            'gtar'  => 'application/x-gtar',
            'gz'    => 'application/x-gzip',
            'gzip'  => 'application/x-gzip',
            'php'   => 'application/x-httpd-php',
            'php4'  => 'application/x-httpd-php',
            'php3'  => 'application/x-httpd-php',
            'phtml' => 'application/x-httpd-php',
            'phps'  => 'application/x-httpd-php-source',
            'js'    => 'application/javascript',
            'swf'   => 'application/x-shockwave-flash',
            'sit'   => 'application/x-stuffit',
            'tar'   => 'application/x-tar',
            'tgz'   => 'application/x-tar',
            'z'     => 'application/x-compress',
            'xhtml' => 'application/xhtml+xml',
            'xht'   => 'application/xhtml+xml',
            'zip'   => 'application/x-zip',
            'rar'   => 'application/x-rar',
            'mid'   => 'audio/midi',
            'midi'  => 'audio/midi',
            'mpga'  => 'audio/mpeg',
            'mp2'   => 'audio/mpeg',
            'mp3'   => 'audio/mpeg',
            'aif'   => 'audio/x-aiff',
            'aiff'  => 'audio/x-aiff',
            'aifc'  => 'audio/x-aiff',
            'ram'   => 'audio/x-pn-realaudio',
            'rm'    => 'audio/x-pn-realaudio',
            'rpm'   => 'audio/x-pn-realaudio-plugin',
            'ra'    => 'audio/x-realaudio',
            'rv'    => 'video/vnd.rn-realvideo',
            'wav'   => 'audio/x-wav',
            'jpg'   => 'image/jpeg',
            'jpeg'  => 'image/jpeg',
            'jpe'   => 'image/jpeg',
            'png'   => 'image/png',
            'gif'   => 'image/gif',
            'bmp'   => 'image/bmp',
            'tiff'  => 'image/tiff',
            'tif'   => 'image/tiff',
            'svg'   => 'image/svg+xml',
            'css'   => 'text/css',
            'html'  => 'text/html',
            'htm'   => 'text/html',
            'shtml' => 'text/html',
            'txt'   => 'text/plain',
            'text'  => 'text/plain',
            'log'   => 'text/plain',
            'rtx'   => 'text/richtext',
            'rtf'   => 'text/rtf',
            'xml'   => 'application/xml',
            'xsl'   => 'application/xml',
            'mpeg'  => 'video/mpeg',
            'mpg'   => 'video/mpeg',
            'mpe'   => 'video/mpeg',
            'qt'    => 'video/quicktime',
            'mov'   => 'video/quicktime',
            'avi'   => 'video/x-msvideo',
            'movie' => 'video/x-sgi-movie',
            'doc'   => 'application/msword',
            'docx'  => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'dot'   => 'application/msword',
            'dotx'  => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'xlsx'  => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'word'  => 'application/msword',
            'xl'    => 'application/excel',
            'eml'   => 'message/rfc822',
            'json'  => 'application/json',
            'pem'   => 'application/x-x509-user-cert',
            'p10'   => 'application/x-pkcs10',
            'p12'   => 'application/x-pkcs12',
            'p7a'   => 'application/x-pkcs7-signature',
            'p7c'   => 'application/pkcs7-mime',
            'p7m'   => 'application/pkcs7-mime',
            'p7r'   => 'application/x-pkcs7-certreqresp',
            'p7s'   => 'application/pkcs7-signature',
            'crt'   => 'application/x-x509-ca-cert',
            'crl'   => 'application/pkix-crl',
            'der'   => 'application/x-x509-ca-cert',
            'kdb'   => 'application/octet-stream',
            'pgp'   => 'application/pgp',
            'gpg'   => 'application/gpg-keys',
            'sst'   => 'application/octet-stream',
            'csr'   => 'application/octet-stream',
            'rsa'   => 'application/x-pkcs7',
            'cer'   => 'application/pkix-cert',
            '3g2'   => 'video/3gpp2',
            '3gp'   => 'video/3gp',
            'mp4'   => 'video/mp4',
            'm4a'   => 'audio/x-m4a',
            'f4v'   => 'video/mp4',
            'webm'  => 'video/webm',
            'aac'   => 'audio/x-acc',
            'm4u'   => 'application/vnd.mpegurl',
            'm3u'   => 'text/plain',
            'xspf'  => 'application/xspf+xml',
            'vlc'   => 'application/videolan',
            'wmv'   => 'video/x-ms-wmv',
            'au'    => 'audio/x-au',
            'ac3'   => 'audio/ac3',
            'flac'  => 'audio/x-flac',
            'ogg'   => 'audio/ogg',
            'kmz'   => 'application/vnd.google-earth.kmz',
            'kml'   => 'application/vnd.google-earth.kml+xml',
            'ics'   => 'text/calendar',
            'zsh'   => 'text/x-scriptzsh',
            '7zip'  => 'application/x-7z-compressed',
            'cdr'   => 'application/cdr',
            'wma'   => 'audio/x-ms-wma',
            'jar'   => 'application/java-archive',
        ];
    }
}
