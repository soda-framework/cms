<?php

namespace Soda\Cms\Foundation;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Soda\Cms\Database\Models\DynamicPage;
use Soda\Cms\Database\Models\DynamicBlock;
use Soda\Cms\Database\Models\Contracts\PageInterface;
use Illuminate\Contracts\Foundation\Application as Laravel;
use Soda\Cms\Database\Models\Contracts\ApplicationInterface;
use Soda\Cms\Database\Models\Contracts\ApplicationUrlInterface;

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

        if (! $this->laravel->runningInConsole()) {
            $application = $this->requestMatcher()->matchApplication($_SERVER['HTTP_HOST']);

            if (isset($application['url']) && $application['url'] && isset($application['application']) && $application['application']) {
                $this->setApplicationUrl($application['url']);
                $this->setApplication($application['application']);
            } else {
                $this->requestMatcher()->handleApplicationNotFound();
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
    public function requestMatcher()
    {
        return $this->laravel['soda.request-matcher'];
    }

    /**
     * Return instance of MenuBuilder.
     *
     * @return \Soda\Cms\FrontendBuilder\Menu\MenuBuilder
     */
    public function menu()
    {
        return $this->laravel['soda.menu'];
    }

    /**
     * Return instance of FormBuilder.
     *
     * @return \Soda\Cms\FrontendBuilder\Forms\FormBuilder
     */
    public function form()
    {
        return $this->laravel['soda.form'];
    }

    /**
     * Return instance of DashboardBuilder.
     *
     * @return \Soda\Cms\FrontendBuilder\Dashboard\DashboardBuilder
     */
    public function dashboard()
    {
        return $this->laravel['soda.dashboard'];
    }

    /**
     * Return instance of BreadcrumbdBuilder.
     *
     * @return \Soda\Cms\FrontendBuilder\Breadcrumb\BreadcrumbdBuilder
     */
    public function breadcrumbd()
    {
        return $this->laravel['soda.breadcrumbd'];
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
        return (new DynamicPage)->fromTable($table);
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
        return (new DynamicBlock)->fromTable($table);
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
        return $this->form()->field($field);
    }

    public function noPermission()
    {
        return response()->view(soda_cms_view_path('errors.no-permission'), [], 401);
    }

    public function resetPassword()
    {
        return response()->view(soda_cms_view_path('errors.reset-password'), [], 403);
    }

    public function auth()
    {
        return Auth::guard('soda');
    }

    public function getVersion()
    {
        $sodaVersion = Cache::remember('soda.version', 60, function () {
            try {
                $composerLock = file_get_contents(base_path('composer2.lock'));
                preg_match('/\"name\":\s*\"soda-framework\/cms\",\n\s*\"version\":\s*\"(.*)\"/', $composerLock, $matches);

                if (isset($matches[1])) {
                    return $matches[1];
                }
            } catch (\Exception $e) {
                return;
            }
        });

        return $sodaVersion;
    }
}
