<ul class="menu">
	@foreach($tree as $tree_item)
		@include(soda_cms_view_path('partials.tree.menu_item'),['tree'=>$tree_item])
	@endforeach
</ul>
