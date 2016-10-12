{{--renders tree in html --}}
<li class="tree-row" data-id="{{ $tree->id }}" data-move="{{route('soda.'.$hint.'.move')}}" style="display:{{ isset($display) ? $display : 'block' }}">
    <div class="tree-item clearfix">
        <span class="handle">
            <img src="/soda/cms/img/drag-dots.gif" />
        </span>
        <span class="item-status">
            <span class="{{ $tree->status == \Soda\Cms\Components\Status::DRAFT ? 'inactive' : 'active' }}-circle"></span>
        </span>
        <a class="item-title" href="{{ route('soda.'.$hint.'.view', ['id'=>$tree->id]) }}">
            <span>{{ $tree->name }}</span>
        </a>
        <i class="fa fa-chevron-right minify" style="display:{{ $tree->hasChildrenRelation() && count($tree->children) > 0 ? 'inline' : 'none' }}"></i>
        <div class="btn-group pull-right" role="group" aria-label="Basic example">
            <a data-tree-add class="btn btn-success btn-sm" href="{{ route('soda.'.$hint.'.create', ['id'=>$tree->id]) }}">
                <span class="fa fa-plus"></span>
            </a>
            <a data-tree-link class="btn btn-info btn-sm" href="{{ $tree->slug }}">
                <span class="fa fa-external-link"></span>
            </a>
            <a data-tree-delete class="btn btn-danger btn-sm" href="{{ route('soda.'.$hint.'.delete', ['id' => $tree->id]) }}">
               <span class="fa fa-remove"></span>
            </a>
        </div>
    </div>

    <ul class="tree-sub-items">
        @if ($tree->hasChildrenRelation() && count($tree->children) > 0)
            @foreach($tree->children as $child)
                @include('soda::partials.tree.tree', ['tree' => $child, 'display' => 'none'])
            @endforeach
        @endif
    </ul>
</li>
