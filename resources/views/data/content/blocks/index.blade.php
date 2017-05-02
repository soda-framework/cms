@if($blockType->description)
    <p>{{ $blockType->description }}</p>
    <hr />
@endif
@if(count($blocks))
<div class="table-responsive">
    <table class="table">
        <thead>
        <tr>
            @foreach($blockType->fields as $field)
                @if($field->pivot->show_in_table)
                    <th>{{ $field->name }}</th>
                @endif
            @endforeach
            <th width="192">Options</th>
        </tr>
        </thead>
        <tbody>
        @foreach($blocks as $block)
            <tr>
                @foreach($blockType->fields as $field)
                    @if($field->pivot->show_in_table)
                        <td>
                            {!! app('soda.form')->field($field)->setModel($block)->renderForTable() !!}
                        </td>
                    @endif
                @endforeach
                <td>
                    <a href="{{ route('soda.content.block-types.block.edit', [$content->id, $blockType->id, $block->id]) }}" class="btn btn-success">Edit</a>
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
