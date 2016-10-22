{{--renders tree in html --}}
<li class="tree-row {{ $page->hasChildrenRelation() && count($page->children) > 0 ? 'has-sub-items' : '' }} {{ $page->parent_id === null ? 'root-node' : '' }}" data-id="{{ $page->id }}" data-parentId="{{ $page->parent_id }}" style="display:{{ isset($display) ? $display : 'block' }}">
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
            <div style="display:inline-block;position:relative;">
                <a href="#" class="btn btn-info option-more" data-toggle="dropdown" aria-expanded="false">
                    <i class="fa fa-ellipsis-v"></i>
                </a>
                <ul class="dropdown-menu">
                    <li>
                        <a data-page-id="{{ $page->id }}" data-toggle="modal" data-target="#pageTypeModal">Create Sub-page</a>
                    </li>
                    <li>
                        <a href="{{ route('soda.pages.edit', $page->id) }}">Edit Page</a>
                    </li>
                    <li>
                        <a href="{{ $page->slug }}" target="_blank" data-tree-link>View page</a>
                    </li>
                    <li class="divider"></li>
                    <li class="warning">
                        <a data-delete-button href="{{ route('soda.pages.destroy', $page->id) }}">Delete</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <ul class="tree-sub-items {{ $page->parent_id === null ? 'sub-items-expanded' : '' }}">
        @if ($page->hasChildrenRelation() && count($page->children) > 0)
            @foreach($page->getRelation('children') as $child)
                @include(soda_cms_view_path('data.pages.tree.branch'), ['page' => $child, 'display' => $page->parent_id === null ? null : 'none'])
            @endforeach
        @endif
    </ul>
</li>
