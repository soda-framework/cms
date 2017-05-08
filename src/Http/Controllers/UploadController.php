<?php

namespace Soda\Cms\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Soda\Cms\Database\Models\Media;
use Soda\Cms\Foundation\Uploads\Uploader;

class UploadController extends BaseController
{
    // pass a file object from request
    public function upload(Request $request)
    {
        $interventionConfig = json_decode($request->input('intervention'), true);
        $appendedPath = $request->input('uploadSubDir');

        return (new Uploader)->fancyUpload($request, $interventionConfig ?: [], $appendedPath);
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
