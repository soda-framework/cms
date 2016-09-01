<?php namespace Soda\Cms\Controllers;

use App\Http\Controllers\Controller;
use DB;
use Illuminate\Http\Request;
use Soda\Cms\Models\Media;
use Storage;
use Uploader;
use URL;

class UploadController extends Controller {
    // pass a file object from request
    public function upload(Request $request) {
        $driver = config('soda.upload.driver');
        $url_prefix = trim(config('soda.upload.folder'), '/');

        if ($request->hasFile('file')) {
            $files = $request->file('file');
            $return = [];
            foreach ($files as $file) {
                if ($file->isValid()) {
                    // Name the file and place in correct directory
                    $unique = uniqid();
                    $path_info = pathinfo($file->getClientOriginalName());
                    $final_path = ltrim($url_prefix . '/', '/') . $path_info['filename'] . '__' . $unique;
                    if ($path_info['extension']) {
                        $final_path .= '.' . $path_info['extension'];
                    }

                    // Upload the file
                    $uploaded = Storage::disk($driver)->put(
                        $final_path,
                        file_get_contents($file->getRealPath()), 'public'
                    );

                    // Generate return information
                    if ($uploaded) {
                        $url = $driver == 'soda.public' ? URL::to('uploads/' . $final_path) : Storage::disk($driver)->url(trim($final_path, '/'));

                        $return = [
                            "error"                => null,
                            "initialPreview"       => ["<img src='$url' width='120' /><input type='hidden' value='$url' name='" . $request->input('field_name') . "' />"],
                            "initialPreviewConfig" => [
                                "caption" => $url,
                                "width"   => "120px",
                                "append"  => true,
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
                                'media'         => $url,
                                'media_type'    => 'image',
                            ]);

                            $return["initalPreviewConfig"]["key"] = $media->id;
                            $return["initalPreviewConfig"]["extra"] = [
                                "key"           => $media->id,
                                "related_table" => $table,
                                'related_field' => $field,
                                "related_id"    => $id,
                            ];
                        } else {
                            DB::table($table)->where('id', $id)->update([
                                $field => $url
                            ]);

                            $return["initalPreviewConfig"]["key"] = null;
                            $return["initalPreviewConfig"]["extra"] = [
                                "related_table" => $table,
                                'related_field' => $field,
                                "related_id"    => $id,
                            ];
                        }
                    }
                } else {
                    dd('file not valid??');  //TODO: REMOVE DD, HANDLE ERRORS BETTER
                }
            }

            //return with a json object containing our shiz.. there might be a nicer way of extracting this functionality elswhere?
            return response()->json($return);
        } else {
            // TODO Should do some exception catching here
            dd('something went wrong, no file');
        }
        //incoming file
        //CALL uploading scripts..
    }

    public function delete(Request $request) {
        if ($request->has('key') && $request->input('key')) {
            $image = Media::find($request->input('key'));
            if ($image) {
                $image->delete();

                return json_encode(true);
            }
        } else {
            $table = $request->input('related_table');
            $field = $request->input('related_field');
            $id = $request->input('related_id');

            if($table && $field && $id) {
                DB::table($table)->where('id', $id)->update([
                    $field => ''
                ]);

                return json_encode(true);
            }
        }

        return json_encode(['error' => 'Unable to delete image, please refresh and try again']);
    }

}
