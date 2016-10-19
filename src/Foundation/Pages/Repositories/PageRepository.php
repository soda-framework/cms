<?php
namespace Soda\Cms\Foundation\Pages\Repositories;

use Illuminate\Http\Request;
use Soda;
use Soda\Cms\Foundation\Pages\Interfaces\PageInterface;
use Soda\Cms\Foundation\Pages\Interfaces\PageRepositoryInterface;
use Soda\Cms\Support\Constants;

class PageRepository implements PageRepositoryInterface
{
    protected $model;

    public function __construct(PageInterface $model)
    {
        $this->model = $model;
    }

    public function findById($id)
    {
        return $this->model->find($id);
    }

    public function findBySlug($slug)
    {
        return $this->model->where('slug', '/'.ltrim($slug, '/'))->first();
    }

    public function getPageTypes()
    {
        return $this->model->type()->getRelated()->get();
    }

    public function getPageTree()
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

        $page = $this->model->newInstance([
            'parent_id'    => $parent ? $parentId : null,
            'page_type_id' => $pageTypeId,
        ]);

        if ($pageTypeId) {
            $page->load('type');

            if ($page->relationLoaded('type')) {
                $page->action = $page->type->action;
                $page->action_type = $page->type->action_type;
                $page->edit_action = $page->type->edit_action;
                $page->edit_action_type = $page->type->edit_action_type;
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
            'application_id' => Soda::getApplication()->id,
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

    public function destroy($id)
    {
        $page = $this->model->find($id);
        $page->delete();

        return $page;
    }

    protected function initializePage(Request $request)
    {
        $page = $this->model->newInstance($request->except(['slug', 'application_id']));
        $parent = $page->parent_id ? $this->model->findOrFail($page->parent_id) : $this->getRoot();

        $slug = $page->generateSlug($request->input('slug'));

        if($parent && !starts_with($slug, $parent->slug)) {
            $slug = $parent->generateSlug($request->input('slug'));
        }

        $page->fill([
            'parent_id'      => $parent->id,
            'slug'           => $slug,
            'application_id' => Soda::getApplication()->id,
        ])->save();


        if ($parent) {
            $parent->addChild($page);
        }

        return $page;
    }

    protected function saveSettings(PageInterface $page, $settings)
    {
        return Soda::dynamicPage($page->type->identifier)
            ->firstOrNew(['page_id' => $page->id])
            ->fill($settings)
            ->save();
    }
}
