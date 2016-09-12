@if(!isset($item['display']) || $item['display'])
<li class="nav-item-group">
    <a class="nav-link {{ $item['current'] ? 'sub-active' : '' }}" data-toggle="collapse" data-parent=".sidebar" href="#{{ $item['id'] }}-nav">
        @if(isset($item['icon']) && $item['icon'])
            <i class="{{ $item['icon'] }}"></i>
        @endif
        <span>{{ $item['label'] }}</span>
    </a>
    <ul id="{{ $item['id'] }}-nav" class="collapse">
        @foreach($item['children'] as $item)
            @include(soda_cms_view_path('partials.menu.item', compact('item')))
        @endforeach
    </ul>
</li>
@endif
