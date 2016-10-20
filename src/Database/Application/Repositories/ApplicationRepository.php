<?php namespace Soda\Cms\Database\Application\Repositories;

use Soda\Cms\Database\Application\Interfaces\ApplicationInterface;
use Soda\Cms\Database\Application\Interfaces\ApplicationUrlInterface;
use Soda\Cms\Database\Application\Interfaces\ApplicationRepositoryInterface;

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
            return $this->findById($url->getAttribute('application_id'));
        }

        return null;
    }
}
