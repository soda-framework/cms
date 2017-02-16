<?php

namespace Soda\Components;

use Route;
use Exception;
use Soda\Models\Page;
use Soda\Models\Block;
use Soda\Models\Application;
use Soda\Models\ModelBuilder;
use Soda\Models\ApplicationUrl;
use Soda\Models\NavigationItem;
use Soda\Components\Forms\FormBuilder;
use Soda\Components\Pages\PageBuilder;

class Soda
{
    protected $application = null;
    protected $blocks = [];
    protected $formBuilder;
    protected $pageBuilder;
    protected $currentPage;

    public function __construct(FormBuilder $formBuilder, PageBuilder $pageBuilder)
    {
        $this->formBuilder = $formBuilder;
        $this->pageBuilder = $pageBuilder;
    }

    public function getApplication()
    {
        if (! $this->application) {
            $this->loadApplication();
        }

        return $this->application;
    }

    public function setApplication(Application $application)
    {
        $this->application = $application;

        return $this;
    }

    protected function loadApplication()
    {
        $domain = $_SERVER['HTTP_HOST'];
        $applicationUrl = ApplicationUrl::whereDomain($domain)->first();

        if ($applicationUrl) {
            $application = $applicationUrl->application()->first();

            if ($application) {
                return $this->setApplication($application);
            }

            throw new Exception('Application URL is not associated with an application');
        }

        throw new Exception('No application found at URL');
    }

    public function getBlock($identifier)
    {
        if (! isset($this->blocks[$identifier])) {
            $this->blocks[$identifier] = Block::with('type')->where('identifier', $identifier)->first();
        }

        return $this->blocks[$identifier];
    }

    public function dynamicModel($table)
    {
        return ModelBuilder::fromTable($table, []);
    }

    public function setCurrentPage(Page $page)
    {
        $this->currentPage = $page;
    }

    public function getCurrentPage()
    {
        return $this->currentPage;
    }

    public function getPageBuilder()
    {
        return $this->pageBuilder;
    }

    /**
     * renders a menu tree.
     *
     * @param $name
     * @param string $view
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function menu($name, $view = 'soda::tree.menu')
    {
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
    public function menuActive($route, $output = 'active')
    {
        if (Route::currentRouteName() == $route) {
            return $output;
        }
    }

    public function getFormBuilder()
    {
        return $this->formBuilder;
    }

    public function getFieldTypes()
    {
        return $this->field_types;
    }

    public function field($field)
    {
        return $this->formBuilder->newField($field);
    }

    /**
     * EXPERAMENTAL renders an editable field.
     *
     * @param $model
     * @param $element
     * @param $type
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function editable($model, $element, $type)
    {
        return $this->formBuilder->editable($model, $element, $type);
    }
}
