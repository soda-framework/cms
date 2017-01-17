<?php

namespace Soda\Cms\Controllers;

use URL;
use Storage;
use Soda\Cms\Models\Media;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UploadController extends Controller
{
    // pass a file object from request
    public function upload(Request $request)
    {
        $driver = config('soda.upload.driver');
        $url_prefix = trim(config('soda.uploder.folder'), '/');

        if ($request->hasFile('file')) {
            $files = $request->file('file');
            $return = [];
            foreach ($files as $file) {
                if ($file->isValid()) {
                    // Name the file and place in correct directory
                    $unique = uniqid();
                    $path_info = pathinfo($file->getClientOriginalName());
                    $final_path = ltrim($url_prefix.'/', '/').$path_info['filename'].'__'.$unique;
                    if ($path_info['extension']) {
                        $final_path .= '.'.$path_info['extension'];
                    }

                    // Upload the file
                    $uploaded = Storage::disk($driver)->put(
                        $final_path,
                        file_get_contents($file->getRealPath()), 'public'
                    );

                    // Generate return information
                    if ($uploaded) {
                        $url = $driver == 'soda.public' ? URL::to('uploads/'.$final_path) : Storage::disk($driver)->url(trim($final_path, '/'));

                        $return = [
                            'error'                => null,
                            'initialPreview'       => ["<img src='$url' width='120' /><input type='hidden' value='$url' name='".$request->input('field_name')."' />"],
                            'initialPreviewConfig' => [
                                'caption' => $url,
                                'width'   => '120px',
                                'append'  => true,
                            ],
                        ];

                        if ($request->has('related_id')) {
                            $media = Media::create([
                                'related_id'    => $request->input('related_id'),
                                'related_table' => $request->input('related_table'),
                                'related_field' => $request->input('related_field'),
                                'position'      => $request->input('file_id'),
                                'media'         => $url,
                                'media_type'    => 'image',
                            ]);

                            $return['initalPreviewConfig']['key'] = $media->id;
                            $return['initalPreviewConfig']['extra'] = [
                                'key'           => $media->id,
                                'related_table' => $media->related_table,
                                'related_id'    => $media->related_id,
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

    public function delete(Request $request)
    {
        if ($request->has('key')) {
            $image = Media::find($request->input('key'));
            if ($image) {
                $image->delete();

                return json_encode(true);
            }
        }

        return json_encode(['error' => 'Unable to delete image, please refresh and try again']);
    }
}
