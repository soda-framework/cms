<?php

namespace Soda\Cms\Components\Menu;

use Auth;

class MenuItem implements \ArrayAccess, \Countable, \IteratorAggregate
{
    protected $name;
    protected $attributes;

    protected $label;
    protected $label_attributes = [];

    protected $url;
    protected $link_attributes = [];

    protected $icon;
    protected $icon_attributes = [];

    protected $children;
    protected $children_attributes = [];

    protected $isCurrent;
    protected $isHidden;
    protected $permissions;

    protected $menu;
    protected $parent;

    public function __construct($name, $attributes = [])
    {
        $this->name = $name;
        $this->apply($attributes);
    }

    public function apply($attributes = [])
    {
        foreach ($attributes as $attribute => $value) {
            $func = 'set'.ucfirst($attribute);
            if (method_exists($this, $func)) {
                $this->$func($value);
            }
        }
    }

    public function getParent()
    {
        return $this->parent;
    }

    public function setParent(MenuItem $item)
    {
        $this->parent = $item;

        return $this;
    }

    public function hasParent()
    {
        return $this->parent !== null;
    }

    /**
     * Implements Countable.
     */
    public function count()
    {
        return count($this->children);
    }

    /**
     * Implements IteratorAggregate.
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->children);
    }

    /**
     * Implements ArrayAccess.
     */
    public function offsetExists($name)
    {
        return isset($this->children[$name]);
    }

    /**
     * Implements ArrayAccess.
     */
    public function offsetGet($name)
    {
        return $this->getChild($name);
    }

    public function getChild($name)
    {
        return isset($this->children[$name]) ? $this->children[$name] : null;
    }

    /**
     * Implements ArrayAccess.
     */
    public function offsetSet($name, $value)
    {
        return $this->addChild($name)->setLabel($value);
    }

    public function addChild($item, array $options = [])
    {
        if (! $item instanceof self) {
            $item = new self($item, $options);
        } elseif (null !== $item->getMenu()) {
            throw new \InvalidArgumentException('Cannot add menu item as child, it already belongs to another menu (e.g. has a parent).');
        }

        $item->setMenu($this->getMenu());
        $item->setParent($this);
        $this->children[$item->getName()] = $item;

        return $item;
    }

    public function getMenu()
    {
        return $this->menu;
    }

    public function setMenu(Menu $menu)
    {
        $this->menu = $menu;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     *
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Implements ArrayAccess.
     */
    public function offsetUnset($name)
    {
        $this->removeChild($name);
    }

    public function removeChild($name)
    {
        $name = $name instanceof self ? $name->getName() : $name;
        if (isset($this->children[$name])) {
            // unset the child and reset it so it looks independent
            $this->children[$name]->setParent(null);
            unset($this->children[$name]);
        }

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
        if (! is_array($attributes)) {
            $attributes = [$attributes];
        }

        $this->attributes = $attributes;

        return $this;
    }

    /**
     * @param $attribute
     * @param $value
     *
     * @return $this
     */
    public function setAttribute($attribute, $value)
    {
        $this->attributes[$attribute] = $value;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getLabel()
    {
        return $this->label !== null ? $this->label : $this->name;
    }

    /**
     * @param mixed $label
     *
     * @return $this
     */
    public function setLabel($label)
    {
        $this->label = $label;

        return $this;
    }

    /**
     * @return array
     */
    public function getLabelAttributes()
    {
        return $this->label_attributes;
    }

    /**
     * @param array $attributes
     *
     * @return $this
     */
    public function setLabelAttributes($attributes)
    {
        if (! is_array($attributes)) {
            $attributes = [$attributes];
        }

        $this->label_attributes = $attributes;

        return $this;
    }

    /**
     * @param string $attribute
     * @param string $value
     *
     * @return $this
     */
    public function setLabeAttribute($attribute, $value)
    {
        $this->label_attribute[$attribute] = $value;

        return $this;
    }

    /**
     * @return array
     */
    public function getLinkAttributes()
    {
        return $this->link_attributes;
    }

    /**
     * @param array $attributes
     *
     * @return $this
     */
    public function setLinkAttributes($attributes)
    {
        if (! is_array($attributes)) {
            $attributes = [$attributes];
        }

        $this->link_attributes = $attributes;

        return $this;
    }

    /**
     * @param string $attribute
     * @param string $value
     *
     * @return $this
     */
    public function setLinkAttribute($attribute, $value)
    {
        $this->link_attributes[$attribute] = $value;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getIcon()
    {
        return $this->icon;
    }

    /**
     * @param mixed $icon
     *
     * @return $this
     */
    public function setIcon($icon)
    {
        $this->icon = $icon;

        return $this;
    }

    /**
     * @return array
     */
    public function getIconAttributes()
    {
        return $this->icon_attributes;
    }

    /**
     * @param array $attributes
     *
     * @return $this
     */
    public function setIconAttributes($attributes)
    {
        if (! is_array($attributes)) {
            $attributes = [$attributes];
        }

        $this->icon_attributes = $attributes;

        return $this;
    }

    /**
     * @param string $attribute
     * @param string $value
     *
     * @return $this
     */
    public function setIconAttribute($attribute, $value)
    {
        $this->icon_attributes[$attribute] = $value;

        return $this;
    }

    /**
     * @return array
     */
    public function getChildrenAttributes()
    {
        return $this->children_attributes;
    }

    /**
     * @param array $attributes
     *
     * @return $this
     */
    public function setChildrenAttributes($attributes)
    {
        if (! is_array($attributes)) {
            $attributes = [$attributes];
        }

        $this->children_attributes = $attributes;

        return $this;
    }

    /**
     * @param string $attribute
     * @param string $value
     *
     * @return $this
     */
    public function setChildrenAttribute($attribute, $value)
    {
        $this->children_attributes[$attribute] = $value;

        return $this;
    }

    /**
     * @return mixed
     */
    public function isCurrent()
    {
        return $this->isCurrent;
    }

    /**
     * @param bool $current
     *
     * @return $this
     */
    public function setIsCurrent($current = true)
    {
        $this->isCurrent = $current;

        return $this;
    }

    /**
     * @return mixed
     */
    public function isVisible()
    {
        return ! $this->isHidden;
    }

    /**
     * @param bool $hidden
     *
     * @return $this
     */
    public function setIsHidden($hidden = true)
    {
        $this->isHidden = $hidden;

        return $this;
    }

    public function render()
    {
        if (! $this->hasPermission() || $this->isHidden() || (! $this->getUrl() && ! $this->hasVisibleChildren())) {
            return '';
        }

        return $this->hasChildren() ? $this->getMenu()->renderMenu($this) : $this->getMenu()->renderItem($this);
    }

    public function hasPermission()
    {
        if (! count($permissions = $this->getPermissions())) {
            return true;
        }

        if (! $user = Auth::user()) {
            return false;
        }

        foreach ($permissions as $permission) {
            if (! $user->can($permission)) {
                return false;
            }
        }

        return true;
    }

    /**
     * @return mixed
     */
    public function getPermissions()
    {
        return $this->permissions;
    }

    /**
     * @param mixed $permissions
     *
     * @return $this
     */
    public function setPermissions($permissions)
    {
        if (! is_array($permissions)) {
            $permissions = [$permissions];
        }

        $this->permissions = $permissions;

        return $this;
    }

    /**
     * @return mixed
     */
    public function isHidden()
    {
        return $this->isHidden;
    }

    /**
     * @return mixed
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param mixed $url
     *
     * @return $this
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    public function hasVisibleChildren()
    {
        return count($this->getVisibleChildren());
    }

    /**
     * @return mixed
     */
    public function getVisibleChildren()
    {
        $visible = [];

        foreach ($this->children as $child) {
            if ($child->isVisible() && $child->hasPermission()) {
                $visible[] = $child;
            }
        }

        return $visible;
    }

    /**
     * @return bool
     */
    public function hasChildren()
    {
        return count($this->getChildren());
    }

    /**
     * @return mixed
     */
    public function getChildren()
    {
        return $this->children;
    }
}
