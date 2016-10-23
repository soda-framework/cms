<ul class="page-tree" id="page-tree">
    @foreach($tree as $treeItem)
        @include(soda_cms_view_path('data.pages.tree.branch'), ['page' => $treeItem])
    @endforeach
</ul>
@section('footer.js')
    @parent
    <script src="/soda/cms/js/forms/sortable.min.js"></script>
    <script>
        $(function(){
            $('#page-tree').nestedSortable({
                handle: '> .tree-item > .handle',
                items: 'li',
                listType: 'ul',
                toleranceElement: '> .tree-item',
                isTree: true,
                protectRoot: true,
                startCollapsed: true,
                expandOnHover: 300,
                tabSize: 75,
                branchClass: 'has-sub-items',
                collapsedClass: 'collapsed',
                disableNestingClass: false,
                errorClass: false,
                expandedClass: 'expanded',
                hoveringClass: false,
                placeholder: 'tree-item hovering',
                leafClass: false,
                disabledClass: false,
                relocate: function(event, item) {
                    moveNode(item.item);
                },
            });

            $(".minify").on('click', function (e) {
                e.preventDefault();

                $(this).closest('.tree-row').toggleClass('expanded collapsed');
            });
        });

        function moveNode($item) {
            var itemId = $item.data('id');
            var parent = $item.parent().closest('.tree-row');
            var parentId = parent.data('id');

            if (typeof parentId !== 'undefined') {

                var $previous = $item.prev();
                var $next = $item.next();

                var data = {
                    entityName: 'pages',
                    id: itemId
                }

                if ($previous.length > 0) {
                    data.type = 'moveAfter';
                    data.positionEntityId = $previous.data('id');
                    changePosition(data);
                } else if ($next.length > 0) {
                    data.type = 'moveBefore';
                    data.positionEntityId = $next.data('id');
                    changePosition(data);
                } else {
                    data.type = 'moveInto';
                    data.positionEntityId = parentId;
                    changePosition(data);
                }

                $item.data('parent-id')
            }
        }

        var changePosition = function(requestData){
            $.ajax({
                'url': '{{ route('soda.sort') }}',
                'type': 'POST',
                'data': requestData,
                'success': function(data) {
                    if (data.errors) {
                        console.error(data.errors);
                    }
                },
                'error': function(){
                    console.error('Something wrong!');
                }
            });
        };
    </script>
@stop
