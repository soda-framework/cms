<?php

namespace Soda\Cms\Database\Repositories;

use Illuminate\Http\Request;
use Soda\Cms\Database\Models\Contracts\ContentInterface;
use Soda\Cms\Database\Repositories\Contracts\ContentRepositoryInterface;
use Soda\Cms\Foundation\Constants;
use Soda\Cms\Support\Facades\Soda;

class ContentRepository extends AbstractRepository implements ContentRepositoryInterface
{
    protected $model;
    protected $perPage = 20;

    public function __construct(ContentInterface $model)
    {
        $this->model = $model;
    }

    public function getTree()
    {
        $content = $this->getRoot() ?: $this->createRoot();

        return $content ? $content->collectDescendants(true)->orderBy('parent_id', 'ASC')->orderBy('position', 'ASC')->get()->toTree() : [];
    }

    public function listFolder($contentId = null)
    {
        if (!$contentId) {
            $contentRoot = $this->model->getRoots()->first();

            if (!$contentRoot) {
                $contentRoot = $this->model->newInstance([
                    'name'           => 'Root',
                    'slug'           => null,
                    'parent_id'      => null,
                    'application_id' => Soda::getApplication()->getKey(),
                    'position'       => 0,
                    'real_depth'     => 0,
                    'status'         => Constants::STATUS_LIVE,
                    'is_sluggable'   => 0,
                    'is_movable'     => 0,
                    'is_folder'      => 1,
                ])->fillDefaults();

                $contentRoot->save();
            }

            $contentId = $contentRoot->id;
        }

        return $this->model->where('parent_id', $contentId)->paginate($this->perPage);
    }

    public function findBySlug($slug)
    {
        return $this->model->where('slug', '/'.ltrim($slug, '/'))->first();
    }

    public function getTypes($creatableOnly = false)
    {
        $query = $this->model->type()->getRelated();

        if ($creatableOnly) {
            $query->where('is_creatable', 1);
        }

        return $query->get();
    }

    public function getBlockTypes()
    {
        return app('soda.block-type.repository')->getAll();
    }

    public function getAvailableBlockTypes(ContentInterface $content)
    {
        if (!$content->relationLoaded('blockTypes')) {
            $content->load('blockTypes');
        }

        return $this->getBlockTypes()->diff($content->getRelation('blockTypes'));
    }

    public function createStub($parentId = null, $contentTypeId = null)
    {
        $parent = $parentId ? $this->findById($parentId) : $this->getRoot();

        if (!$parent->isFolder()) {
            throw new \Exception('You cannot create that content here.');
        }

        if ($parent->type) {
            $allowedContentTypes = $parent->type->pageTypes ? $parent->type->pageTypes->pluck('id')->toArray() : [];
            if (count($allowedContentTypes) && !in_array($contentTypeId, $allowedContentTypes)) {
                throw new \Exception('You cannot create that content here.');
            }
        }

        $content = $this->newInstance([
            'parent_id'       => $parent ? $parentId : null,
            'content_type_id' => $contentTypeId,
        ]);

        if ($contentTypeId) {
            $content->load('type');

            if ($content->relationLoaded('type')) {
                $content->fill([
                    'view_action'      => $content->getRelation('type')->getAttribute('view_action'),
                    'view_action_type' => $content->getRelation('type')->getAttribute('view_action_type'),
                    'edit_action'      => $content->getRelation('type')->getAttribute('edit_action'),
                    'edit_action_type' => $content->getRelation('type')->getAttribute('edit_action_type'),
                ]);
            }
        }

        return $content;
    }

    public function save(Request $request, $id = null)
    {
        if ($id !== null) {
            $content = $this->model->findOrFail($id);
            $content->fill($request->all())->fillDefaults();
            $content->slug = $content->generateSlug($request->input('slug'), false);
        } else {
            $content = $this->initializeContent($request);
        }

        if ($content->content_type_id) {
            $content->load('type');

            if ($content->relationLoaded('type')) {
                $this->saveSettings($content, $request);
            }
        }

        $content->save();
        if ($content->properties) {
            $content->properties->save();
        } // Save last so attributes are available in saving event above

        return $content;
    }

    protected function initializeContent(Request $request)
    {
        $content = $this->newInstance($request->except(['slug', 'application_id']));
        $parentContent = $content->getAttribute('parent_id') ? $this->model->findOrFail($content->getAttribute('parent_id')) : $this->getRoot();

        $slug = $content->generateSlug($request->input('slug'));

        if ($parentContent && !starts_with($slug, $parentContent->getAttribute('slug'))) {
            $slug = $parentContent->generateSlug($request->input('slug'));
        }

        $content->fill([
            'parent_id'      => $parentContent->getKey(),
            'slug'           => $slug,
            'application_id' => Soda::getApplication()->getKey(),
        ])->fillDefaults()->save();

        if ($parentContent) {
            $parentContent->addChild($content);
        }

        return $content;
    }

    protected function saveSettings(ContentInterface $content, Request $request)
    {
        if ($content->type !== null) {
            $content->setRelation('properties', Soda::dynamicContent($content->getRelation('type')->getAttribute('identifier'))->firstOrNew(['content_id' => $content->getKey()]));

            foreach ($content->type->fields as $field) {
                if ($request->input('settings') !== null || $request->file('settings') !== null) {
                    $content->properties->parseField($field, $request, 'settings');
                }
            }

            return true;
        }

        return false;
    }
}
