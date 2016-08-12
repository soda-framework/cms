<?php namespace Soda\Controllers;

//maybe this should be renamed to be block/page specific?

use Carbon\Carbon;
use Redirect;
use Soda\Models\BlockType;
use Soda\Models\ModelBuilder;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DynamicController extends Controller
{
    public $model = null;

    public function __construct(ModelBuilder $modelBuilder)
    {
        $this->type = BlockType::with('fields')->where('identifier', \Route::current()->getParameter('type'))->first();
        $this->model = \Soda::dynamicModel('soda_' . $this->type->identifier, $this->type->fields->lists('field_name')->toArray());
    }

    public function index()
    {
        $this->model = $this->model->all();

        return view($this->index_view, ['models' => $this->model]);
    }

    public function view($type = null, $id = null)
    {
        dd($this->model);
        if ($id) {
            $model = $this->model->findOrFail($id);
        } else {
            $model = $this->model;
        }
        return view('soda::standard.view', ['type' => $this->type, 'model' => $model]);
    }

    public function edit(Request $request, $type = null, $id = null)
    {

        if ($id) {
            $this->model = $this->model->findOrFail($id);
        }

        //we pull out the fields being saved..
        //dd($this->type->fields);

        //TODO: this shiz should be in model??
        foreach ($this->type->fields as $field) {
            //we have some default mutators for sensible stuff:

            if ($field->field_type == 'datetime') {
                //TODO: parse format through from field params?
                //TODO: timezone parse through from field params
                if ($request->input($field->field_name)) {
                    $this->model->{$field->field_name} = Carbon::createFromFormat('m/d/Y g:i A',
                        $request->input($field->field_name));
                } else {
                    //else if it's not set (or blank) do we want to run some special stuff here?
                    //could and should this be moved to outside the above if statement (could cause problems with blank fields ..like checkboxes etc)
                }
            } else {
                //default, just chuck in the values.
                $this->model->{$field->field_name} = $request->input($field->field_name);
            }

        }

        //dd($request->except(['_token']));
        //TODO: investigate this forceFill more - why can I not set this during runtime?

        //TODO: we should be serialsing any arrays that appear in here or at least figuring out how to save them properly
        //$this->model->forceFill($request->except(['_token', 'file']));

        $this->model->save();

        return redirect()->route('soda.dyn.view',
            ['type' => $this->type->identifier, 'id' => $this->model->id])->with('success', 'updated!');
    }

    /**
     * delete
     * @param Request $request
     * @param null $type
     * @param null $id
     */
    public function delete(Request $request, $type = null, $id = null)
    {
        $this->model = $this->model->findOrFail($id);
        $this->model->delete();
        return \Redirect::back()->with('message', 'Success, item deleted'); //TODO: this should use nicer refirect?
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
