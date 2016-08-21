<?php namespace Soda\Cms\Controllers;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Redirect;
use Route;
use Soda;
use Soda\Cms\Models\BlockType;
use Soda\Cms\Models\ModelBuilder;

class DynamicController extends Controller {
    public function __construct(ModelBuilder $modelBuilder) {
        $type = Route::current()->getParameter('type');
        $this->type = BlockType::with('fields')->where('identifier', $type)->first();
        $this->model = Soda::dynamicModel('soda_' . $this->type->identifier, $this->type->fields->lists('field_name')->toArray());
    }

    public function index() {
        $models = $this->model->all();

        return view($this->index_view, compact('models'));
    }

    public function view($type = null, $id = null) {
        $model = $id ? $this->model->findOrFail($id) : $this->model;

        return view('soda::standard.view', ['type' => $this->type, 'model' => $model]);
    }

    public function edit(Request $request, $type = null, $id = null) {
        $model = $id ? $this->model->findOrFail($id) : $this->model;

        foreach ($this->type->fields as $field) {
            $model->parseField($field, $request);
        }

        $model->save();

        return redirect()->route('soda.dyn.view', [
            'type' => $this->type->identifier,
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

        return \Redirect::back()->with('message', 'Success, item deleted'); //TODO: this should use nicer refirect?
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
