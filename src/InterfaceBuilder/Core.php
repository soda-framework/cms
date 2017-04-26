<?php

namespace Soda\Cms\InterfaceBuilder;

use Soda\Cms\InterfaceBuilder\Menu\Menu;
use Soda\Cms\InterfaceBuilder\Menu\MenuBuilder;
use Soda\Cms\InterfaceBuilder\Breadcrumb\BreadcrumbBuilder;

class Core
{
    protected $title;
    protected $heading;
    protected $headingIcon;
    protected $description;

    protected $menuBuilder;
    protected $breadcrumbs;
    protected $dashboard;

    public function __construct(MenuBuilder $menuBuilder, BreadcrumbBuilder $breadcrumbs)
    {
        $this->menuBuilder = $menuBuilder;
        $this->breadcrumbs = $breadcrumbs;
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title ?: $this->heading;
    }

    /**
     * @param mixed $title
     *
     * @return Core
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getHeading()
    {
        return $this->heading;
    }

    /**
     * @param mixed $heading
     *
     * @return Core
     */
    public function setHeading($heading)
    {
        $this->heading = $heading;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getHeadingIcon()
    {
        return $this->headingIcon;
    }

    /**
     * @param mixed $headingIcon
     *
     * @return Core
     */
    public function setHeadingIcon($headingIcon)
    {
        $this->headingIcon = $headingIcon;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param mixed $description
     *
     * @return Core
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Menu
     */
    public function menuBuilder()
    {
        return $this->menuBuilder;
    }

    /**
     * @return BreadcrumbBuilder
     */
    public function breadcrumbs()
    {
        return $this->breadcrumbs;
    }

    /**
     * @return FormBuilder
     */
    public function form()
    {
        return $this->form;
    }
}
