<?php
namespace Soda\Cms\Support;

use Exception;
use Route;
use Soda\Cms\Components\Forms\FormBuilder;
use Soda\Cms\Components\Menu\MenuBuilder;
use Soda\Cms\Components\Pages\PageBuilder;
use Soda\Cms\Models\Application;
use Soda\Cms\Models\ApplicationUrl;
use Soda\Cms\Models\Block;
use Soda\Cms\Models\ModelBuilder;
use Soda\Cms\Models\Page;

class SodaInstance
{
    protected $application = null;
    protected $blocks = [];
    protected $formBuilder;
    protected $pageBuilder;
    protected $menuBuilder;
    protected $currentPage;

    public function __construct(FormBuilder $formBuilder, PageBuilder $pageBuilder, MenuBuilder $menuBuilder)
    {
        $this->formBuilder = $formBuilder;
        $this->pageBuilder = $pageBuilder;
        $this->menuBuilder = $menuBuilder;

        if (!app()->runningInConsole()) {
            $this->loadApplication();
        }
    }

    /**
     * Returns the current application loaded at the current URL
     *
     * @return null
     */
    public function getApplication()
    {
        return $this->application;
    }

    /**
     * Sets the application
     *
     * @param \Soda\Cms\Models\Application $application
     *
     * @return $this
     */
    public function setApplication(Application $application)
    {
        $this->application = $application;

        return $this;
    }

    /**
     * Get a block by its identifier
     *
     * @param $identifier
     *
     * @return mixed
     */
    public function getBlock($identifier)
    {
        if (!isset($this->blocks[$identifier])) {
            $this->blocks[$identifier] = Block::with('type')->where('identifier', $identifier)->first();
        }

        return $this->blocks[$identifier];
    }

    /**
     * @deprecated Load a dynamic model
     *
     * @param $table
     *
     * @return mixed
     */
    public function dynamicModel($table)
    {
        return ModelBuilder::fromTable($table, []);
    }

    /**
     * Load a dynamic model
     *
     * @param      $table
     * @param bool $autoprefix
     *
     * @return mixed
     */
    public function model($table, $autoprefix = true)
    {
        if ($autoprefix) $table = 'soda_'.$table;

        return ModelBuilder::fromTable($table, []);
    }

    /**
     * Get the current page that we're visiting
     *
     * @return mixed
     */
    public function getCurrentPage()
    {
        return $this->currentPage;
    }

    /**
     * Set the current page that we're visiting
     *
     * @param \Soda\Cms\Models\Page $page
     */
    public function setCurrentPage(Page $page)
    {
        $this->currentPage = $page;
    }

    /**
     * Return instance of PageBuilder
     *
     * @return \Soda\Cms\Components\Pages\PageBuilder
     */
    public function getPageBuilder()
    {
        return $this->pageBuilder;
    }

    /**
     * Return instance of MenuBuilder
     *
     * @return \Soda\Cms\Components\Pages\MenuBuilder
     */
    public function getMenuBuilder()
    {
        return $this->menuBuilder;
    }

    /**
     * Return instance of FormBuilder
     *
     * @return \Soda\Cms\Components\Pages\FormBuilder
     */
    public function getFormBuilder()
    {
        return $this->formBuilder;
    }

    /**
     * Build a FormField from array or Field model
     *
     * @param $field
     *
     * @return mixed
     */
    public function field($field)
    {
        return $this->getFormBuilder()->field($field);
    }

    /**
     * Determines the application by our current URL and sets it
     *
     * @return \Soda\Cms\Components\Soda
     * @throws \Exception
     */
    protected function loadApplication()
    {
        $domain = str_replace('www.', '', $_SERVER['HTTP_HOST']);
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
}
