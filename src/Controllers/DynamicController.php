<?php namespace Soda\Controllers;

//maybe this should be renamed to be block/page specific?

use Redirect;
use Soda\Models\BlockType;
use Soda\Models\ModelBuilder;
use App\Http\Controllers\Controller;

class DynamicController extends Controller
{


    public $model = null;

    public function __construct(ModelBuilder $modelBuilder)
    {
        $this->type = BlockType::with('fields')->where('identifier',\Route::current()->getParameter('type'))->first();
        $this->model = \Soda::dynamicModel('soda_' . $this->type->identifier,
            $this->type->fields->lists('field_name')->toArray());
    }

    public function index()
    {
        $this->model = $this->model->all();
        return view($this->index_view, ['models' => $this->model]);
    }

    public function view($type = null, $id = null)
    {
        if ($id) {
            $model = $this->model->findOrFail($id);
        } else {
            $model = $this->model;
        }
        return view('soda::standard.view', ['type' => $this->type, 'model' => $model]);
    }

    public function edit($type = null, $id = null)
    {
        if ($id) {
            $this->model = $this->model->findOrFail($id);
        }

        //TODO: investigate this forceFill more - why can I not set this during runtime?
        $this->model->forceFill(\Request::except(['_token']));
        $this->model->save();

        return redirect()->route('soda.dyn.view',
            ['type' => $this->type->identifier, 'id' => $this->model->id])->with('success', 'updated!');
    }

    /**
     * edits a specific field and value!
     * @param $type
     * @param $id
     * @param $field
     */
    public function inlineEdit($type, $id, $field)
    {
        $this->model = $this->model->findOrFail($id);
        $this->model->{$field} = \Request::get($field);
        $this->model->save();
        //TODO: respnse?
    }
}