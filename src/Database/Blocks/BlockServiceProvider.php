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
use Soda\Cms\Foundation\Providers\Traits\RegistersBindings;

class BlockServiceProvider extends ServiceProvider
{
    use RegistersBindings;
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;

    protected $bindings = [
        'soda.block.model' => [
            'abstract' => BlockInterface::class,
            'concrete' => Block::class,
        ],
        'soda.dynamic-block.model' => [
            'abstract' => DynamicBlockInterface::class,
            'concrete' => DynamicBlock::class,
        ],
        'soda.block-type.model' => [
            'abstract' => BlockTypeInterface::class,
            'concrete' => BlockType::class,
        ],
        'soda.block.repository' => [
            'instance' => true,
            'abstract' => BlockRepositoryInterface::class,
            'concrete' => BlockRepository::class,
        ],
        'soda.block-type.repository' => [
            'instance' => true,
            'abstract' => BlockTypeRepositoryInterface::class,
            'concrete' => BlockTypeRepository::class,
        ],
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
