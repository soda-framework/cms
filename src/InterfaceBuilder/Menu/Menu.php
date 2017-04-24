<?php

namespace Soda\Cms\InterfaceBuilder\Menu;

use InvalidArgumentException;

class Menu implements \ArrayAccess, \Countable, \IteratorAggregate
{
    protected $renderer;
    protected $active_class = 'active';
    protected $items = [];
    protected $attributes = [];

    public function getItems()
    {
        return $this->items;
    }

    public function render()
    {
        return $this->renderer->renderRoot($this);
    }

    /**
     * @param MenuItem $menu
     */
    public function renderMenu($menu)
    {
        return $this->renderer->renderMenu($menu);
    }

    public function renderItem(MenuItem $item)
    {
        return $this->renderer->renderItem($item);
    }

    /**
     * @return mixed
     */
    public function getRenderer()
    {
        return $this->renderer;
    }

    /**
     * @param mixed $renderer
     *
     * @return $this
     */
    public function setRenderer($renderer)
    {
        $this->renderer = $renderer;

        return $this;
    }

    /**
     * @return string
     */
    public function getActiveClass()
    {
        return $this->active_class;
    }

    /**
     * @param mixed $active_class
     *
     * @return $this
     */
    public function setActiveClass($active_class)
    {
        $this->active_class = $active_class;

        return $this;
    }

    /**
     * @return array
     */
    public function getAttributes()
    {
        return $this->attributes;
    }

    /**
     * @param array $attributes
     *
     * @return $this
     */
    public function setAttributes($attributes)
    {
        $this->attributes = $attributes;

        return $this;
    }

    /**
     * Implements Countable.
     */
    public function count()
    {
        return count($this->items);
    }

    /**
     * Implements IteratorAggregate.
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->items);
    }

    /**
     * Implements ArrayAccess.
     */
    public function offsetExists($name)
    {
        return isset($this->items[$name]);
    }

    /**
     * Implements ArrayAccess.
     */
    public function offsetGet($name)
    {
        return $this->getItem($name);
    }

    public function getItem($name)
    {
        return isset($this->items[$name]) ? $this->items[$name] : null;
    }

    /**
     * Implements ArrayAccess.
     */
    public function offsetSet($name, $value)
    {
        return $this->addItem($name)->setLabel($value);
    }

    public function addItem($item, array $options = [])
    {
        if (! $item instanceof MenuItem) {
            $item = new MenuItem($item, $options);
        } elseif (null !== $item->getMenu()) {
            throw new InvalidArgumentException('Cannot add menu item as child, it already belongs to another menu (e.g. has a parent).');
        }

        $item->setMenu($this);
        $this->items[$item->getName()] = $item;

        return $item;
    }

    /**
     * Implements ArrayAccess.
     */
    public function offsetUnset($name)
    {
        $this->removeItem($name);
    }

    public function removeItem($name)
    {
        $name = $name instanceof MenuItem ? $name->getName() : $name;
        if (isset($this->items[$name])) {
            // unset the child and reset it so it looks independent
            $this->items[$name]->setParent(null);
            unset($this->items[$name]);
        }

        return $this;
    }
}
