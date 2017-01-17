<?php
/**
 * Functions specific to pages and paging.
 * User: sidavies
 * Date: 23/02/2016
 * Time: 4:36 PM.
 */

namespace Soda\Components;

class Page
{
    //probably slug generation in here
    //probably handle rendering
    //TODO: OR SHOULD I MOVE ALL THIS INTO THE MODEL??

    public $page;

    public function handleEditAction($page)
    {
        if ($page->edit_action_type == 'view') {
            if (! $page->edit_action) {
                return view('soda::page.view', ['page' => $page]);
            } else {
            }
        }
    }

    /**
     * renders the hint path and view of given page (or pageable item).
     * @return string
     */
    public static function constructView($page, $params = [])
    {
        if ($page->action_type == 'controller') {
            //TODO.
            return false;
        } else {
            //$page->action
            //just assume its a view.
            $view = $page->package.'::'.$page->action;

            return view($view, $params);
        }
    }
}
