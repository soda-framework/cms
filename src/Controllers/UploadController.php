<?php namespace Soda\Controllers;

use App\Http\Controllers\Controller;
use \Illuminate\Http\Request;
use Storage;
use Soda\Models\Upload;

class UploadController extends Controller
{

    public function __construct()
    {
        //$this->middleware('auth');
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
    public function retrieveUpload(Request $request){
        $files = $request->get('files');
        $uploads = Upload::where(function($q) use ($files){
            foreach($files as $file){
                $q->orWhere('original_file_url',$file);
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
            foreach($files as $file){
                if ($file->isValid()) {
                    $unique = uniqid();
                    $path_info = pathinfo($file->getClientOriginalName());
                    $final_path = trim(config('soda.upload_folder'), '/') .'/'. $path_info['filename']. '__' . $unique;
                    if($path_info['extension']){
                        $final_path .= '.'.$path_info['extension'];
                    }
                    $uploaded = Storage::disk(config('soda.uploader'))->put(
                        $final_path,
                        file_get_contents($file->getRealPath()), 'public'
                    );
                    if($uploaded){
                        $url = config('soda.upload_domain').'/'.$final_path;
                        //upload succesful - we want to tery and see if there's a specific url we can load these from?

                        //we want to add this to the uploads db.
                        $upload = new Upload;
                        $upload->file_url = $url;
                        //TODO: other meta junk in here.. caption, x,y,etc etc.
                        $upload->save();
                        $return = new \stdClass();
                        $return->error = NULL;
                        //TODO: move this into nicer place - not html in the controller dude.
                        $return->initialPreview = ["<img src='$url' width='120' /><input type='hidden' value='$url' name='".$request->input('field_name')."' />"];
                        $preview = new \stdClass();
                        $preview->caption = $url;
                        $preview->width = '120px';
                        $return->append = false;
                        $return->initialPreviewConfig = [$preview];
                    }
                }
                else{
                    dd('file not valid??');
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

    public function delete()
    {
        return response()->json();
    }

}