<?php

namespace Soda\Cms\Http\Controllers\Api;

use Illuminate\Http\Request;
use Soda\Cms\Database\Repositories\Contracts\ContentRepositoryInterface;

class ContentController extends ApiController
{
    protected $contents;

    public function __construct(ContentRepositoryInterface $content)
    {
        $this->content = $content;

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
    public function show(Request $request, $contentId = null)
    {
        $contentFolder = $this->content->findById($contentId);

        if (! $contentFolder) {
            return $this->respondNotFound(trans('soda::errors.not-found', ['object' => trans('soda::terminology.content')]));
        } elseif (! $contentFolder->is_folder) {
            return $this->respondNotAllowed(ucwords(trans('soda::terminology.content')).' is not a folder');
        }

        $content = $this->content->listFolder($request, $contentFolder);
        $contentTypes = $this->content->getCreatableContentTypes($contentFolder->id);

        return $this->respond(compact('contentFolder', 'content', 'contentTypes'));
    }

    public function get(Request $request, $contentId)
    {
        $contentItem = $this->content->findById($contentId);

        if (! $contentItem) {
            return $this->respondNotFound(trans('soda::errors.not-found', ['object' => trans('soda::terminology.content')]));
        }

        return $this->respond($contentItem);
    }
}
