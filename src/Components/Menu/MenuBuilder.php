<?php

namespace Soda\Cms\Components\Menu;

use Knp\Menu\FactoryInterface;
use Knp\Menu\Matcher\Matcher;
use Knp\Menu\Renderer\ListRenderer;
use Knp\Menu\Renderer\RendererInterface;
use Soda;
use Route;

class MenuBuilder {
    protected $registrar;
    protected $factory;

    public function __construct(MenuRegistrar $registrar, FactoryInterface $factory) {
        $this->registrar = $registrar;
        $this->factory = $factory;
    }

    /**
     * Gets a menu object by its name
     *
     * @param $name
     *
     * @return array|\Soda\Cms\Components\Menu\Menu
     */
    public function menu($name, $attributes = []) {
        $menu = $this->registrar->resolve($name);

        if(!$menu) {
            $menu = $this->factory->createItem($name, $attributes);
            $this->registrar->register($name, $menu);
        }

        return $menu;
    }

    public function render($name, $attributes = []) {
        $menu = $this->registrar->resolve($name);

        $renderer = $menu->getRenderer();

        if(!$renderer) {
            $renderer = new ListRenderer(new Matcher);
        }

        return $renderer->render($menu, $attributes);
    }
}
