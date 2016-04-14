<?php namespace Soda\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Soda\Models\User;

class UserController extends Controller
{

    use    Traits\CrudableTrait;

    public $model = null;
    public $index_view = 'soda::standard.index';
    public $view_view = 'soda::standard.view';

    public function __construct(User $user)
    {
        //$this->middleware('auth');
        $this->model = $user;
    }


}