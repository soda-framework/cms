<?php

namespace Soda\Controllers;

/******************************
 * /*NOTE:: THIS is no longer used at all....
 * /*/

use Soda\Models\Page;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TreeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //		$this->middleware('soda');
    }

    public function htmlTree(Request $request, $id = false)
    {
        if (! isset($id) || ! $id || $id == '#') {
            $page = Page::getRoots()->first();    //todo: from application.
        } elseif ($id) {
            $page = Page::where('id', $id)->first();
        } elseif ($request->input('id')) {
            $page = Page::where('id', $request->input('id'))->first();
        }

        $tree_obj = []; // = $this->assignModelValues($page);

        //we need to get elements for each element in the tree.
        //TODO: see https://github.com/franzose/ClosureTable/issues/164
        $tree_items = $page->collectDescendants()->withoutGlobalScopes(['live'])->get()->toTree();

        return view('soda::tree.base', ['tree' => $tree_items]);
        //$tree_obj = $this->renderTreeHtml($tree_items);

        //return response()->json($tree_obj);
    }

    /**
     * returns json object for use with stuff like jstree..
     * @param Request $request
     * @param bool $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function jsonTree(Request $request, $id = false)
    {
        if (! isset($id) || ! $id || $id == '#') {
            $page = Page::getRoots()->first();    //todo: from application.
        } elseif ($id) {
            $page = Page::where('id', $id)->first();
        } elseif ($request->input('id')) {
            $page = Page::where('id', $request->input('id'))->first();
        }

        $tree_obj = []; // = $this->assignModelValues($page);

        //we need to get elements for each element in the tree.
        //TODO: see https://github.com/franzose/ClosureTable/issues/164
        $tree_items = $page->collectDescendants()->withoutGlobalScopes(['live'])->get()->toTree();
        if ($tree_items) {
            foreach ($tree_items as $tree_item) {
                $tree_obj[] = $this->renderTreeJson($tree_item);
            }
        } else {
            return response()->json();
        }

        return response()->json($tree_obj);
    }

    /**
     * pass in tree item and returns a jstree formatted object.
     * @param $tree_item
     * @param $object
     * @return \stdClass
     */
    public function assignModelValues($tree_item, $object = null)
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
