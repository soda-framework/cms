<?php namespace Soda\Cms\Http\Controllers\Traits;

use Illuminate\Http\Request;

Trait TreeableTrait
{

    /*
     * render tree item
     */
    public function htmlTree($tree, $hint)
    {
        return view('soda::partials.tree.base', compact('tree', 'hint'));
    }

    /**
     * returns json object for use with stuff like jstree..
     *
     * @param Request $request
     * @param bool    $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function jsonTree(Request $request, $id = false)
    {
        $treeItems = $this->grabTree();
        if ($treeItems) {
            foreach ($treeItems as $treeItem) {
                $treeObj[] = $this->renderTreeJson($treeItem);
            }
        } else {
            return response()->json();
        }

        return response()->json($treeObj);
    }

    /**
     * Renders tree in a way that's consumable via jstree.
     *
     * @param $tree
     * @param $object
     *
     * @return mixed
     */
    public function renderTreeJson($tree_item, $object = null)
    {
        $object = $this->assignModelValues($tree_item, $object);

        $children_array = [];
        //TODO: vv is this polling db each time? is there a better way of handling this without throwing shitty errors?
        if (@$tree_item->hasChildren()) {
            //this item has children, we need to recurse
            foreach ($tree_item->children as $child) {
                $children_array[] = $this->renderTreeJson($child);
            }
            $object->children = $children_array;
        }

        return $object;
    }

    /**
     * move element into parent_id at position
     *
     * @param $id
     * @param $parent_id
     * @param $position
     *
     * @return string
     */
    public function move(Request $request, $parent_id = null, $id = null, $position = null)
    {
        if (!$parent_id) {
            $parent_id = $request->get('parent_id');
        }
        if (!$id) {
            $id = $request->get('id');
        }
        if (!$position) {
            $position = $request->get('position');
        }

        $item = $this->tree->find($id);

        if ($parent_id == 'root') {
            $parent = $this->tree->getRoots()->first();    //TODO: from application.
        } else {
            $parent = $this->tree->find($parent_id);
        }

        //re-handle slugging - TODO: should we make this optional?
        //$item->slug = $parent->generateSlug($item->name);

        if ($item->parent_id == $parent->id) {
            //we just want to re-order, not move the element.
            $item->position = $position;
            $item->save();
        }

        $item->moveTo($position, $parent->id);

        return 'true'; //todo - should return json?
    }

    public function deleteTree($id)
    {
        $item = $this->tree->find($id);
        $item->deleteSubtree(true);

        return redirect()->route('soda.'.$this->hint)->with('success', 'page updated');
    }

    /**
     * show the relevant create item form.
     *
     * @param Request $request
     * @param null    $parent_id
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function createForm(Request $request, $parent_id = null)
    {
        if ($parent_id) {
            $parent = $this->model->withoutGlobalScopes(['live'])->find($parent_id);
        } else {
            $parent = $this->model->getRoots()->first();
        }

        $this->model->parent_id = $parent->id;
        $this->model->page_type_id = $request->input('page_type_id');

        return view('soda::'.$this->hint.'.view', ['model' => $this->model, 'hint' => $this->hint, 'tree' => false]);
    }

    /**
     * pass in tree item and returns a jstree formatted object
     *
     * @param $tree_item
     * @param $object
     *
     * @return \stdClass
     */
    protected function assignModelValues($tree_item, $object = null)
    {
        if (!$object) {
            //this is the 1st run, we need to make an empty object;
            $object = new \stdClass();
        }
        $object->id = $tree_item->id;
        $object->text = $tree_item->name;

        return $object;
    }
}
