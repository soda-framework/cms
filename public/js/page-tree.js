(function (Soda) {
    var elements = {
        tree: '#page-tree',
        row: '.tree-row',
        toleranceElement: '> .tree-item',
        handle: '> .tree-item > .handle',
        minify: '.minify',
    }

    var classes = {
        branchClass: 'has-sub-items',
        collapsedClass: 'collapsed',
        disableNestingClass: false,
        errorClass: false,
        expandedClass: 'expanded',
        hoveringClass: false,
        placeholder: 'tree-item hovering',
        leafClass: false,
        disabledClass: false,
    }

    var _registerEvents = function () {
        var parameters = $.extend({}, {
            handle: elements.handle,
            items: 'li',
            listType: 'ul',
            toleranceElement: '> .tree-item',
            isTree: true,
            protectRoot: true,
            startCollapsed: true,
            expandOnHover: 300,
            tabSize: 75,
            relocate: function(event, item) {
                _moveNode(item.item);
            },
        }, classes);

        $(elements.tree).nestedSortable(parameters);

        $(elements.minify).on('click', function (e) {
            e.preventDefault();

            $(this).closest(elements.row).toggleClass(classes.collapsedClass).toggleClass(classes.expandedClass);
        });
    }

    var _moveNode = function($item) {
        var itemId = $item.data('id');
        var parent = $item.parent().closest(elements.row);
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
                Soda.changePosition(data);
            } else if ($next.length > 0) {
                data.type = 'moveBefore';
                data.positionEntityId = $next.data('id');
                Soda.changePosition(data);
            } else {
                data.type = 'moveInto';
                data.positionEntityId = parentId;
                Soda.changePosition(data);
            }

            $item.data('parent-id')
        }
    }

    $(function(){
        _registerEvents();
    })

    Soda.pageTree = {
        elements: elements,
        classes: classes,
    };

    return Soda;

})(Soda || {});

//# sourceMappingURL=page-tree.js.map
