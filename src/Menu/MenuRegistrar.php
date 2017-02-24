<?php

namespace Soda\Cms\Menu;

class MenuRegistrar
{
    protected $registered_menus = [];

    /**
     * Registers a new CMS form field.
     *
     * @param $name
     * @param $callback
     *
     * @return $this
     */
    public function register($name, $callback)
    {
        if (! isset($this->registered_menus[$name])) {
            $this->registered_menus[$name] = [];
        }

        $this->registered_menus[$name][] = $callback;

        return $this;
    }

    /**
     * Returns a menu by its name.
     *
     * @return Menu|null
     */
    public function resolve($name)
    {
        return $this->isRegistered($name) ? $this->boot($name) : null;
    }

    /**
     * Checks if a menu is registered.
     *
     * @param $name
     *
     * @return bool
     */
    public function isRegistered($name)
    {
        return isset($this->registered_menus[$name]);
    }

    protected function boot($name)
    {
        $callables = $this->registered_menus[$name];

        if ($callables instanceof Menu) {
            return $callables;
        }

        $menu = new Menu($name);

        foreach ($callables as $callable) {
            $callable($menu);
        }

        $this->registered_menus[$name] = $menu;

        return $menu;
    }
}
