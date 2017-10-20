<?php

namespace Soda\Cms\Database\Repositories\Cachable;

use Soda\Cms\Database\Models\Contracts\ApplicationInterface;
use Soda\Cms\Database\Repositories\Contracts\ApplicationRepositoryInterface;

class ApplicationRepository extends AbstractCacheRepository implements ApplicationRepositoryInterface
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

        if (! $application) {
            $this->forget($this->getApplicationUrlCacheKey($url));
        }

        return $application;
    }

    protected function getApplicationUrlCacheKey($url)
    {
        return 'soda.application:'.$url;
    }

    public function getSettingsForApplication(ApplicationInterface $application)
    {
        return $this->cache($this->getApplicationSettingsCacheKey($application->getKey()), config('soda.cache.application'), function () use ($application) {
            if (! $application->relationLoaded('settings')) {
                $application->load('settings');
            }

            $settings = $application->getRelation('settings');

            if ($settings !== null) {
                return $settings;
            }

            return [];
        });
    }

    protected function getApplicationSettingsCacheKey($appId)
    {
        return 'soda.application.'.$appId.':settings';
    }

    public function findById($id)
    {
        $this->repository->findById($id);
    }
}
