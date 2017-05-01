<?php

namespace Soda\Cms\Database\Repositories\Cachable;

use Illuminate\Http\Request;
use Soda\Cms\Support\Facades\Soda;
use Soda\Cms\Database\Models\Contracts\ContentInterface;
use Soda\Cms\Database\Repositories\Contracts\ContentRepositoryInterface;

class ContentRepository extends AbstractCacheRepository implements ContentRepositoryInterface
{
    protected $repository;

    public function __construct(ContentRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function findBySlug($slug)
    {
        return $this->cache($this->getSluggableContentCacheKey($slug), config('soda.cache.content'), function () use ($slug) {
            $content = $this->repository->findBySlug($slug);
            if (config('soda.cache.content') && config('soda.cache.content-type') === true) {
                $content->load('type');
            }
            if (config('soda.cache.content') && config('soda.cache.content-blocks') === true) {
                $content->load('blocks');
            }

            return $content;
        });
    }

    public function getAttributesForContent(ContentInterface $content)
    {
        return $this->cache($this->getContentAttributesCacheKey($content->getKey()), config('soda.cache.content-data'), function () use ($content) {
            if (! $content->relationLoaded('type')) {
                $content->load('type');
            }

            $model = $content->getDynamicModel();

            if ($content->relationLoaded('type')) {
                $model = $model->fromTable($content->getRelation('type')->getAttribute('identifier'))->firstOrNew([
                    'content_id' => $content->getKey(),
                ]);
            }

            return $model;
        });
    }

    protected function getSluggableContentCacheKey($slug)
    {
        return 'soda.'.Soda::getApplication()->id.'.content-slug:'.$slug;
    }

    protected function getContentAttributesCacheKey($contentId)
    {
        return 'soda.'.Soda::getApplication()->id.'.content.'.$contentId.':attributes';
    }

    /**
     * @param int $id
     */
    public function findById($id)
    {
        return $this->repository->findById($id);
    }

    /**
     * @param int $id
     */
    public function save(Request $request, $id = null)
    {
        return $this->repository->save($request, $id);
    }

    /**
     * @param int $id
     */
    public function destroy($id)
    {
        return $this->repository->destroy($id);
    }

    /**
     * @param Request $attributes
     */
    public function newInstance($attributes)
    {
        return $this->repository->newInstance($attributes);
    }

    public function getBlockTypes()
    {
        return $this->repository->getBlockTypes();
    }

    public function getAvailableBlockTypes(ContentInterface $content)
    {
        return $this->repository->getAvailableBlockTypes($content);
    }

    public function getTypes($creatableOnly = false)
    {
        return $this->repository->getTypes($creatableOnly);
    }

    public function getTree()
    {
        return $this->repository->getTree();
    }

    public function getRoot()
    {
        return $this->repository->getRoot();
    }

    /**
     * @return ContentInterface
     */
    public function createRoot()
    {
        return $this->repository->createRoot();
    }

    public function createStub($parentId = null, $contentTypeId = null)
    {
        return $this->repository->createStub($parentId, $contentTypeId);
    }
}
