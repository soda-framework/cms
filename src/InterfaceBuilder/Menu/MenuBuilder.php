<?php

namespace Soda\Cms\InterfaceBuilder\Menu;

class MenuBuilder
{
    protected $registrar;

    public function __construct(MenuRegistrar $registrar)
    {
        $this->registrar = $registrar;
    }

    /**
     * Gets a menu object by its name.
     *
     * @param $name
     *
     * @return array|\Soda\Cms\InterfaceBuilder\Menu\Menu
     */
    public function menu($name, $callback)
    {
        $this->registrar->register($name, $callback);
    }

    public function render($name)
    {
        $menu = $this->registrar->resolve($name);

        return $menu->render();
    }
}
