<?php
    $menu_attributes = $menu->getAttributes();
?>

<ul @attributes($menu_attributes)>
    @foreach($menu->getItems() as $item)
    {!! $item->render() !!}
    @endforeach
</ul>
