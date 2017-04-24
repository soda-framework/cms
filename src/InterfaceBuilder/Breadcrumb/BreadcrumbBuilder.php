<?php

namespace Soda\Cms\InterfaceBuilder\Breadcrumb;

class BreadcrumbBuilder
{
    protected $links = [];

    public function getLinks()
    {
        return $this->links;
    }

    public function addLink($url, $title)
    {
        $this->links[] = compact('url', 'title');
    }
}
