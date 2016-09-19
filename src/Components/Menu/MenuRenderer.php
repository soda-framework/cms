<?php

namespace Soda\Cms\Components\Menu;

class MenuRenderer
{
    public function renderRoot(Menu $menu)
    {
        return soda_cms_view('partials.menu.root', compact('menu'));
    }

    public function renderMenu(MenuItem $item)
    {
        return soda_cms_view('partials.menu.group', compact('item'));
    }

    public function renderItem(MenuItem $item)
    {
        return soda_cms_view('partials.menu.item', compact('item'));
    }
}
