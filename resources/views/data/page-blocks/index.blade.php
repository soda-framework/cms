@if($block->type->description)
    <p>{{ $block->type->description }}</p>
    <hr />
@endif
@if(count($models))
<div class="table-responsive">
    <table class="table">
        <thead>
        <tr>
            @foreach($block->type->fields as $field)
                @if($field->pivot->show_in_table)
                    <th>{{ $field->name }}</th>
                @endif
            @endforeach
            <th width="80">Options</th>
        </tr>
        </thead>
        <tbody>
        @foreach($models as $model)
            <tr>
                @foreach($block->type->fields as $field)
                    @if($field->pivot->show_in_table)
                        <td>
                            {!! SodaForm::field($field)->setModel($model)->renderForTable() !!}
                        </td>
                    @endif
                @endforeach
                <td>
                    <a href="{{route('soda.pages.blocks.edit', ['page_id' => $page->id, 'id'=>$model->id, 'type'=>$block->identifier])}}" class="btn btn-success btn-sm"><i class="fa fa-pencil"></i></a>
                    @if($block->pivot->min_blocks === null || count($models) > $block->pivot->min_blocks)
                        <a href="{{route('soda.pages.blocks.destroy', ['page_id' => $page->id, 'id'=>$model->id, 'type'=>$block->identifier])}}" class="btn btn-danger btn-sm"><i class="fa fa-remove"></i></a>
                    @endif
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
{!! $models->render() !!}
@else
<p>No records found.</p>
@endif

@if($block->pivot->max_blocks === null || count($models) < $block->pivot->max_blocks)
    @include(soda_cms_view_path('partials.buttons.create'), ['url' => route('soda.pages.blocks.create', ['page_id' => $page->id, 'type' => $block->identifier])])
@endif
