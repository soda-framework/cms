<?php namespace Soda\Cms\Controllers;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Redirect;
use Route;
use Soda;
use Soda\Cms\Models\Block;
use Soda\Cms\Models\ModelBuilder;

class DynamicController extends Controller {
    protected $block;
    protected $model;

    public function __construct(ModelBuilder $modelBuilder) {
        $type = Route::current()->getParameter('type');
        $this->block = Block::with('type', 'type.fields')->where('identifier', $type)->first();
        $this->model = Soda::dynamicModel('soda_' . $this->block->type->identifier, $this->block->type->fields->lists('field_name')->toArray());
    }

    public function index() {
        $models = $this->model->all();

        return view($this->index_view, compact('models'));
    }

    public function view($type = null, $id = null) {
        $model = $id ? $this->model->findOrFail($id) : $this->model;

        return view('soda::standard.view', ['type' => $this->block->type, 'model' => $model]);
    }

    public function edit(Request $request, $type = null, $id = null) {
        $model = $id ? $this->model->findOrFail($id) : $this->model;

        foreach ($this->block->type->fields as $field) {
            $model->parseField($field, $request);
        }

        $model->block_id = $this->block->id;

        $model->save();

        return redirect()->route('soda.dyn.view', [
            'type' => $this->block->type->identifier,
            'id' => $model->id
        ])->with('success', 'updated!');
    }

    /**
     * delete
     *
     * @param Request $request
     * @param null $type
     * @param null $id
     */
    public function delete(Request $request, $type = null, $id = null) {
        $this->model = $this->model->findOrFail($id);
        $this->model->delete();

        return redirect()->back()->with('message', 'Success, item deleted'); //TODO: this should use nicer refirect?
    }

    /**
     * edits a specific field and value!
     *
     * @param $type
     * @param $id
     * @param $field
     */
    public function inlineEdit($type, $id, $field) {

        $this->model = $this->model->findOrFail($id);
        $this->model->{$field} = \Request::get($field);
        $this->model->save();
        //TODO: respnse?
    }
}
