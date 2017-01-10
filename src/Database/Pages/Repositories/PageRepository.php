<?php
namespace Soda\Cms\Database\Pages\Repositories;

use Illuminate\Http\Request;
use Soda\Cms\Database\Pages\Interfaces\PageInterface;
use Soda\Cms\Database\Pages\Interfaces\PageRepositoryInterface;
use Soda\Cms\Database\Support\Repositories\AbstractRepository;
use Soda\Cms\Support\Constants;
use Soda\Cms\Support\Facades\Soda;

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

    public function getTypes($creatableOnly = false)
    {
        $query = $this->model->type()->getRelated();

        if($creatableOnly)
        {
            $query->where('can_create', 1);
        }

        return $query->get();
    }

    public function getBlockTypes()
    {
        return app('soda.block-type.repository')->getAll();
    }

    public function getAvailableBlockTypes(PageInterface $page)
    {
        if (!$page->relationLoaded('block_types')) {
            $page->load('block_types');
        }

        return $this->getBlockTypes()->diff($page->getRelation('block_types'));
    }

    public function getTree()
    {
        $page = $this->getRoot() ?: $this->createRoot();

        return $page ? $page->collectDescendants(true)->orderBy('parent_id', 'ASC')->orderBy('position', 'ASC')->get()->toTree() : [];
    }

    public function getRoot()
    {
        return $this->model->getRoots()->first();
    }

    public function createStub($parentId = null, $pageTypeId = null)
    {
        $parent = $parentId ? $this->findById($parentId) : $this->getRoot();

        if($parent->type) {
            $allowedPageTypes = $parent->type->subpageTypes->pluck('id')->toArray();
            if(count($allowedPageTypes) && !in_array($pageTypeId, $allowedPageTypes)) {
                throw new \Exception('You cannot create a page of this type here');
            }
        }

        if(!$parent->allowed_children)
        {
            throw new \Exception('You cannot create a subpage here');
        }

        $page = $this->newInstance([
            'parent_id'    => $parent ? $parentId : null,
            'page_type_id' => $pageTypeId,
        ]);

        if ($pageTypeId) {
            $page->load('type');

            if ($page->relationLoaded('type')) {
                $page->fill([
                    'view_action'      => $page->getRelation('type')->getAttribute('view_action'),
                    'view_action_type' => $page->getRelation('type')->getAttribute('view_action_type'),
                    'edit_action'      => $page->getRelation('type')->getAttribute('edit_action'),
                    'edit_action_type' => $page->getRelation('type')->getAttribute('edit_action_type'),
                ]);
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
        if ($id) {
            $page = $this->model->findOrFail($id);
            $page->fill($request->all());
            $page->slug = $page->generateSlug($request->input('slug'), false);
            $page->save();
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
        $parentPage = $page->getAttribute('parent_id') ? $this->model->findOrFail($page->getAttribute('parent_id')) : $this->getRoot();

        $slug = $page->generateSlug($request->input('slug'));

        if ($parentPage && !starts_with($slug, $parentPage->getAttribute('slug'))) {
            $slug = $parentPage->generateSlug($request->input('slug'));
        }

        $page->fill([
            'parent_id'      => $parentPage->getKey(),
            'slug'           => $slug,
            'application_id' => Soda::getApplication()->getKey(),
        ])->save();

        if ($parentPage) {
            $parentPage->addChild($page);
        }

        return $page;
    }

    protected function saveSettings(PageInterface $page, $settings)
    {
        $dynamicPageRecord = Soda::dynamicPage($page->getRelation('type')->getAttribute('identifier'))->firstOrNew(['page_id' => $page->getKey()]);

        foreach ($page->type->fields as $field) {
            if ($settings !== null) {
                $dynamicPageRecord->parseField($field, $settings, 'settings');
            }
        }

        return $dynamicPageRecord->save();
    }
}
