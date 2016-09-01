{{-- base tree item --}}
<link href="/soda/cms/css/tree.min.css" rel="stylesheet"> {{--should this be in head? --}}

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
			$.get($item.data('move'), {
						id:id,
						position:position,
						parent_id:parent_id
					},function(){
				//move complete.
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
		$(this).toggleClass('fa-minus').toggleClass('fa-plus');
		$('ul', $(this).closest('li')).toggle();
	});
</script>
