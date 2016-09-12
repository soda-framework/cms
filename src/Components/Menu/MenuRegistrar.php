<?php
namespace Soda\Cms\Components\Menu;

class MenuRegistrar {
    protected $registered_menus = [];

    /**
     * Registers a new CMS form field
     *
     * @param $name
     * @param \Soda\Cms\Components\Menu\MenuItem $menu
     *
     */
    public function register($name, MenuItem $menu){
        $this->registered_menus[$name] = $menu;
    }

    /**
     * Returns a menu by its name
     *
     * @return array
     */
    public function resolve($name) {
        return $this->isRegistered($name) ? $this->registered_menus[$name] : null;
    }

    /**
     * Checks if a menu is registered
     *
     * @param $name
     *
     * @return bool
     */
    public function isRegistered($name) {
        return isset($this->registered_menus[$name]);
    }
}
