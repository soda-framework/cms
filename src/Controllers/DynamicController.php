<?php namespace Soda\Cms\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Redirect;
use Route;
use Soda;
use Soda\Cms\Models\Block;
use Soda\Cms\Models\ModelBuilder;
use Soda\Cms\Models\Page;

class DynamicController extends Controller {
    protected $page;
    protected $block;
    protected $model;

    public function __construct(ModelBuilder $modelBuilder) {
        $type = Route::current()->getParameter('type');
        $page_id = Route::current()->getParameter('page_id');
        $this->page = $page_id ? Page::find($page_id) : new Page;

        $block = $page_id ? $this->page->blocks() : new Block;
        $this->block = $block->with('type', 'type.fields')->where('identifier', $type)->first();
        $this->model = Soda::dynamicModel('soda_' . $this->block->type->identifier, $this->block->type->fields->lists('field_name')->toArray());
    }

    public function index() {
        $models = $this->model->all();
        $page = $this->page;

        return view($this->index_view, compact('models', 'page'));
    }

    public function view($page_id, $type, $id = null) {
        if(!$id && isset($this->block->pivot) && !$this->block->pivot->can_create) {
            // No permission
            dd('Not allowed');
        }

        $model = $id ? $this->model->findOrFail($id) : $this->model;

        return view('soda::standard.view', ['block' => $this->block, 'model' => $model, 'page' => $this->page]);
    }

    public function edit(Request $request, $page_id, $type, $id = null) {
        if(!$id && isset($this->block->pivot) && !$this->block->pivot->can_create) {
            // No permission
            dd('Not allowed');
        }

        $model = $id ? $this->model->findOrFail($id) : $this->model;

        foreach ($this->block->type->fields as $field) {
            $model->parseField($field, $request);
        }

        $model->block_id = $this->block->id;

        if (!$this->block->is_shared && $this->page) {
            $model->page_id = $this->page->id;
            $model->is_shared = false;
        } else {
            $model->is_shared = true;
        }

        $model->save();

        return redirect()->route('soda.page.block.view', [
            'page_id' => $this->page->id,
            'type'    => $this->block->identifier,
            'id'      => $model->id,
        ])->with('success', 'updated!');
    }

    /**
     * delete
     *
     * @param Request $request
     * @param null $type
     * @param null $id
     */
    public function delete(Request $request, $page_id, $type, $id = null) {
        if(isset($this->block->pivot) && !$this->block->pivot->can_delete) {
            // No permission
            dd('Not allowed');
        }

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
    public function inlineEdit($page_id, $type, $id, $field) {

        $this->model = $this->model->findOrFail($id);
        $this->model->{$field} = \Request::get($field);
        $this->model->save();
        //TODO: respnse?
    }
}
