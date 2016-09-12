<?php

namespace Soda\Cms\Components\Menu;

use Auth;
use Knp\Menu\MenuItem as BaseMenuItem;

class MenuItem extends BaseMenuItem {
    protected $renderer;

    public function isDisplayed()
    {
        return $this->display && $this->isPermitted();
    }

    public function isPermitted() {
        $permissions = $this->getPermissions();

        if(!$permissions) {
            return true;
        }

        if(!Auth::user()) {
            return false;
        }

        $permitted = true;

        foreach($permissions as $permission) {
            if(!Auth::user()->can($permission)) {
                $permitted = false;
            }
        }

        return $permitted;
    }

    public function getPermissions() {
        if(!isset($this->extras['permissions']) || !$this->extras['permissions']) {
            return [];
        }

        return is_array($this->extras['permissions']) ? $this->extras['permissions'] : [$this->extras['permissions']];
    }

    public function setRenderer($renderer) {
        $this->renderer = $renderer;

        return $this;
    }

    public function getRenderer() {
        return $this->renderer;
    }

    public function hasIcon() {
        return isset($this->extras['icon']) && $this->extras['icon'];
    }

    public function getIcon() {
        return $this->hasIcon() ? $this->extras['icon'] : '';
    }

    public function renderCollapseAttributes() {
        if($this->hasChildren() && !$this->isRoot()){
            return [
                'data-toggle' => 'collapse',
                'data-parent' => '.sidebar',
                'data-target' => $this->getCollapsibleId(),
            ];
        }

        return [];
    }

    public function getCollapsibleId($hash = true) {
        return ($hash ? '#' : '') . str_slug($this->getName() . '-nav');
    }
}
