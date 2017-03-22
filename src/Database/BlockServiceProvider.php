<?php

namespace Soda\Cms\Database;

use Soda\Cms\Database\Models\Field;
use Illuminate\Support\ServiceProvider;
use Soda\Cms\Database\Models\BlockType;
use Illuminate\Database\Eloquent\Relations\Relation;
use Soda\Cms\Database\Repositories\BlockTypeRepository;
use Soda\Cms\Database\Repositories\BlockTypeFieldRepository;
use Soda\Cms\Database\Repositories\Contracts\BlockTypeRepositoryInterface;
use Soda\Cms\Foundation\Providers\Traits\RegistersBindingsAndDependencies;
use Soda\Cms\Database\Repositories\Contracts\BlockTypeFieldRepositoryInterface;

class BlockServiceProvider extends ServiceProvider
{
    use RegistersBindingsAndDependencies;
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;

    protected $aliases = [
        'soda.block-type.repository'       => [BlockTypeRepositoryInterface::class, BlockTypeRepository::class],
        'soda.block-type-field.repository' => [BlockTypeFieldRepositoryInterface::class, BlockTypeFieldRepository::class],
    ];

    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        Relation::morphMap([
            'BlockType' => BlockType::class,
        ]);
    }

    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('soda.block-type.repository', function ($app) {
            return new BlockTypeRepository(new BlockType);
        });

        $this->app->singleton('soda.block-type-field.repository', function ($app) {
            return new BlockTypeFieldRepository(new BlockType, new Field);
        });

        $this->registerAliases($this->aliases);
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return array_keys($this->aliases);
    }
}
