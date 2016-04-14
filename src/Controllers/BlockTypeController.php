<?php namespace Soda\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Soda\Models\BlockType;


class BlockTypeController extends Controller
{

    use Traits\CrudableTrait;
    public $type = 'block_type';
    public $index_view = 'soda::crudable.index';
    public $view_view = 'soda::crudable.view';

    public function __construct(BlockType $type)
    {
        //$this->middleware('auth');
        $this->model = $type;
    }

    /**
     * Create a block type.. and related tables.
     */
    public function blockcreate(){

        //fields to come from form (with list view to select fields)
        $fields = [48,50,10];


        $type = new BlockType();
        $type->name="yoyo";
        $type->description = 'desc';
        $type->application_id = '1';
        $type->identifier="yoyo";
        $type->status_id="1";
        $type->edit_action="view";
        $type->edit_action_type="soda::blocks.index";
        //$type->fields()->attach
        //derp.
        $type->save();
        $type->addType();
    }

}