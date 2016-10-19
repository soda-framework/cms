<?php

namespace Soda\Cms\Foundation\Blocks;

use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\ServiceProvider;
use Soda\Cms\Foundation\Blocks\Interfaces\BlockRepositoryInterface;
use Soda\Cms\Foundation\Blocks\Models\DynamicBlock;
use Soda\Cms\Foundation\Blocks\Models\Block;
use Soda\Cms\Foundation\Blocks\Models\BlockType;
use Soda\Cms\Foundation\Blocks\Interfaces\BlockInterface;
use Soda\Cms\Foundation\Blocks\Interfaces\BlockTypeInterface;
use Soda\Cms\Foundation\Blocks\Interfaces\DynamicBlockInterface;
use Soda\Cms\Foundation\Blocks\Repositories\BlockRepository;

class BlocksServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;

    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        Relation::morphMap([
            'BlockType' => resolve_class(BlockTypeInterface::class),
        ]);
    }

    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(BlockInterface::class, Block::class);
        $this->app->bind(DynamicBlockInterface::class, DynamicBlock::class);
        $this->app->bind(BlockTypeInterface::class, BlockType::class);
        $this->app->bind(BlockRepositoryInterface::class, BlockRepository::class);
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [
            BlockInterface::class,
            DynamicBlockInterface::class,
            BlockIypeInterface::class,
            BlockRepositoryInterface::class,
        ];
    }
}
