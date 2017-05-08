<?php

namespace Soda\Cms\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Soda\Cms\Database\Repositories\Contracts\ContentRepositoryInterface;

class ContentController extends BaseController
{
    protected $contents;

    public function __construct(ContentRepositoryInterface $content)
    {
        $this->content = $content;

        app('soda.interface')->setHeading(ucfirst(trans('soda::terminology.content_plural')))->setHeadingIcon('mdi mdi-file-outline');
        app('soda.interface')->breadcrumbs()->addLink(route('soda.home'), ucfirst(trans('soda::terminology.home')));

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

        $contentTypes = $this->content->getCreatableContentTypes();

        return soda_cms_view('data.content.index', compact('contentFolder', 'content', 'contentTypes'));
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
        $contentTypes = $this->content->getCreatableContentTypes($contentFolder->id);

        return soda_cms_view('data.content.index', compact('contentFolder', 'content', 'contentTypes'));
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
        app('soda.interface')->setHeading('New '.ucfirst(trans('soda::terminology.content')));
        app('soda.interface')->breadcrumbs()->addLink(route('soda.content.index'), ucfirst(trans('soda::terminology.content_plural')));

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

                return redirect()->route('soda.content.index')->with('success', trans('soda::messages.created', ['object' => trans('soda::terminology.content')]));
            }
        } catch (Exception $e) {
            return $this->handleException($e, trans('soda::errors.create', ['object' => trans('soda::terminology.content')]));
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
        } elseif ($content->is_folder) {
            return redirect()->route('soda.content.show', $content->getKey())->withInput();
        }

        app('soda.interface')->setHeading($content->name);
        app('soda.interface')->breadcrumbs()->addLink(route('soda.content.index'), ucfirst(trans('soda::terminology.content_plural')));

        $content->load('blockTypes.fields', 'type.blockTypes.fields', 'type.fields');
        $blockTypes = $this->content->getAvailableBlockTypes($content);

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

        return redirect()->route('soda.content.index')->with('warning', trans('soda::messages.deleted', ['object' => trans('soda::terminology.content')]));
    }
}
