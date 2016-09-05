<?php namespace Soda\Cms\Controllers;

use Soda;
use App\Http\Controllers\Controller;
use Soda\Cms\Models\BlockType;
use Soda\Cms\Controllers\Traits\CrudableTrait;
use Illuminate\Http\Request;

class BlockTypeController extends Controller {

    use CrudableTrait;
    protected $hint = 'block_type';

    public function __construct(BlockType $type) {
        $this->model = $type;
    }

    public function edit(Request $request, $id = null) {
        if ($id) {
            $this->model = $this->model->findOrFail($id);
        }

        if(!$request->has('status')) $request->merge(['status' => 0]);
        $this->model->fill($request->except('fields'));

        $this->model->application_id = Soda::getApplication()->id;
        if($fields = $request->input('fields')) {
            $this->model->fields()->sync($fields);
        }
        $this->model->save();

        return redirect()->route('soda.' . $this->hint . '.view', ['id' => $this->model->id])->with('success',
            'updated');
    }

}
