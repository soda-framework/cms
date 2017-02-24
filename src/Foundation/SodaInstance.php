<?php

namespace Soda\Cms\Foundation;

use Illuminate\Support\Facades\Auth;
use Soda\Cms\Database\Pages\Interfaces\PageInterface;
use Illuminate\Contracts\Foundation\Application as Laravel;
use Soda\Cms\Database\Application\Interfaces\ApplicationInterface;
use Soda\Cms\Database\Application\Interfaces\ApplicationUrlInterface;

class SodaInstance
{
    protected $laravel;
    protected $requestMatcher;
    protected $application;
    protected $applicationUrl;
    protected $blocks = [];
    protected $currentPage;

    public function __construct(Laravel $laravel)
    {
        $this->laravel = $laravel;

        if (!$this->laravel->runningInConsole()) {
            $application = $this->getRequestMatcher()->matchApplication($_SERVER['HTTP_HOST']);

            if (isset($application['url']) && $application['url'] && isset($application['application']) && $application['application']) {
                $this->setApplicationUrl($application['url']);
                $this->setApplication($application['application']);
            } else {
                $this->getRequestMatcher()->handleApplicationNotFound();
            }
        }
    }

    /**
     * Returns the current application loaded at the current URL.
     *
     * @return ApplicationInterface
     */
    public function getApplication()
    {
        return $this->application;
    }

    /**
     * Sets the application.
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
     * Returns the application url model matched to the current application.
     *
     * @return ApplicationUrlInterface
     */
    public function getApplicationUrl()
    {
        return $this->applicationUrl;
    }

    /**
     * Sets the application.
     *
     * @param ApplicationUrlInterface $applicationUrl
     *
     * @return $this
     */
    public function setApplicationUrl(ApplicationUrlInterface $applicationUrl)
    {
        $this->applicationUrl = $applicationUrl;

        return $this;
    }

    /**
     * Get the current page that we're visiting.
     *
     * @return PageInterface
     */
    public function getCurrentPage()
    {
        return $this->currentPage;
    }

    /**
     * Set the current page that we're visiting.
     *
     * @param PageInterface $page
     */
    public function setCurrentPage(PageInterface $page)
    {
        $this->currentPage = $page;
    }

    /**
     * Return instance of MenuBuilder.
     *
     * @return \Soda\Cms\Http\RequestMatcher\RequestMatcher
     */
    public function getRequestMatcher()
    {
        return $this->laravel['soda.request-matcher'];
    }

    /**
     * Return instance of MenuBuilder.
     *
     * @return \Soda\Cms\Menu\MenuBuilder
     */
    public function getMenuBuilder()
    {
        return $this->laravel['soda.menu'];
    }

    /**
     * Return instance of FormBuilder.
     *
     * @return \Soda\Cms\Forms\FormBuilder
     */
    public function getFormBuilder()
    {
        return $this->laravel['soda.form'];
    }

    /**
     * Load a dynamic page.
     *
     * @param      $table
     *
     * @return mixed
     */
    public function dynamicPage($table)
    {
        return app('soda.dynamic-page.model')->fromTable($table);
    }

    /**
     * Load a dynamic block.
     *
     * @param      $table
     *
     * @return mixed
     */
    public function dynamicBlock($table)
    {
        return app('soda.dynamic-block.model')->fromTable($table);
    }

    /**
     * Build a FormField from array or Field model.
     *
     * @param $field
     *
     * @return mixed
     */
    public function field($field)
    {
        return $this->getFormBuilder()->field($field);
    }

    public function noPermission()
    {
        return response()->view(soda_cms_view_path('errors.no-permission'), [], 401);
    }

    public function auth()
    {
        return Auth::guard('soda');
    }
}
