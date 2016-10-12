<ul class="tree-view">
    @foreach($tree as $tree_item)
        @include('soda::partials.tree.tree',['tree'=>$tree_item])
    @endforeach
</ul>

<script>
    var oldContainer;
    $("ul.tree-view").sortable({
        group: 'no-drop',
        handle: '.handle',
        afterMove: function (placeholder, container, closestItemOrContainer) {
            if (oldContainer != container) {
                if (oldContainer) {
                    oldContainer.el.removeClass("active");
                }
                container.el.addClass("active");

                oldContainer = container;
            }
        },
        onDrop: function ($item, container, _super) {
            var id = $item.data('id');
            var parent = $item.parent().closest('.tree-row');
            var parent_id = parent.data('id');
            var position = $item.index();

            if (typeof parent_id === 'undefined') {
                parent_id = 'root';
            }

            $.get($item.data('move'), {
                id: id,
                position: position,
                parent_id: parent_id
            }, function () {
                //move complete.
            });

            _super($item, container);

            $('.tree-sub-items:has(li)').closest('.tree-row').children('.tree-item').find('.minify').fadeIn();
            $('.tree-sub-items:not(:has(li))').closest('.tree-row').children('.tree-item').find('.minify').fadeOut();
        },
        serialize: function (parent, children, isContainer) {
            return isContainer ? children.join() : parent.text();
        }
    });

    $(".minify").on('click', function (e) {
        e.preventDefault();
        var container = $(this).closest('.tree-row').children('.tree-sub-items');
        $(this).toggleClass('fa-chevron-down fa-chevron-right');

        container.toggleClass('sub-items-expanded');

        if(container.hasClass('sub-items-expanded')) {
            container.children().show();
            container.hide();
        }

        container.slideToggle(function() {
            if(!$(this).hasClass('sub-items-expanded')) {
                $(this).children().hide();
            }

            $(this).show();
        });
    });
</script>
