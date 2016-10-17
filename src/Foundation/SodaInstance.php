<?php
namespace Soda\Cms\Foundation;

use Cache;
use Exception;
use Illuminate\Contracts\Foundation\Application as IlluminateApplication;
use Route;
use Soda\Cms\Models\Application;
use Soda\Cms\Models\ApplicationUrl;
use Soda\Cms\Models\Block;
use Soda\Cms\Models\ModelBuilder;
use Soda\Cms\Models\Page;

class SodaInstance
{
    protected $app;
    protected $application = null;
    protected $blocks = [];
    protected $currentPage;

    public function __construct(IlluminateApplication $app)
    {
        $this->app = $app;

        if (!app()->runningInConsole()) {
            $domain = str_replace('www.', '', $_SERVER['HTTP_HOST']);

            if(config('soda.cache.application') === true) {
                $application = $this->app['cache']->rememberForever('soda.application-'.$domain, function() use ($domain) {
                    return $this->findApplicationByDomain($domain);
                });
            } else {
                $application = $this->findApplicationByDomain($domain);
            }

            $this->setApplication($application);
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

        return ModelBuilder::fromTable($table);
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
     * @return \Soda\Cms\Foundation\Pages\PageBuilder
     */
    public function getPageBuilder()
    {
        return $this->app['soda.page'];
    }

    /**
     * Return instance of MenuBuilder
     *
     * @return \Soda\Cms\Foundation\Pages\MenuBuilder
     */
    public function getMenuBuilder()
    {
        return $this->app['soda.menu'];
    }

    /**
     * Return instance of FormBuilder
     *
     * @return \Soda\Cms\Foundation\Pages\FormBuilder
     */
    public function getFormBuilder()
    {
        return $this->app['soda.form'];
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
     * @return \Soda\Cms\Foundation\Soda
     * @throws \Exception
     */
    protected function findApplicationByDomain($domain)
    {
        if ($applicationUrl = ApplicationUrl::whereDomain($domain)->first()) {
            if ($application = $applicationUrl->application()->first()) {
                return $application;
            }

            throw new Exception('Application URL is not associated with an application');
        }

        throw new Exception('No application found at URL');
    }
}
