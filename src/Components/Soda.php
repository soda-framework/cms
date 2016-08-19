<?php
namespace Soda\Cms\Components;

use Exception;
use Route;
use Soda\Cms\Components\Forms\FormBuilder;
use Soda\Cms\Components\Pages\PageBuilder;
use Soda\Cms\Models\Application;
use Soda\Cms\Models\ApplicationUrl;
use Soda\Cms\Models\Block;
use Soda\Cms\Models\ModelBuilder;
use Soda\Cms\Models\NavigationItem;
use Soda\Cms\Models\Page;

class Soda {
    protected $application = null;
    protected $blocks = [];
    protected $formBuilder;
    protected $pageBuilder;
    protected $currentPage;

    public function __construct(FormBuilder $formBuilder, PageBuilder $pageBuilder) {
        $this->formBuilder = $formBuilder;
        $this->pageBuilder = $pageBuilder;
    }

    public function getApplication() {
        if (!$this->application) {
            $this->loadApplication();
        }

        return $this->application;
    }

    public function setApplication(Application $application) {
        $this->application = $application;

        return $this;
    }

    protected function loadApplication() {
        $domain = $_SERVER['HTTP_HOST'];
        $applicationUrl = ApplicationUrl::whereDomain($domain)->first();

        if ($applicationUrl) {
            $application = $applicationUrl->application()->first();

            if ($application) {
                return $this->setApplication($application);
            }

            Throw new Exception('Application URL is not associated with an application');
        }

        Throw new Exception('No application found at URL');
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

    public function setCurrentPage(Page $page) {
        $this->currentPage = $page;
    }

    public function getCurrentPage() {
        return $this->currentPage;
    }

    public function getPageBuilder() {
        return $this->pageBuilder;
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
