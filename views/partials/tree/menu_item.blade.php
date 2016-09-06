{{--renders tree in html --}}
<li data-id="{{$tree->id}}">
	<a class='item-title' href="{{$tree->url}}">{{$tree->text}}</a>

	@if (@$tree->subnodes && $tree->subnodes !== null && $tree->subnodes->count() > 0)
	<ul>
		@foreach($tree->subnodes as $child)
			@include('soda::partials.tree.menu_item', ['tree'=>$child])
		@endforeach
	</ul>
	@endif
</li>
