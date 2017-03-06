<?php

namespace Soda\Cms\Database\Blocks;

use Illuminate\Support\ServiceProvider;
use Soda\Cms\Database\Blocks\Models\BlockType;
use Soda\Cms\Database\Blocks\Models\DynamicBlock;
use Illuminate\Database\Eloquent\Relations\Relation;
use Soda\Cms\Database\Blocks\Interfaces\BlockTypeInterface;
use Soda\Cms\Database\Blocks\Interfaces\DynamicBlockInterface;
use Soda\Cms\Database\Blocks\Repositories\BlockTypeRepository;
use Soda\Cms\Database\Blocks\Repositories\BlockTypeFieldRepository;
use Soda\Cms\Database\Blocks\Interfaces\BlockTypeRepositoryInterface;
use Soda\Cms\Database\Blocks\Interfaces\BlockTypeFieldRepositoryInterface;
use Soda\Cms\Foundation\Providers\Traits\RegistersBindingsAndDependencies;

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
        'soda.dynamic-block.model'         => [DynamicBlockInterface::class, DynamicBlock::class],
        'soda.block-type.model'            => [BlockTypeInterface::class, BlockType::class],
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
            'BlockType' => resolve_class('soda.block-type.model'),
        ]);
    }

    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('soda.dynamic-block.model', function ($app) {
            return new DynamicBlock;
        });

        $this->app->bind('soda.block-type.model', function ($app) {
            return new BlockType;
        });

        $this->app->singleton('soda.block-type.repository', function ($app) {
            return new BlockTypeRepository($app['soda.block-type.model']);
        });

        $this->app->singleton('soda.block-type-field.repository', function ($app) {
            return new BlockTypeFieldRepository($app['soda.block-type.model'], $app['soda.field.model']);
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
