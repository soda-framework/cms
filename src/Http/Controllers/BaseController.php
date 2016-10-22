<?php

namespace Soda\Cms\Http\Controllers;

use Exception;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller;

abstract class BaseController extends Controller
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function handleException(Exception $e, $message)
    {
        \Log::error($e);

        return $this->handleError($message.': '.$e->getMessage());
    }

    public function handleError($message)
    {
        return redirect()->back()->withInput()->with('error', $message);
    }
}
