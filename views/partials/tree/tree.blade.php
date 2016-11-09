{{--renders tree in html --}}
<li class="tree-row {{ $tree->hasChildrenRelation() && count($tree->children) > 0 ? 'has-sub-items' : '' }}" data-id="{{ $tree->id }}" data-move="{{route('soda.'.$hint.'.move')}}" style="display:{{ isset($display) ? $display : 'block' }}">
    <div class="tree-item clearfix">
        <span class="handle">
            <img src="/soda/cms/img/drag-dots.gif" />
        </span>
        <span class="item-status">
            <span class="{{ $tree->status == \Soda\Cms\Support\Constants::STATUS_DRAFT ? 'inactive' : 'active' }}-circle"></span>
        </span>
        <a class="item-title" href="{{ route('soda.'.$hint.'.view', ['id'=>$tree->id]) }}">
            <span>{{ $tree->name }}</span>
        </a>
        <span class="minify">
            <i class="fa fa-chevron-right"></i>
        </span>
        <div class="option-buttons pull-right">
            <div style="display:inline-block;position:relative;">
                <a href="#" class="btn btn-info option-more" data-toggle="dropdown" aria-expanded="false">
                    <i class="fa fa-ellipsis-v"></i>
                </a>
                <ul class="dropdown-menu">
                    @permission('create-pages')
                    <li>
                        <a data-tree-add href="{{ route('soda.'.$hint.'.create', ['id'=>$tree->id]) }}">Create Sub-page</a>
                    </li>
                    @endpermission
                    @permission('edit-pages')
                    <li>
                        <a href="{{ route('soda.'.$hint.'.view', ['id'=>$tree->id]) }}">Edit Page</a>
                    </li>
                    @endpermission
                    <li>
                        <a href="{{ $tree->slug }}" target="_blank" data-tree-link>View page</a>
                    </li>
                    @permission('delete-pages')
                    <li class="divider"></li>
                    <li class="warning">
                        <a data-tree-delete href="{{ route('soda.'.$hint.'.delete', ['id' => $tree->id]) }}">Delete</a>
                    </li><!--v-if-->
                    @endpermission
                </ul>
            </div>
        </div>
    </div>

    <ul class="tree-sub-items">
        @if ($tree->hasChildrenRelation() && count($tree->children) > 0)
            @foreach($tree->children as $child)
                @include(soda_cms_view_path('partials.tree.tree'), ['tree' => $child, 'display' => 'none'])
            @endforeach
        @endif
    </ul>
</li>
