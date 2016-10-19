<?php namespace Soda\Cms\Database\Application\Repositories;

use Soda\Cms\Database\Application\Interfaces\ApplicationRepositoryInterface;
use Soda\Cms\Database\Application\Interfaces\CachedApplicationRepositoryInterface;
use Soda\Cms\Database\Support\Repositories\AbstractCacheRepository;

class CachedApplicationRepository extends AbstractCacheRepository implements CachedApplicationRepositoryInterface
{
    protected $repository;

    public function __construct(ApplicationRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function findByUrl($url)
    {
        $application = $this->cache($this->getApplicationUrlCacheKey($url), config('soda.cache.application'), function () use ($url) {
            return $this->repository->findByUrl($url);
        });

        if (!$application) {
            $this->cache->forget($this->getApplicationUrlCacheKey($url));
        }

        return $application;
    }

    protected function getApplicationUrlCacheKey($url)
    {
        return 'soda.application:'.$url;
    }
}
