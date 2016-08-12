<?php
namespace Soda\Components;

use Route;
use Soda\Components\Forms\FormBuilder;
use Soda\Components\Forms\FormField;
use Soda\Models\ApplicationUrl;
use Soda\Models\Block;
use Soda\Models\BlockType;
use Soda\Models\ModelBuilder;
use Soda\Models\NavigationItem;

//TODO: MOVE ME SOMEWHERE SENSIBLE

class Soda {
    protected $application = null;
    protected $blocks = [];
    protected $formBuilder;

    public function __construct(FormBuilder $formBuilder) {
        $this->formBuilder = $formBuilder;
    }

    public function getApplication() {
        if (!$this->application) {
            $this->application = ApplicationUrl::where('domain', $_SERVER['HTTP_HOST'])->first()->application()->first();
        }

        return $this->application;
    }

    public function getBlock($identifier) {
        if (!isset($this->blocks[$identifier])) {
            $this->blocks[$identifier] = Block::with('type')->where('identifier', $identifier)->first();
        }

        return $this->blocks[$identifier];
    }

    public function dynamicModel($table) {
        return ModelBuilder::fromTable($table, []);
    }

    /**
     * renders a menu tree
     *
     * @param $name
     * @param string $view
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function menu($name, $view = 'soda::tree.menu') {
        $nav = NavigationItem::where('name', $name)->first();
        if ($nav) {
            $tree = $nav->grabTree($nav->id);

            return view($view, ['tree' => $tree, 'hint' => 'page']);
        }
    }

    /**
     * returns active if given route matches current route.
     * TODO: move to menu class somewhere?
     *
     * @param $route
     * @param string $output
     *
     * @return string
     */
    public function menuActive($route, $output = 'active') {
        if (Route::currentRouteName() == $route) {
            return $output;
        }
    }

    public function getFormBuilder() {
        return $this->formBuilder;
    }

    public function getFieldTypes() {
        return $this->field_types;
    }

    public function field($field) {
        return $this->formBuilder->newField($field);
    }

    /**
     * EXPERAMENTAL renders an editable field
     *
     * @param $model
     * @param $element
     * @param $type
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function editable($model, $element, $type) {
        return $this->formBuilder->editable($model, $element, $type);
    }
}
