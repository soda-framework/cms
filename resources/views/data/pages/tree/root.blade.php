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
            if(!container.el.closest('.root-node').length) {
                return false;
            }

            return true;
        },
        onDrop: function ($item, container, _super) {
            var id = $item.data('id');
            var parent = $item.parent().closest('.tree-row');
            var parent_id = parent.data('id');
            var position = $item.index();

            if (typeof parent_id !== 'undefined') {
                $.get($item.data('move'), {
                    id: id,
                    position: position,
                    parent_id: parent_id
                }, function () {
                    //move complete.
                });
            }

            _super($item, container);

            $('.tree-sub-items:has(li)').closest('.tree-row:not(.root-node)').addClass('has-sub-items');
            $('.tree-sub-items:not(:has(li))').closest('.tree-row').removeClass('has-sub-items');
        },
        serialize: function (parent, children, isContainer) {
            return isContainer ? children.join() : parent.text();
        }
    });

    $(".minify").on('click', function (e) {
        e.preventDefault();
        var row = $(this).closest('.tree-row');
        var container = row.children('.tree-sub-items');
        row.toggleClass('row-expanded');

        container.toggleClass('sub-items-expanded');

        if(container.hasClass('sub-items-expanded')) {
            container.children().show();
            container.hide();
        }

        container.slideToggle('fast', function() {
            if(!$(this).hasClass('sub-items-expanded')) {
                $(this).children().hide();
            }

            $(this).show();
        });
    });
</script>
