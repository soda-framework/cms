<?php namespace Soda\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ApplicationController extends Controller {

	public function anyIndex() {
		dd('get applications');
	}

}