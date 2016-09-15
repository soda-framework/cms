<?php

$item_attributes = $item->getAttributes();
$link_attributes = $item->getLinkAttributes();
$icon_attributes = $item->getIconAttributes();
$label_attributes = $item->getLabelAttributes();
$children_attributes = $item->getChildrenAttributes();

?>

<li @attr($item_attributes)>
    <a @attr($link_attributes)>
        @if($item->getIcon())
            <i @attr($icon_attributes)></i>
        @endif
        <span @attr($label_attributes)>{{ $item->getLabel() }}</span>
    </a>
    <ul @attr($children_attributes)>
        @foreach($item->getChildren() as $item)
            {!! $item->render() !!}
        @endforeach
    </ul>
</li>
