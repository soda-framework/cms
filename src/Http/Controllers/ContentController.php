<?php

namespace Soda\Cms\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Soda\Cms\Database\Models\Contracts\ContentInterface;
use Soda\Cms\Database\Repositories\Contracts\ContentRepositoryInterface;

class ContentController extends BaseController
{
    protected $contents;

    public function __construct(ContentRepositoryInterface $content)
    {
        $this->content = $content;

        $this->middleware(function ($request, $next) {
            app('soda.interface')->setHeading(ucfirst(trans('soda::terminology.content_plural')))->setHeadingIcon('mdi mdi-file-outline');
            app('soda.interface')->breadcrumbs()->addLink(route('soda.home'), ucfirst(trans('soda::terminology.home')));

            return $next($request);
        });

        $this->middleware('soda.permission:view-pages');
        $this->middleware('soda.permission:create-pages')->only(['create', 'store']);
        $this->middleware('soda.permission:edit-pages')->only(['edit', 'update']);
        $this->middleware('soda.permission:delete-pages')->only(['delete']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(Request $request)
    {
        $contentFolder = $this->content->getRoot();

        $content = $this->content->listFolder($request, $contentFolder);

        if(!$request->has('search')) {
            $contentTypes = $this->content->getCreatableContentTypes($contentFolder->id);
            $shortcuts = $this->content->getShortcuts($contentFolder);
            $isMovable = $content->where('is_movable', true)->count() ? true : false;
        } else {
            app('soda.interface')->breadcrumbs()->addLink(route('soda.content.index'), ucfirst(trans('soda::terminology.content_plural')));
            app('soda.interface')->setHeading("Search");
        }

        return soda_cms_view('data.content.index', compact('contentFolder', 'content', 'contentTypes', 'shortcuts','isMovable'));
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @param         $contentId
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function show(Request $request, $contentId)
    {
        $contentFolder = $this->content->findById($contentId);

        if (! $contentFolder) {
            return $this->handleError(trans('soda::errors.not-found', ['object' => trans('soda::terminology.content')]));
        } elseif (! $contentFolder->is_folder) {
            return redirect()->route('soda.content.edit', $contentFolder->getKey())->withInput();
        }

        $content = $this->content->listFolder($request, $contentFolder);

        if(!$request->has('search')) {
            $contentTypes = $this->content->getCreatableContentTypes($contentFolder->id);
            $shortcuts = $this->content->getShortcuts($contentFolder);
            $isMovable = $content->where('is_movable', true)->count() ? true : false;

            if($contentFolder->real_depth > 0) {
                $this->buildBreadcrumbTree($contentFolder);
                app('soda.interface')->setHeading($contentFolder->name);
            }
        } else {
            $this->buildBreadcrumbTree($contentFolder, true);
            app('soda.interface')->setHeading("Search");
        }

        return soda_cms_view('data.content.index', compact('contentFolder', 'content', 'contentTypes', 'shortcuts', 'isMovable'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param Request $request
     *
     * @return \Illuminate\View\Factory|\Illuminate\Contracts\View\View
     */
    public function create(Request $request)
    {
        try {
            $parentId = $request->input('parentId');
            $contentTypeId = $request->input('contentTypeId');
            $content = $this->content->createStub($parentId, $contentTypeId);

            if ($request->input('folder') == true) {
                $content->fillDefaults()->fill([
                    'name'           => $request->input('name'),
                    'is_sluggable'   => false,
                    'is_folder'      => true,
                    'is_publishable' => false,
                ])->save();

                if ($parentId) {
                    return redirect()->route('soda.content.show', $parentId)->with('success', trans('soda::messages.created', ['object' => trans('soda::terminology.content')]));
                }

                return redirect()->route('soda.content.index')->with('success', trans('soda::messages.created', ['object' => trans('soda::terminology.content')]));
            }
        } catch (Exception $e) {
            return $this->handleException($e, trans('soda::errors.create', ['object' => trans('soda::terminology.content')]));
        }

        if ($parentId) {
            $contentFolder = $this->content->findById($parentId);
            $this->buildBreadcrumbTree($contentFolder, $contentFolder->real_depth > 0);
        }

        if ($content->type) {
            app('soda.interface')->setHeading('New '.$content->type->name);
        } else {
            app('soda.interface')->setHeading('New '.ucfirst(trans('soda::terminology.content')));
        }

        return view($content->edit_action, compact('content'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, ['name' => 'required']);

        try {
            $content = $this->content->save($request);
        } catch (Exception $e) {
            return $this->handleException($e, trans('soda::errors.create', ['object' => trans('soda::terminology.content')]));
        }

        return redirect()->route('soda.content.edit', $content->getKey())->with('success', trans('soda::messages.created', ['object' => trans('soda::terminology.content')]));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     *
     * @return \Illuminate\View\Factory|\Illuminate\Contracts\View\View
     */
    public function edit($id)
    {
        $content = $this->content->findById($id);

        if (! $content) {
            return $this->handleError(trans('soda::errors.not-found', ['object' => trans('soda::terminology.content')]));
        }

        $content->load('blockTypes.fields', 'type.blockTypes.fields', 'type.fields');
        $blockTypes = $this->content->getAvailableBlockTypes($content);

        $this->buildBreadcrumbTree($content);
        app('soda.interface')->setHeading($content->name);

        return view($content->edit_action, compact('content', 'blockTypes'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int                      $id
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, ['name' => 'required']);

        try {
            $content = $this->content->save($request, $id);
        } catch (Exception $e) {
            return $this->handleException($e, trans('soda::errors.update', ['object' => trans('soda::terminology.content')]));
        }

        return redirect()->route('soda.content.edit', $content->getKey())->with('success', trans('soda::messages.updated', ['object' => trans('soda::terminology.content')]));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function destroy($id)
    {
        try {
            $this->content->destroy($id);
        } catch (Exception $e) {
            return $this->handleException($e, trans('soda::errors.delete', ['object' => trans('soda::terminology.content')]));
        }

        return redirect()->back()->with('info', trans('soda::messages.deleted', ['object' => trans('soda::terminology.content')]));
    }

    protected function buildBreadcrumbTree(ContentInterface $contentItem, $includeItem = false)
    {
        app('soda.interface')->breadcrumbs()->addLink(route('soda.content.index'), ucfirst(trans('soda::terminology.content_plural')));

        if ($parentFolder = $contentItem->getAncestorsTree()->first()) {
            do {
                if ($parentFolder->real_depth > 0) {
                    app('soda.interface')->breadcrumbs()->addLink(route('soda.content.show', $parentFolder->id), $parentFolder->name);
                }

                if ($parentFolder->relationLoaded('children') && isset($parentFolder->getRelation('children')[0])) {
                    $parentFolder = $parentFolder->getRelation('children')[0];
                } else {
                    $parentFolder = false;
                }
            } while ($parentFolder);
        }

        if ($includeItem) {
            app('soda.interface')->breadcrumbs()->addLink(route('soda.content.show', $contentItem->id), $contentItem->name);
        }
    }
}
