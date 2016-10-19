<?php namespace Soda\Cms\Foundation\Application\Repositories;

use Soda\Cms\Foundation\Application\Interfaces\ApplicationInterface;
use Soda\Cms\Foundation\Application\Interfaces\ApplicationUrlInterface;
use Soda\Cms\Foundation\Application\Interfaces\ApplicationRepositoryInterface;

class ApplicationRepository implements ApplicationRepositoryInterface
{
    protected $appModel;
    protected $urlModel;

    public function __construct(ApplicationInterface $appModel, ApplicationUrlInterface $urlModel)
    {
        $this->appModel = $appModel;
        $this->urlModel = $urlModel;
    }

    public function findById($id)
    {
        return $this->appModel->find($id);
    }

    public function findByUrl($url)
    {
        $domain = str_replace('www.', '', $url);

        if ($url = $this->urlModel->where('domain', $domain)->first()) {
            return $this->findById($url->application_id);
        }

        return null;
    }
}
