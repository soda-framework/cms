@if($blockType->description)
    <p>{{ $blockType->description }}</p>
    <hr />
@endif

@if(count($blocks))
<div class="table-responsive">
    <table class="table">
        <thead>
        <tr>
            @if($blockType->pivot->is_orderable && count($blocks) > 1)
                <th width="20"></th>
            @endif
            @foreach($blockType->fields as $field)
                @if($field->pivot->show_in_table)
                    <th>{{ $field->name }}</th>
                @endif
            @endforeach
            <th width="233">Options</th>
        </tr>
        </thead>
        <tbody class="sortable" data-entityname="dynamic-block" data-entityidentifier="{{ $blockType->identifier }}">
        @foreach($blocks as $block)
            <tr data-itemId="{{ $block->id }}" data-parentId="{{ $block->content_id }}">
                @if($blockType->pivot->is_orderable && count($blocks) > 1)
                    <td class="sortable-handle"><img src="/soda/cms/img/drag-dots.gif" /></td>
                @endif
                @foreach($blockType->fields as $field)
                    @if($field->pivot->show_in_table)
                        <td>
                            {!! app('soda.form')->field($field)->setModel($block)->renderForTable() !!}
                        </td>
                    @endif
                @endforeach
                <td>
                    <a href="{{ route('soda.content.block-types.block.edit', [$content->id, $blockType->id, $block->id]) }}" class="btn btn-warning">Edit</a>
                    @if($blockType->pivot->min_blocks === null || count($blocks) > $blockType->pivot->min_blocks)
                        <a data-delete-button href="{{ route('soda.content.block-types.block.destroy', [$content->id, $blockType->id, $block->id]) }}" class="btn btn-danger">Delete</a>
                    @endif
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
{!! $blocks->render() !!}
@else
<p>No records found.</p>
@endif

@if($blockType->pivot->max_blocks === null || count($blocks) < $blockType->pivot->max_blocks)
    @include(soda_cms_view_path('partials.buttons.create'), ['url' => route('soda.content.block-types.block.create', [$content->id, $blockType->id])])
@endif

@permission('detach-blocks')
    <a data-delete-button class="btn btn-warning btn-lg" href="{{ route('soda.content.blocks.detach', [$content->id, $blockType->id]) }}">
        <span>Detach</span>
    </a>
@endpermission

<script src="/soda/cms/js/forms/sortable.js"></script>
<script>
    $(function() {
        $('.sortable').sortable({
            handle: '.sortable-handle',
            axis: 'y',
            update: function(a, b){

                var $sorted = b.item;

                var $previous = $sorted.prev();
                var $next = $sorted.next();

                var data = {
                    id: $sorted.data('itemid'),
                    parentId: $sorted.data('parentid'),
                    entityName: $(this).data('entityname'),
                    entityIdentifier: $(this).data('entityidentifier'),
                };

                if ($previous.length > 0) {
                    data.type = 'moveAfter';
                    data.positionEntityId = $previous.data('itemid');

                    console.log(data);
                    Soda.changePosition(data);
                } else if ($next.length > 0) {
                    data.type = 'moveBefore';
                    data.positionEntityId = $next.data('itemid');

                    console.log(data);
                    Soda.changePosition(data);
                } else {
                    console.error('Something wrong!');
                }
            },
            cursor: "move"
        });
    })
</script>
