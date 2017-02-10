<?php

namespace Soda\Cms\Http\Controllers;

use Soda\Cms\Models\Media;
use Illuminate\Http\Request;
use Soda\Cms\Foundation\Uploader;
use Illuminate\Support\Facades\DB;

class UploadController extends BaseController
{
    public function upload(Request $request)
    {
        $interventionConfig = json_decode($request->input('intervention'), true);

        return (new Uploader)->fancyUpload($request, $interventionConfig ?: []);
    }

    public function tinyUpload(Request $request)
    {
        $interventionConfig = json_decode($request->input('intervention'), true);

        $uploadedFilePath = (new Uploader)->uploadFile($request->file('file'), $interventionConfig ?: []);

        if ($uploadedFilePath) {
            return response()->json(['location' => $uploadedFilePath]);
        }

        return response()->json(['error' => 'File upload failed'], 422);
    }

    public function delete(Request $request)
    {
        if ($request->has('key') && $request->input('key') !== null) {
            return $this->deleteByKey($request->input('key'));
        } else {
            return $this->deleteByTableRow($request->input('related_table'), $request->input('related_field'), $request->input('related_id'));
        }

        return $this->errorDeleting();
    }

    protected function deleteByKey($key)
    {
        $image = Media::find($key);
        if ($image) {
            $image->delete();

            return json_encode(true);
        }

        return $this->errorDeleting();
    }

    protected function deleteByTableRow($table, $field, $id)
    {
        if ($table && $field && $id) {
            DB::table($table)->where('id', $id)->update([
                $field => '',
            ]);

            return json_encode(true);
        }

        return $this->errorDeleting();
    }

    protected function errorDeleting()
    {
        return json_encode(['error' => 'Unable to delete image, please refresh and try again']); // todo: not always an image
    }
}
