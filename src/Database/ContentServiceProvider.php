<?php

namespace Soda\Cms\Database;

use Soda\Cms\Database\Models\Field;
use Soda\Cms\Database\Models\Content;
use Illuminate\Support\ServiceProvider;
use Soda\Cms\Database\Models\BlockType;
use Soda\Cms\Database\Models\ContentType;
use Soda\ClosureTable\ClosureTableServiceProvider;
use Illuminate\Database\Eloquent\Relations\Relation;
use Soda\Cms\Database\Repositories\ContentRepository;
use Soda\Cms\Database\Repositories\ContentTypeRepository;
use Soda\Cms\Database\Repositories\ContentBlockRepository;
use Soda\Cms\Database\Repositories\ContentTypeBlockRepository;
use Soda\Cms\Database\Repositories\ContentTypeFieldRepository;
use Soda\Cms\Database\Repositories\Contracts\ContentRepositoryInterface;
use Soda\Cms\Foundation\Providers\Traits\RegistersBindingsAndDependencies;
use Soda\Cms\Database\Repositories\Contracts\ContentTypeRepositoryInterface;
use Soda\Cms\Database\Repositories\Contracts\ContentBlockRepositoryInterface;
use Soda\Cms\Database\Repositories\Contracts\ContentTypeBlockRepositoryInterface;
use Soda\Cms\Database\Repositories\Contracts\ContentTypeFieldRepositoryInterface;

class ContentServiceProvider extends ServiceProvider
{
    use RegistersBindingsAndDependencies;
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;

    protected $aliases = [
        'soda.content.repository'            => [ContentRepositoryInterface::class, ContentRepository::class],
        'soda.content-type.repository'       => [ContentTypeRepositoryInterface::class, ContentTypeRepository::class],
        'soda.content-type-block.repository' => [ContentTypeBlockRepositoryInterface::class, ContentTypeBlockRepository::class],
        'soda.content-type-field.repository' => [ContentTypeFieldRepositoryInterface::class, ContentTypeFieldRepository::class],
        'soda.content-block.repository'      => [ContentBlockRepositoryInterface::class, ContentBlockRepository::class],
    ];

    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        Relation::morphMap([
            'ContentType' => ContentType::class,
        ]);

        app('soda.drafting')->registerDraftables([
            Content::class,
            ContentType::class,
        ]);
    }

    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function register()
    {
        $this->registerDependencies([
            ClosureTableServiceProvider::class,
        ]);

        $this->app->singleton('soda.content.repository', function ($app) {
            return new ContentRepository(new Content);
        });

        $this->app->singleton('soda.content-type.repository', function ($app) {
            return new ContentTypeRepository(new ContentType);
        });

        $this->app->singleton('soda.content-type-block.repository', function ($app) {
            return new ContentTypeBlockRepository(new ContentType, new BlockType);
        });

        $this->app->singleton('soda.content-type-field.repository', function ($app) {
            return new ContentTypeFieldRepository(new ContentType, new Field);
        });

        $this->app->singleton('soda.content-block.repository', function ($app) {
            return new ContentBlockRepository(new Content, new BlockType);
        });

        $this->app->singleton('soda.content.cached-repository', function ($app) {
            return new CachedPContent($app['soda.content.repository']);
        });

        $this->registerAliases($this->aliases);

        if ( config('soda.cms.enable_publish_date') ) {
            Content::setPublishDateField('published_at');
        }
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
