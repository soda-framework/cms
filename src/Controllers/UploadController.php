<?php namespace Soda\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Storage;
use Soda\Models\Media;
use Soda\Models\Upload;

class UploadController extends Controller
{

    private $url_prefix = '';

    public function __construct()
    {
        //$this->middleware('auth');
        $this->url_prefix = trim(config('soda.upload_folder'), '/');
    }

    public function postTest(Request $request)
    {
        $unique = uniqid();     //we need unique identifier for each item to try
        // and prevent duplicate issues
        if ($request->hasFile('file')) {

            $file = $request->file('file');

            Storage::put(
                'simon/bleh',
                file_get_contents($request->file('avatar')->getRealPath())
            );

            if ($file->isValid()) {
                $file = \Uploader::upload($file);
            }
        } else {
            // TODO Should do some exception catching here
            dd('something went wrong, no file');
        }

    }

    public function getTest()
    {
        return view('soda::upload_test');
    }

    /**
     * retrieves uploads for encoding into json.
     */
    public function retrieveUpload(Request $request)
    {
        $files = $request->get('files');
        $uploads = Upload::where(function ($q) use ($files) {
            foreach ($files as $file) {
                $q->orWhere('original_file_url', $file);
            }
        })->get();

        return response()->json($uploads);
    }

    // pass a file object from request
    public function upload(Request $request)
    {
        if ($request->hasFile('file')) {
            $files = $request->file('file');
            $return = [];
            foreach ($files as $file) {
                if ($file->isValid()) {
                    $unique = uniqid();
                    $path_info = pathinfo($file->getClientOriginalName());
                    $final_path = $this->url_prefix . '/' . $path_info['filename'] . '__' . $unique;
                    if ($path_info['extension']) {
                        $final_path .= '.' . $path_info['extension'];
                    }
                    $uploaded = Storage::disk(config('soda.uploader'))->put(
                        $final_path,
                        file_get_contents($file->getRealPath()), 'public'
                    );
                    if ($uploaded) {
                        $url = config('soda.upload_domain') . '/' . $final_path;
                        //upload succesful - we want to try and see if there's a specific url we can load these from?
                        //we want to add this to the uploads db.
                        $upload = new Upload;
                        $upload->file_url = $url;
                        //TODO: other meta junk in here.. caption, x,y,etc etc.
                        $upload->save();
                        $return = new \stdClass();
                        $return->error = NULL;
                        $return->initialPreview = ["<img src='$url' width='120' /><input type='hidden' value='$url' name='" . $request->input('field_name') . "' />"];
                        $preview = new \stdClass();
                        $preview->caption = $url;
                        $preview->width = '120px';
                        $return->append = true;

                        if ($request->has('related_id')) {
                            $media = Media::create([
                                'related_id' => $request->input('related_id'),
                                'related_table' => $request->input('related_table'),
                                'related_field' => $request->input('related_field'),
                                'position' => $request->input('file_id'),
                                'media' => $url,
                                'media_type' => 'image'
                            ]);
                            $media->save();
                            $config = new \stdClass();
                            $config->key = $media->id;
                            $config->related_table = $media->related_table;
                            $config->related_id = $media->related_id;
                            $preview->extra = [$config];
                        }
                        $return->initialPreviewConfig = [$preview];
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
       if($request->has('key')){
           $image = Media::find($request->input('key'));
           if($image){
               $image->delete();
               return json_encode(true);
           }
       }
       return json_encode(['error' => 'Unable to delete image, please refresh and try again']);
    }

}
