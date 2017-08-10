<?php

$item_attributes = $item->getAttributes();
$link_attributes = $item->getLinkAttributes();
$icon_attributes = $item->getIconAttributes();
$label_attributes = $item->getLabelAttributes();
$children_attributes = $item->getChildrenAttributes();
$badgeAttributes = $item->getBadgeAttributes();

$childActive = isset($children_attributes['class']) && $children_attributes['class'] == 'collapse in';
$badgeAttributes['class'] = isset($badgeAttributes['class']) ? $badgeAttributes : 'badge badge-info';

?>

<li @attr($item_attributes)>
    <a @attr($link_attributes)>
        @if($item->getIcon())
            <i @attr($icon_attributes)></i>
        @endif
        <span @attr($label_attributes)>{{ $item->getLabel() }}</span> @if($badge = $item->getBadge())<span @attr($badgeAttributes)>{{ $badge }}</span>@endif
        <i class="nav-dropdown-indicator fa fa-chevron-right {{ $childActive ? 'active' : '' }}"></i>
    </a>
    <div @attr($children_attributes)>
        <ul>
            @foreach($item->getChildren() as $item)
                {!! $item->render() !!}
            @endforeach
        </ul>
    </div>
</li>
