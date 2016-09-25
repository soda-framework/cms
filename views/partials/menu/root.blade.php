<?php
    $menu_attributes = $menu->getAttributes();
?>

<ul @attr($menu_attributes)>
    @foreach($menu->getItems() as $item)
    {!! $item->render() !!}
    @endforeach
</ul>
