<?php

$item_attributes = $item->getAttributes();
$link_attributes = $item->getLinkAttributes();
$icon_attributes = $item->getIconAttributes();
$label_attributes = $item->getLabelAttributes();

?>

<li @attr($item_attributes)>
    <a @attr($link_attributes)>
        @if($item->getIcon())
            <i @attr($icon_attributes)></i>
        @endif
        <span @attr($label_attributes)>{{ $item->getLabel() }}</span>
    </a>
</li>
