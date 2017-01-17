<?php

namespace Soda\Cms\Models\Traits;

trait TreeableTrait
{
    /*
     * get tree from given tree.
     */
    public function grabTree($id = null)
    {
        $treeModel = isset($id) && $id && $id !== '#' ? $this->where('id', $id)->first() : $this->getRoots()->first();

        // we need to get elements for each element in the tree.
        // TODO: see https://github.com/franzose/ClosureTable/issues/164
        return $treeModel ? $treeModel->collectDescendants()->withoutGlobalScopes(['live'])->orderBy('position')->get()->toTree() : [];
    }
}
