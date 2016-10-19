<?php
namespace Soda\Cms\Foundation;

use Cache;
use Illuminate\Contracts\Foundation\Application as IlluminateApplication;
use Route;
use Soda\Cms\Foundation\Application\Interfaces\ApplicationInterface;
use Soda\Cms\Foundation\Blocks\Interfaces\DynamicBlockInterface;
use Soda\Cms\Foundation\Pages\Interfaces\DynamicPageInterface;
use Soda\Cms\Foundation\Pages\Interfaces\PageInterface;

class SodaInstance
{
    protected $app;
    protected $requestMatcher;
    protected $application = null;
    protected $blocks = [];
    protected $currentPage;

    public function __construct(IlluminateApplication $app)
    {
        $this->app = $app;

        if (!$this->app->runningInConsole()) {
            $application = $this->getRequestMatcher()->matchApplication($_SERVER['HTTP_HOST']);

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
     * @param ApplicationInterface $application
     *
     * @return $this
     */
    public function setApplication(ApplicationInterface $application)
    {
        $this->application = $application;

        return $this;
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
     * @param PageInterface $page
     */
    public function setCurrentPage(PageInterface $page)
    {
        $this->currentPage = $page;
    }

    /**
     * Return instance of MenuBuilder
     *
     * @return \Soda\Cms\Http\Matcher\RequestMatcher
     */
    public function getRequestMatcher()
    {
        return $this->app['soda.request-matcher'];
    }

    /**
     * Return instance of MenuBuilder
     *
     * @return \Soda\Cms\Menu\MenuBuilder
     */
    public function getMenuBuilder()
    {
        return $this->app['soda.menu'];
    }

    /**
     * Return instance of FormBuilder
     *
     * @return \Soda\Cms\Forms\FormBuilder
     */
    public function getFormBuilder()
    {
        return $this->app['soda.form'];
    }

    /**
     * Load a dynamic page
     *
     * @param      $table
     *
     * @return mixed
     */
    public function dynamicPage($table)
    {
        return app(DynamicPageInterface::class)->fromTable($table);
    }

    /**
     * Load a dynamic block
     *
     * @param      $table
     *
     * @return mixed
     */
    public function dynamicBlock($table)
    {
        return app(DynamicBlockInterface::class)->fromTable($table);
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
}
