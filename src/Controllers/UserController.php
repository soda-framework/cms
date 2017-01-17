<?php

namespace Soda\Controllers;

use Soda\Models\User;
use App\Http\Controllers\Controller;

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
