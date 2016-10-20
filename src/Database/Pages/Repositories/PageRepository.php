<?php
namespace Soda\Cms\Database\Pages\Repositories;

use Illuminate\Http\Request;
use Soda;
use Soda\Cms\Database\Pages\Interfaces\PageInterface;
use Soda\Cms\Database\Pages\Interfaces\PageRepositoryInterface;
use Soda\Cms\Database\Support\Repositories\AbstractRepository;
use Soda\Cms\Support\Constants;

class PageRepository extends AbstractRepository implements PageRepositoryInterface
{
    protected $model;

    public function __construct(PageInterface $model)
    {
        $this->model = $model;
    }

    public function findBySlug($slug)
    {
        return $this->model->where('slug', '/'.ltrim($slug, '/'))->first();
    }

    public function getTypes()
    {
        return $this->model->type()->getRelated()->get();
    }

    public function getTree()
    {
        $page = $this->getRoot() ?: $this->createRoot();

        return $page ? $page->collectDescendants(true)->get()->toTree() : [];
    }

    public function getRoot()
    {
        return $this->model->getRoots()->first();
    }

    public function createStub($parentId = null, $pageTypeId = null)
    {
        $parent = $parentId ? $this->findById($parentId) : $this->getRoot();

        $page = $this->newInstance([
            'parent_id'    => $parent ? $parentId : null,
            'page_type_id' => $pageTypeId,
        ]);

        if ($pageTypeId) {
            $page->load('type');

            if ($page->relationLoaded('type')) {
                $page->action = $page->getRelation('type')->getAttribute('action');
                $page->action_type = $page->getRelation('type')->getAttribute('action_type');
                $page->edit_action = $page->getRelation('type')->getAttribute('edit_action');
                $page->edit_action_type = $page->getRelation('type')->getAttribute('edit_action_type');
            }
        }

        return $page;
    }

    public function createRoot()
    {
        return $this->model->create([
            'name'           => 'Homepage',
            'slug'           => '/',
            'parent_id'      => null,
            'application_id' => Soda::getApplication()->getKey(),
            'position'       => 0,
            'real_depth'     => 0,
            'status'         => Constants::STATUS_LIVE,
        ]);
    }

    public function save(Request $request, $id = null)
    {
        if($id) {
            $page = $this->model->findOrFail($id);
            $page->fill($request->all())->save();
        } else {
            $page = $this->initializePage($request);
        }

        $page->load('type');

        if ($page->relationLoaded('type') && $request->has('settings')) {
            $this->saveSettings($page, $request->input('settings'));
        }

        return $page;
    }

    protected function initializePage(Request $request)
    {
        $page = $this->newInstance($request->except(['slug', 'application_id']));
        $parent = $page->getAttribute('parent_id') ? $this->model->findOrFail($page->getAttribute('parent_id')) : $this->getRoot();

        $slug = $page->generateSlug($request->input('slug'));

        if($parent && !starts_with($slug, $parent->getAttribute('slug'))) {
            $slug = $parent->generateSlug($request->input('slug'));
        }

        $page->fill([
            'parent_id'      => $parent->getKey(),
            'slug'           => $slug,
            'application_id' => Soda::getApplication()->getKey(),
        ])->save();


        if ($parent) {
            $parent->addChild($page);
        }

        return $page;
    }

    protected function saveSettings(PageInterface $page, $settings)
    {
        return Soda::dynamicPage($page->getRelation('type')->getAttribute('identifier'))
            ->firstOrNew(['page_id' => $page->getKey()])
            ->fill($settings)
            ->save();
    }
}
