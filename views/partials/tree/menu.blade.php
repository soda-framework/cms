<ul class="menu">
	@foreach($tree as $tree_item)
		@include('soda::partials.tree.menu_item',['tree'=>$tree_item])
	@endforeach
</ul>
