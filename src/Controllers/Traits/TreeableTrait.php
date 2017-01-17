<?php

namespace Soda\Controllers\Traits;

use Illuminate\Http\Request;

trait TreeableTrait
{
    //get tree from given model.
    private function grabTree($id = false)
    {
        if (! isset($id) || ! $id || $id == '#') {
            $treeModel = $this->model->getRoots()->first();    //todo: from application.
        } elseif ($id) {
            $treeModel = $this->model->where('id', $id)->first();
        } elseif ($request->input('id')) {
            $treeModel = $this->model->where('id', $request->input('id'))->first();
        }

        $tree_obj = []; // = $this->assignModelValues($page);

        //we need to get elements for each element in the tree.
        //TODO: see https://github.com/franzose/ClosureTable/issues/164
        return $treeModel->collectDescendants()->withoutGlobalScopes(['live'])->orderBy('position')->get()->toTree();
    }

    public function htmlTree($id = false)
    {
        return view('soda::tree.base', ['tree' => $this->grabTree()]);
    }

    /**
     * returns json object for use with stuff like jstree..
     * @param Request $request
     * @param bool $id
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
     * pass in tree item and returns a jstree formatted object.
     * @param $tree_item
     * @param $object
     * @return \stdClass
     */
    protected function assignModelValues($tree_item, $object = null)
    {
        if (! $object) {
            //this is the 1st run, we need to make an empty object;
            $object = new \stdClass();
        }
        $object->id = $tree_item->id;
        $object->text = $tree_item->name;

        return $object;
    }

    /**
     * Renders tree in a way that's consumable via jstree.
     * @param $tree
     * @param $object
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
     * move element into parent_id at position.
     * @param $id
     * @param $parent_id
     * @param $position
     * @return string
     */
    public function move($id, $parent_id, $position)
    {
        $item = $this->model->find($id);

        if ($parent_id == 'root') {
            $parent = $this->model->getRoots()->first();    //TODO: from application.
        } else {
            $parent = $this->model->find($parent_id);
        }

        //re-handle slugging - TODO: should we make this optional?
        $item->slug = $parent->generateSlug($item->name);

        if ($item->parent_id == $parent_id) {
            //we just want to re-order, not move the element.
            $item->position = $position;
            $item->save();

            return 'true';
        }

        $item->moveTo($position, $parent_id);

        return 'true'; //todo - should return json?
    }

    public function delete($id)
    {
        $item = $this->model->find($id);
        $item->deleteSubtree(true);

        return 'true';
    }

    /**
     * Renders a tree from a given node.. for use in jstree.
     * @param  [type]  $tree  [description]
     * @param  int $depth current depth - used to see where to put children nodes.
     * @return [type]         [description]
     */
    public function renderTree($tree, $depth = 0)
    {
        if (! $tree->hide_in_tree) {
            $depth++;
            $branch = new \stdClass();
            $branch->id = $tree->id;

            $branch->text = $tree->name;

            $branch->a_attr = new \stdClass();
            if ($tree->hide_in_tree === false && config('bootlegcms.cms_debug')) {
                $branch->a_attr->class = 'text-danger';
            } else {
                $branch->a_attr->class = '';
            }

            if ($tree->edit_action) {
                $branch->a_attr->href = action($tree->edit_action, [$tree->id]);
            } else {
                if ($this->content_mode == 'contents') {
                    $branch->a_attr->href = action('\Bootleg\Cms\ContentsController@anyEdit', [$tree->id]);
                } else {
                    $branch->a_attr->href = action('\Bootleg\Cms\TemplateController@anyEdit', [$tree->id]);
                }
            }

            $branch->children = [];
            //$branch->children = ($tree->rgt - $tree->lft > 1);
            // dd($tree->hide_children);
            if (count($tree->children)) {
                foreach ($tree->children as $child) {
                    if (! $child->hide_in_tree) {
                        $c = $this->renderTree($child, $depth);

                        $branch->children[] = $c;
                    } else {
                        $branch->children = false;
                    }
                }
            } else {
                if ($depth <= config('bootlegcms.cms_tree_descendents')) {
                    //we don't know if there's anymore children.. so assume there is
                    $branch->children = true;
                }
            }

            return $branch;
        }

        return false;
    }
}
