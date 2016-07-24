{{-- base tree item --}}
<link href="/sodacms/sodacms/css/tree.css" rel="stylesheet"> {{--should this be in head? --}}

<ul class="tree-view">

	@foreach($tree as $tree_item)
		@include('soda::tree.tree',['tree'=>$tree_item])
	@endforeach
</ul>

<script>
	var oldContainer;
	$("ul.tree-view").sortable({
		group: 'no-drop',
		handle: 'i.handle',
		afterMove: function (placeholder, container) {
			if(oldContainer != container){
				if(oldContainer)
					oldContainer.el.removeClass("active");
				container.el.addClass("active");

				oldContainer = container;
			}
		},
		onDrop: function ($item, container, _super) {
			var id = $item.data('id');
			var parent_id = $item.parent().parent().data('id');
			var position = $item.index();
			if(typeof parent_id === 'undefined'){
				parent_id = 'root';
			}
			$.get('/cms/pages/move/'+id+'/'+parent_id+'/'+position,function(){
				//complete.
			});
			console.log(_super);
			container.el.removeClass("active");
			_super($item, container);
		},
		serialize: function (parent, children, isContainer) {
			return isContainer ? children.join() : parent.text();
		}
	});

	$("ul.tree-view .minify").click(function(e){
		e.preventDefault();
		$('ul', $(this).closest('li')).toggle();
	});
</script>