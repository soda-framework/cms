<?php

namespace Soda\Cms\Database\Repositories;

use Illuminate\Http\Request;
use Soda\Cms\Foundation\Constants;
use Soda\Cms\Support\Facades\Soda;
use Soda\Cms\Database\Models\ContentShortcut;
use Soda\Cms\Database\Models\Contracts\ContentInterface;
use Soda\Cms\Database\Repositories\Contracts\ContentRepositoryInterface;

class ContentRepository extends AbstractRepository implements ContentRepositoryInterface
{
    protected $model;

    public function __construct(ContentInterface $model)
    {
        $this->model = $model;
    }

    public function getTree()
    {
        $content = $this->getRoot() ?: $this->createRoot();

        return $content ? $content->collectDescendants(true)->orderBy('parent_id', 'ASC')->orderBy('position', 'ASC')->get()->toTree() : [];
    }

    public function getRoot()
    {
        $contentRoot = $this->model->getRoots()->first();

        if (! $contentRoot) {
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

        return $contentRoot;
    }

    public function listFolder(Request $request, ContentInterface $contentFolder)
    {
        return $this->model->withoutGlobalScope('position')->where('parent_id', $contentFolder->id)
            ->orderBy($request->input('order', 'position'), $request->input('dir', 'ASC'))
            ->paginate($request->input('show', 20))
            ->appends($request->only('order', 'dir', 'show'));
    }

    public function findBySlug($slug)
    {
        return $this->model->where('slug', '/'.ltrim($slug, '/'))->first();
    }

    public function getCreatableContentTypes($contentFolderId = null)
    {
        $content = $contentFolderId == null ? $this->getRoot() : $this->model->find($contentFolderId);

        if ($content && $content->content_type_id !== null) {
            $creatableTypes = $content->type->pageTypes()->where('is_creatable', true)->get();

            if ($creatableTypes) {
                return $creatableTypes;
            }
        }

        return $this->getTypes(true);
    }

    public function getTypes($creatableOnly = false)
    {
        if ($this->model->content_type_id) {
            $query = $this->model->type()->getRelated();

            if ($creatableOnly) {
                $query->where('is_creatable', 1);
            }

            return $query->get();
        }

        return collect();
    }

    public function getBlockTypes()
    {
        return app('soda.block-type.repository')->getAll();
    }

    public function getAvailableBlockTypes(ContentInterface $content)
    {
        if (! $content->relationLoaded('blockTypes')) {
            $content->load('blockTypes');
        }

        return $this->getBlockTypes()->diff($content->getRelation('blockTypes'));
    }

    public function getShortcuts(ContentInterface $content)
    {
        $creatableTypes = $content->type ? $content->type->pageTypes()->get() : [];

        $shortcutsQuery = ContentShortcut::where(function ($sq) use ($content) {
            $sq->whereNull('parent_id')->orWhere('parent_id', $content->content_type_id);
        });

        if (count($creatableTypes)) {
            $shortcutsQuery->whereIn('content_type_id', $creatableTypes->where('is_creatable', true)->pluck('id')->toArray());
        }

        $shortcuts = $shortcutsQuery->get();

        if (! count($creatableTypes)) {
            if (! $shortcuts->where('is_folder', true)->where('override_default', true)->count()) {
                $shortcuts->push(new ContentShortcut([
                    'text'             => 'New Content Folder',
                    'is_folder'        => 1,
                ]));
            }

            if (! $shortcuts->where('is_folder', false)->where('override_default', true)->count()) {
                $shortcuts->push(new ContentShortcut([
                    'text'             => 'New Content Item',
                    'is_folder'        => 0,
                ]));
            }
        }

        return $shortcuts;
    }

    public function createStub($parentId = null, $contentTypeId = null)
    {
        $parent = $parentId ? $this->findById($parentId) : $this->getRoot();

        if (! $parent->isFolder()) {
            throw new \Exception('You cannot create that content here.');
        }

        if ($parent->type) {
            $allowedContentTypes = $parent->type->pageTypes ? $parent->type->pageTypes->pluck('id')->toArray() : [];
            if (count($allowedContentTypes) && ! in_array($contentTypeId, $allowedContentTypes)) {
                throw new \Exception('You cannot create that content here.');
            }
        }

        $content = $this->newInstance([
            'parent_id'       => $parent ? $parentId : null,
            'content_type_id' => $contentTypeId,
            'is_deletable'    => 1,
            'is_movable'      => 1,
            'is_sluggable'    => 1,
            'is_folder'       => 0,
        ]);

        if ($contentTypeId) {
            $content->load('type');

            if ($content->relationLoaded('type')) {
                $content->fill([
                    'view_action'      => $content->getRelation('type')->getAttribute('view_action'),
                    'view_action_type' => $content->getRelation('type')->getAttribute('view_action_type'),
                    'edit_action'      => $content->getRelation('type')->getAttribute('edit_action'),
                    'edit_action_type' => $content->getRelation('type')->getAttribute('edit_action_type'),
                    'is_sluggable'     => $content->getRelation('type')->getAttribute('is_sluggable'),
                    'is_movable'       => $content->getRelation('type')->getAttribute('is_movable'),
                    'is_folder'        => $content->getRelation('type')->getAttribute('is_folder'),
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

        $this->saveSettings($content, $request);

        $content->save();
        if ($content->type !== null && $content->type->shouldDynamicTableExist() && count($content->properties->toArray())) {
            $content->properties->save();
        } // Save last so attributes are available in saving event above

        return $content;
    }

    protected function initializeContent(Request $request)
    {
        $content = $this->newInstance($request->except(['slug', 'application_id']));
        $parentContent = $content->getAttribute('parent_id') ? $this->model->findOrFail($content->getAttribute('parent_id')) : $this->getRoot();

        $slug = $content->generateSlug($request->input('slug'));

        if ($parentContent && ! starts_with($slug, $parentContent->getAttribute('slug'))) {
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
        if ($content->type !== null && $content->type->shouldDynamicTableExist()) {
            $content->setRelation('properties', Soda::dynamicContent($content->getRelation('type')->getAttribute('identifier'))->firstOrNew(['content_id' => $content->getKey()]));

            foreach ($content->type->fields as $field) {
                if ($request->input("settings.$field->field_name") !== null || $request->file("settings.$field->field_name") !== null) {
                    $content->properties->parseField($field, $request, 'settings');
                }
            }

            return true;
        }

        return false;
    }
}
