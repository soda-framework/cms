<?php

namespace Soda\Cms\Controllers;

use Auth;
use Illuminate\Http\Request;
use Soda\Cms\Models\Navigation;
use Soda\Cms\Models\NavigationItem;
use App\Http\Controllers\Controller;
use Soda\Cms\Controllers\Traits\CrudableTrait;
use Soda\Cms\Controllers\Traits\TreeableTrait;

class NavigationController extends Controller
{
    use CrudableTrait, TreeableTrait;
    public $hint = 'navigation';

    public function __construct(NavigationItem $navigation_item)
    {
        //$this->middleware('auth');
        $this->model = $navigation_item;
        $this->tree = $navigation_item;
    }

    public function index()
    {
        $filter = \DataFilter::source($this->model->collectRoots());
        $filter->add('name', 'name', 'text');
        $filter->submit('Search');
        $filter->reset('Clear');
        $filter->build();

        $grid = \DataGrid::source($filter);  //same source types of DataSet
        $grid->add('name', 'Name', true); //field name, label, sortable
        $grid->add('description', 'Description', true); //field name, label, sortable
        $grid->add('{{ $id }}', 'Options')->cell(function ($value) {
            $edit = "<a href='".route('soda.'.$this->hint.'.edit',
                    [$value])."' class='btn btn-warning'><span class='fa fa-pencil'></span> Edit</a> ";
            $edit .= "<a href='".route('soda.'.$this->hint.'.delete',
                    [$value])."' class='btn btn-danger'><span class='fa fa-pencil'></span> Delete</a>";

            return $edit;
        });
        $grid->orderBy('id', 'desc'); //default orderby
        $grid->paginate(10)->getGrid('soda::partials.grid');
        $hint = $this->hint;

        return view('soda::'.$this->hint.'.index', compact('filter', 'grid', 'hint'));
    }

    public function deleteTree($id)
    {
        $item = $this->tree->find($id);
        $root_id = $item->getAncestorsWhere('real_depth', '=', 0)->first()->id;
        $item->deleteSubtree(true);

        //we redirect back to the root menu
        return redirect()->route('soda.'.$this->hint.'.view', ['id' => $root_id])->with('success', 'deleted');
    }

    public function view(Request $request, $id = null)
    {

//        $parent = NavigationItem::find($id);
//
//        $b = new NavigationItem();
//        $b->name = 'blargh3';
//        $b->navigation_id = 1;
//
//        $parent->addChild($b);
//dd('done');

        if ($id) {
            $this->model = $this->model->find($id);
        }

        $model = $this->model;
        $hint = $this->hint;
        if ($model->isRoot()) {
            $tree = $this->htmlTree($request, $model->id, $this->hint);
        } else {
            $tree = false;
        }

        return view('soda::'.$this->hint.'.view', compact('model', 'hint', 'tree'));
    }

    public function edit(Request $request, $id = null)
    {
        if ($id) {
            $this->model = $this->model->findOrFail($id);
        }
        if ($request->get('parent_id')) {
            $parent = $this->model->find($request->get('parent_id'));
        }

        //we create a new menu block
        $this->model->fill($request->input());
        $this->model->application_id = \Soda::getApplication()->id;
        if (@$parent && ! $this->model->id) {
            //create a new item and move it into a parent.
            //we need to move this item into the parent.
            $parent->addChild($this->model);
        } else {
            //otherwise, jsut assuem this is a root element I guess.
            $this->model->save();
        }

        return redirect()->route('soda.'.$this->hint.'.view', ['id' => $this->model->id])->with('success',
            'updated');
    }

    public function createForm(Request $request, $parent_id = null)
    {
        if ($parent_id) {
            $parent = $this->model->withoutGlobalScopes(['live'])->find($parent_id);
            $this->model->parent_id = $parent->id;
        } else {
            $this->model->parent_id = null;
        }

        return view('soda::'.$this->hint.'.view', [
            'model' => $this->model,
            'hint'  => $this->hint,
            'tree'  => false,
        ]);
    }
}
