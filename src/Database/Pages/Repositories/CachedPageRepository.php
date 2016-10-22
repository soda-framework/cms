<?php namespace Soda\Cms\Database\Pages\Repositories;

use Soda\Cms\Database\Pages\Interfaces\CachedPageRepositoryInterface;
use Soda\Cms\Database\Pages\Interfaces\PageInterface;
use Soda\Cms\Database\Pages\Interfaces\PageRepositoryInterface;
use Soda\Cms\Database\Support\Repositories\AbstractCacheRepository;
use Soda\Cms\Support\Facades\Soda;

class CachedPageRepository extends AbstractCacheRepository implements CachedPageRepositoryInterface
{
    protected $repository;

    public function __construct(PageRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function findBySlug($slug)
    {
        return $this->cache($this->getSluggablePageCacheKey($slug), config('soda.cache.pages'), function () use ($slug) {
            $page = $this->repository->findBySlug($slug);
            if (config('soda.cache.pages') && config('soda.cache.page-type') === true) {
                $page->load('type');
            }
            if (config('soda.cache.pages') && config('soda.cache.page-blocks') === true) {
                $page->load('blocks');
            }

            return $page;
        });
    }

    public function getAttributesForPage(PageInterface $page)
    {
        return $this->cache($this->getPageAttributesCacheKey($page->getKey()), config('soda.cache.page-data'), function () use ($page) {
            if (!$page->relationLoaded('type')) {
                $page->load('type');
            }

            $model = $page->getDynamicModel();

            if ($page->relationLoaded('type')) {
                $model = $model->fromTable($page->getRelation('type')->getAttribute('identifier'))->firstOrNew([
                    'page_id' => $page->getKey(),
                ]);
            }

            return $model;
        });
    }

    protected function getSluggablePageCacheKey($slug)
    {
        return 'soda.'.Soda::getApplication()->id.'.page-slug:'.$slug;
    }

    protected function getPageAttributesCacheKey($pageId)
    {
        return 'soda.'.Soda::getApplication()->id.'.page.'.$pageId.'.data';
    }
}
