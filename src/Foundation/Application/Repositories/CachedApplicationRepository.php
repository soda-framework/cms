<?php namespace Soda\Cms\Foundation\Application\Repositories;

use Soda\Cms\Foundation\Application\Interfaces\ApplicationRepositoryInterface;
use Soda\Cms\Foundation\Application\Interfaces\CachedApplicationRepositoryInterface;
use Soda\Cms\Foundation\Support\Repositories\AbstractCacheRepository;

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
