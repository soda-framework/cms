<?php

namespace Soda\Cms\Components\Menu;

use Soda\Cms\Models\NavigationItem;
use Soda;
use Route;

class MenuBuilder {
    /**
     * Renders a menu tree
     *
     * @param $name
     * @param string $view
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function menu($name, $view = 'soda::tree.menu') {
        $nav = NavigationItem::where('name', $name)->first();
        if ($nav) {
            $tree = $nav->grabTree($nav->id);

            return view($view, ['tree' => $tree, 'hint' => 'page']);
        }
    }

    /**
     * Returns active if given route matches current route.
     *
     * @param $route
     * @param string $output
     *
     * @return string
     */
    public function matchesRoute($route, $output = 'active') {
        if (Route::currentRouteName() == $route) {
            return $output;
        }
    }
}
