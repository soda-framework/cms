<?php

$item_attributes = $item->getAttributes();
$link_attributes = $item->getLinkAttributes();
$icon_attributes = $item->getIconAttributes();
$label_attributes = $item->getLabelAttributes();
$badgeAttributes = $item->getBadgeAttributes();
$badgeAttributes['class'] = isset($badgeAttributes['class']) ? $badgeAttributes : 'badge badge-info';

?>

<li @attr($item_attributes)>
    <a @attr($link_attributes)>
        @if($item->getIcon())
            <i @attr($icon_attributes)></i>
        @endif
        <span @attr($label_attributes)>{{ $item->getLabel() }}</span> @if($badge = $item->getBadge())
            <span @attr($badgeAttributes)>{{ $badge }}</span>@endif
    </a>
</li>
