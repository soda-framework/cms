<?php namespace Soda\Cms\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Soda;
use Soda\Cms\Controllers\Traits\CrudableTrait;
use Soda\Cms\Models\BlockType;

class BlockTypeController extends Controller
{

    use CrudableTrait;
    protected $hint = 'block_type';

    public function __construct(BlockType $type)
    {
        $this->model = $type;
    }

    public function edit(Request $request, $id = null)
    {
        if ($id) {
            $this->model = $this->model->findOrFail($id);
        }

        $this->model->fill($request->except('fields'));

        $this->model->application_id = Soda::getApplication()->id;
        if ($fields = $request->input('fields')) {
            $this->model->fields()->sync($fields);
        }
        $this->model->save();

        return redirect()->route('soda.'.$this->hint.'.view', ['id' => $this->model->id])->with('success',
            'updated');
    }

}
