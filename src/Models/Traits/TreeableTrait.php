<?php

namespace Soda\Models\Traits;

use Illuminate\Support\Str;

trait TreeableTrait
{
    /*
     * get tree from given tree.
     */
    public function grabTree($id)
    {

        if (!isset($id) || !$id || $id == '#') {
            $treeModel = $this->getRoots()->first();
        } elseif ($id) {
            $treeModel = $this->where('id', $id)->first();
        }

        $tree_obj = [];// = $this->assignModelValues($page);
        //dd($treeModel->collectDescendants()->withoutGlobalScopes(['live'])->orderBy('position')->get()->toTree());
        //we need to get elements for each element in the tree.
        //TODO: see https://github.com/franzose/ClosureTable/issues/164
        return $treeModel->collectDescendants()->withoutGlobalScopes(['live'])->orderBy('position')->get()->toTree();
    }

}