@extends(soda_cms_view_path('wrappers.'.$render))

@section('wrapper-content')
	<p>{{ $block->type->description}}</p>
	<hr />
	@if(count($models))
	<div class="table-fixed fixed-options-column">
        <div class="table-fixed-inner">
            <table class="table">
                <thead>
                <tr>
                    @foreach($block->type->fields as $field)
                        @if($field->show_in_table)
                            <th>{{ $field->name }}</th>
                        @endif
                    @endforeach
                    <th class="fixed-column options-column">Options</th>
                </tr>
                </thead>
                <tbody>
                @foreach($models as $model)
                    <tr>
                        @foreach($block->type->fields as $field)
                            @if($field->show_in_table)
                                <td>
                                    {!! SodaForm::field($field)->setModel($model)->renderForTable() !!}
                                </td>
                            @endif
                        @endforeach
                        <td class="fixed-column options-column">
                            <a href="{{route('soda.page.block.view', ['page_id' => $page->id, 'id'=>$model->id, 'type'=>$block->identifier])}}" class="btn btn-success btn-sm"><i class="fa fa-pencil"></i></a>
                            @if($block->pivot->can_delete)
                                <a href="{{route('soda.page.block.delete', ['page_id' => $page->id, 'id'=>$model->id, 'type'=>$block->identifier])}}" class="btn btn-danger btn-sm"><i class="fa fa-remove"></i></a>
                            @endif
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
    {!! $models->render() !!}
    @else
    <p>No records found.</p>
    @endif

	@if($block->pivot->can_create)
	<a href="{{route('soda.page.block.view',['page_id' => $page->id, 'type' => $block->identifier])}}" class="btn btn-primary">Create</a>
	@endif
@overwrite
