<?php namespace Soda\Cms\Database\Application\Repositories;

use Illuminate\Http\Request;
use Soda\Cms\Database\Application\Interfaces\ApplicationInterface;
use Soda\Cms\Database\Application\Interfaces\ApplicationRepositoryInterface;
use Soda\Cms\Database\Application\Interfaces\ApplicationUrlInterface;
use Soda\Cms\Database\Support\Repositories\Traits\BuildsDataGrids;
use Soda\Cms\Support\Facades\Soda;

class ApplicationRepository implements ApplicationRepositoryInterface
{
    use BuildsDataGrids;

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

    public function getApplication($id = null)
    {
        if ($id !== null) {
            return $this->findById($id);
        } else {
            return Soda::getApplication();
        }
    }

    public function findByUrl($url)
    {
        $domain = str_replace('www.', '', $url);

        if ($url = $this->urlModel->where('domain', $domain)->first()) {
            return $this->findById($url->getAttribute('application_id'));
        }

        return null;
    }

    public function save(Request $request, $id = null)
    {
        $model = $this->getApplication($id);

        $model->fill($request->input())->save();

        if ($request->has('application_urls')) {
            $newUrls = $request->input('application_urls');
            $currentUrls = $model->urls()->pluck('domain')->toArray();

            $detach = array_diff($currentUrls, $newUrls);
            $attach = [];

            foreach (array_diff($newUrls, $currentUrls) as $new) {
                $attach[] = ['domain' => $new, 'application_id' => $model->id];
            };

            if (count($detach)) {
                // Remove detachable URLs, EXCEPT our current host!
                $model->urls()->whereIn('domain', $detach)->where('domain', '!=', str_replace('www.', '', $_SERVER['HTTP_HOST']))->delete();
            }

            if (count($attach)) {
                $model->urls()->insert($attach);
            }
        }

        return $model;
    }

    public function getSettingsForApplication(ApplicationInterface $application)
    {
        if (!$application->relationLoaded('settings')) {
            $application->load('settings');
        }

        $settings = $application->getRelation('settings');

        if ($settings !== null) {
            $settings = $settings->groupBy('category');

            // Move 'Settings' category to the start
            if (isset($settings['Settings'])) {
                $defaultCategory = $settings->pull('Settings');

                return $settings->prepend($defaultCategory, 'Settings');
            }
        }

        return [];
    }

    public function getFilteredGrid($perPage)
    {
        $filter = $this->buildFilter($this->appModel);
        $grid = $this->buildGrid($filter);
        $grid = $this->addButtonsToGrid($grid, 'soda.application.edit', 'soda.application.destroy');
        $grid->paginate($perPage)->getGrid($this->getGridView());

        return compact('filter', 'grid');
    }
}
