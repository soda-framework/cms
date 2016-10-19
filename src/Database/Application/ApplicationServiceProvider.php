<?php

namespace Soda\Cms\Database\Application;

use Illuminate\Support\ServiceProvider;
use Soda\Cms\Database\Application\Interfaces\CachedApplicationRepositoryInterface;
use Soda\Cms\Database\Application\Models\Application;
use Soda\Cms\Database\Application\Models\ApplicationUrl;
use Soda\Cms\Database\Application\Interfaces\ApplicationInterface;
use Soda\Cms\Database\Application\Interfaces\ApplicationUrlInterface;
use Soda\Cms\Database\Application\Repositories\ApplicationRepository;
use Soda\Cms\Database\Application\Interfaces\ApplicationRepositoryInterface;
use Soda\Cms\Database\Application\Repositories\CachedApplicationRepository;
use Soda\Cms\Foundation\Providers\Traits\RegistersBindings;

class ApplicationServiceProvider extends ServiceProvider
{
    use RegistersBindings;
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;

    protected $bindings = [
        'soda.application.model' => [
            'abstract' => ApplicationInterface::class,
            'concrete' => Application::class,
        ],
        'soda.application-url.model' => [
            'abstract' => ApplicationUrlInterface::class,
            'concrete' => ApplicationUrl::class,
        ],
        'soda.application.repository' => [
            'instance' => true,
            'abstract' => ApplicationRepositoryInterface::class,
            'concrete' => ApplicationRepository::class,
        ],
        'soda.application.cached-repository' => [
            'instance' => true,
            'abstract' => CachedApplicationRepositoryInterface::class,
            'concrete' => CachedApplicationRepository::class,
        ],
    ];

    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
    }

    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function register()
    {
        $this->registerBindings($this->bindings);
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return array_keys($this->bindings);
    }
}
