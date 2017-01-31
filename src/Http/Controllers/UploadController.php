<?php

namespace Soda\Cms\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Soda\Cms\Foundation\Uploader;
use Soda\Cms\Models\Media;

class UploadController extends BaseController
{
    public function upload(Request $request)
    {
        return (new Uploader)->fancyUpload($request);
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
