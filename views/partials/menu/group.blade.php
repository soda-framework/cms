<?php

$item_attributes = $item->getAttributes();
$link_attributes = $item->getLinkAttributes();
$icon_attributes = $item->getIconAttributes();
$label_attributes = $item->getLabelAttributes();
$children_attributes = $item->getChildrenAttributes();

?>

<li @attributes($item_attributes)>
    <a @attributes($link_attributes)>
        @if($item->getIcon())
            <i @attributes($icon_attributes)></i>
        @endif
        <span @attributes($label_attributes)>{{ $item->getLabel() }}</span>
    </a>
    <ul @attributes($children_attributes)>
        @foreach($item->getChildren() as $item)
            {!! $item->render() !!}
        @endforeach
    </ul>
</li>
