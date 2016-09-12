@if(!isset($item['display']) || $item['display'])
<li class="nav-item">
    <a class="nav-link {{ $item['current'] ? 'active' : ''}}" href="{{ $item['uri'] }}">
        @if(isset($item['icon']) && $item['icon'])
            <i class="{{ $item['icon'] }}"></i>
        @endif
        <span>{{ $item['label'] }}</span>
    </a>
</li>
@endif
