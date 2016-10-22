<?php namespace Soda\Cms\Database\Application\Repositories;

use Soda\Cms\Database\Application\Interfaces\ApplicationInterface;
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
            $this->forget($this->getApplicationUrlCacheKey($url));
        }

        return $application;
    }

    public function getSettingsForApplication(ApplicationInterface $application)
    {
        return $this->cache($this->getApplicationSettingsCacheKey($application->getKey()), config('soda.cache.application'), function () use ($application) {
            if (!$application->relationLoaded('settings')) {
                $application->load('settings');
            }

            $settings = $application->getRelation('settings');

            if ($settings !== null) {
                return $settings;
            }

            return [];
        });
    }

    protected function getApplicationUrlCacheKey($url)
    {
        return 'soda.application:'.$url;
    }

    protected function getApplicationSettingsCacheKey($appId)
    {
        return 'soda.application.'.$appId.':settings';
    }
}
