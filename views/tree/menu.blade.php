<ul class="menu">
	@foreach($tree as $tree_item)
		@include('soda::tree.menu_item',['tree'=>$tree_item])
	@endforeach
</ul>