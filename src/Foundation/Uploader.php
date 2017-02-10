<?php

namespace Soda\Cms\Foundation;

use Soda\Cms\Models\Media;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class Uploader
{
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
        $return = [
            'error'                => null, // todo: what is this
            'initialPreview'       => ["<img src='$uploadedFilePath' width='120' /><input type='hidden' value='$uploadedFilePath' name='".$request->input('field_name')."' />"], // todo: not always an image
            'initialPreviewConfig' => [
                'caption' => $uploadedFilePath,
                'width'   => '120px',
                'append'  => true, // todo: check if this is necessary
            ],
        ];

        $table = $request->input('related_table');
        $field = $request->input('related_field');
        $id = $request->input('related_id');

        if ($request->input('multi') && $request->input('multi') == 'true') {
            $media = Media::create([
                'related_id'    => $id,
                'related_table' => $table,
                'related_field' => $field,
                'position'      => $request->input('file_id'),
                'media'         => $uploadedFilePath,
                'media_type'    => 'image', // todo: autodetect
            ]);

            $return['initalPreviewConfig']['key'] = $media->id;
            $return['initalPreviewConfig']['extra'] = [
                'key'           => $media->id,
                'related_table' => $table,
                'related_field' => $field,
                'related_id'    => $id,
            ];
        } else {
            DB::table($table)->where('id', $id)->update([
                $field => $uploadedFilePath,
            ]);

            $return['initalPreviewConfig']['key'] = null;
            $return['initalPreviewConfig']['extra'] = [
                'related_table' => $table,
                'related_field' => $field,
                'related_id'    => $id,
            ];
        }

        return $return;
    }
}
