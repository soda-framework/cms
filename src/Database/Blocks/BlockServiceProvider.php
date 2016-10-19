<?php

namespace Soda\Cms\Database\Blocks;

use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\ServiceProvider;
use Soda\Cms\Database\Blocks\Interfaces\BlockRepositoryInterface;
use Soda\Cms\Database\Blocks\Interfaces\BlockTypeRepositoryInterface;
use Soda\Cms\Database\Blocks\Models\DynamicBlock;
use Soda\Cms\Database\Blocks\Models\Block;
use Soda\Cms\Database\Blocks\Models\BlockType;
use Soda\Cms\Database\Blocks\Interfaces\BlockInterface;
use Soda\Cms\Database\Blocks\Interfaces\BlockTypeInterface;
use Soda\Cms\Database\Blocks\Interfaces\DynamicBlockInterface;
use Soda\Cms\Database\Blocks\Repositories\BlockRepository;
use Soda\Cms\Database\Blocks\Repositories\BlockTypeRepository;

class BlockServiceProvider extends ServiceProvider
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
        $this->app->bind(BlockTypeRepositoryInterface::class, BlockTypeRepository::class);
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
            BlockTypeRepositoryInterface::class,
        ];
    }
}
