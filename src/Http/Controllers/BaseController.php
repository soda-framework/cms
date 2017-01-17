<?php

namespace Soda\Cms\Http\Controllers;

use Exception;
use Illuminate\Routing\Controller;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

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
