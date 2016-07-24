{{--renders tree in html --}}
<li data-id="{{$tree->id}}">
	<div class="tree-item">
		<i class="fa fa-bars handle"></i>
		<i class="fa fa-minus minify"></i>
		<a class='item-title' href="{{route('soda.pages.view', ['id'=>$tree->id])}}">{{$tree->name}}</a>
		<div class="btn-group pull-right" role="group" aria-label="Basic example">
			<a class='btn btn-success btn-sm' href="{{route('soda.pages.create', ['id'=>$tree->id])}}"><span class="fa fa-plus"></span></a>
			<a class='btn btn-info btn-sm' href="{{$tree->slug}}"><span class="fa fa-external-link"></span></a>
			<a class='btn btn-danger btn-sm' href="{{route('soda.pages.delete', ['id'=>$tree->id])}}"><span class="fa fa-remove"></span></a>
		</div>
		<div class="'clearfix" style="clear:both;"></div>
	</div>

	<ul>
		@if($tree->hasChildren())
			@foreach($tree->children as $child)
				@include('soda::tree.tree', ['tree'=>$child])
			@endforeach
		@endif
	</ul>
</li>