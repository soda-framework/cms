{{--renders tree in html --}}
<li id="page-tree-node-{{ $page->id }}" class="tree-row {{ $page->parent_id === null ? 'root-node' : '' }} {{ $page->hasChildrenRelation() && count($page->children) > 0 ? 'collapsed' : '' }}" data-id="{{ $page->id }}">
    <div class="tree-item clearfix">
        <span class="{{ $page->parent_id === null ? 'locked-handle' : 'handle' }}">
            <img src="/soda/cms/img/drag-dots.gif" />
        </span>
        <span class="item-status">
            <span class="{{ $page->status == \Soda\Cms\Support\Constants::STATUS_DRAFT ? 'inactive' : 'active' }}-circle"></span>
        </span>
        <a class="item-title" href="{{ route('soda.pages.edit', ['id' => $page->id]) }}">
            <span>{{ $page->name }}</span>
        </a>
        <span class="{{ $page->parent_id === null ? 'locked-minify' : 'minify' }}">
            <i class="fa fa-chevron-right"></i>
        </span>
        <div class="option-buttons pull-right">
            <div style="display:inline-block;">
                <a href="#" class="btn btn-info option-more" data-toggle="dropdown" aria-expanded="false">
                    <i class="fa fa-ellipsis-v"></i>
                </a>
                <div class="dropdown-menu">
                    @if($page->isAllowedChildren())
                    @permission('create-pages')
                    <div>
                        <a data-page-id="{{ $page->id }}" data-page-type-id="{{ $page->type_id }}" data-toggle="modal" data-target="#pageTypeModal">Create Sub-page</a>
                    </div>
                    @endpermission
                    @endif
                    @permission('edit-pages')
                    <div>
                        <a href="{{ route('soda.pages.edit', $page->id) }}">Edit Page</a>
                    </div>
                    @endpermission
                    <div>
                        <a href="{{ $page->slug }}" target="_blank" data-tree-link>View page</a>
                    </div>
                    @if($page->canDelete())
                    @permission('delete-pages')
                    <div class="divider"></div>
                    <div class="warning">
                        <a data-delete-button href="{{ route('soda.pages.destroy', $page->id) }}">Delete</a>
                    </div>
                    @endpermission
                    @endif
                </div>
            </div>
        </div>
    </div>

    @if ($page->hasChildrenRelation() && count($page->children) > 0)
    <ul>
        @foreach($page->getRelation('children') as $child)
            @include(soda_cms_view_path('partials.page-tree.branch'), ['page' => $child])
        @endforeach
    </ul>
    @endif
</li>
