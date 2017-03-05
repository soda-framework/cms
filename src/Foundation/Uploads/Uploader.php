<?php

namespace Soda\Cms\Foundation\Uploads;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Soda\Cms\Database\Fields\Models\Media;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class Uploader
{
    public function uploadFile(UploadedFile $file, array $transformConfig = [], $appendedPath)
    {
        if ($file->isValid()) {
            $fileToUpload = new FileToUpload($file);

            if ($appendedPath) {
                $fileToUpload->appendToUploadPath($appendedPath);
            }

            if ($transformConfig) {
                $fileToUpload->setTransformConfig($transformConfig)->transform();
            }

            $uploadFilePath = $fileToUpload->uploadPath();

            // Generate return information
            if ($this->driver()->put($uploadFilePath, $fileToUpload->fileContents(), 'public')) {
                if (config('filesystems.disks.'.config('soda.upload.driver').'.driver') == 'local') {
                    return substr($this->driver()->getAdapter()->applyPathPrefix($uploadFilePath), strlen(public_path()));
                }

                return $this->driver()->url($uploadFilePath);
            }
        }

        return false;
    }

    public function fancyUpload(Request $request, array $transformConfig = [], $appendedPath)
    {
        if ($request->hasFile('file')) {
            foreach ($request->file('file') as $file) {
                if ($file->isValid()) {
                    $uploadedFile = $this->uploadFile($file, $transformConfig, $appendedPath);

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
