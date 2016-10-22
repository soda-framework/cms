<ul class="page-tree">
    @foreach($tree as $treeItem)
        @include(soda_cms_view_path('data.pages.tree.branch'), ['page' => $treeItem])
    @endforeach
</ul>

<script>
    $(".page-tree").sortable({
        group: 'page-tree',
        handle: '.handle',
        isValidTarget: function  ($item, container) {
            return container.el.closest('.root-node').length ? true : false;
        },
        onDrop: function ($item, container, _super) {
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

            _super($item, container);

            $('.tree-sub-items:has(li)').closest('.tree-row:not(.root-node)').addClass('has-sub-items');
            $('.tree-sub-items:not(:has(li))').closest('.tree-row').removeClass('has-sub-items');

            showTreeSubnodes($item.closest('.tree-sub-items'));
            $('.tree-row:not(.has-sub-items):not(.root-node) > .tree-sub-items.sub-items-expanded').each(function(key, item) {
                hideTreeSubnodes($(item));
            });


        },
        serialize: function (parent, children, isContainer) {
            return isContainer ? children.join() : parent.text();
        }
    });

    $(".minify").on('click', function (e) {
        e.preventDefault();
        var row = $(this).closest('.tree-row');
        var container = row.children('.tree-sub-items');

        if(container.hasClass('sub-items-expanded')) {
            hideTreeSubnodes(container);
        } else {
            showTreeSubnodes(container);
        }
    });

    function hideTreeSubnodes(container) {
        container.closest('.tree-row').removeClass('row-expanded');
        if(container.hasClass('sub-items-expanded'))
        {
            container.removeClass('sub-items-expanded');

            container.slideUp('fast', function() {
                $(this).children().hide();
                $(this).show();
            });
        }
    }

    function showTreeSubnodes(container) {
        container.closest('.tree-row').addClass('row-expanded');
        if(!container.hasClass('sub-items-expanded'))
        {
            container.addClass('sub-items-expanded');

            container.children().show();
            container.hide();

            container.slideDown('fast', function() {
                $(this).show();
            });
        }
    }

    /**
     *
     * @param type string 'insertAfter' or 'insertBefore'
     * @param entityName
     * @param id
     * @param positionId
     */
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
