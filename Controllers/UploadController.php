<?php namespace Soda\Controllers;

use App\Http\Controllers\Controller;
use \Illuminate\Http\Request;
use Soda\Models\PageClosure;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class UploadController extends Controller
{

    public function __construct()
    {
        //$this->middleware('auth');
    }

    public function postTest(Request $request)
    {
        if($request->hasFile('file'))
        {

            $file = $request->file('file');

            if($file->isValid())
            {
                $file = \Uploader::upload($file);
            }
        }
        else
        {
            // TODO Should do some exception catching here
            dd('something went wrong, no file');
        }

    }

    public function getTest()
    {
        return view('soda::upload_test');
    }

    // pass a file object from request
    public function upload(UploadedFile $file)
    {
        //incoming file
        //CALL uploading scripts..
    }

    public function delete()
    {
        //incoming file
        dd('delete.');
    }

}