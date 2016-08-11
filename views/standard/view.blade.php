<?php
$related = [];
if (isset($model)) {
    $related = [
            'related_table' => $model->getTable(),
            'related_id' => $model->id,
    ];
}
?>
@extends(config('soda.hint_path').'::layouts.inner')

@section('header')
    <title>View {{ucfirst($model->name)}}</title>
@endsection

@section('content')
    <h1>
        @if(!@$model->id)
            Create {{ucfirst($model->name)}}
        @else
            Update {{ucfirst($model->name)}}
        @endif
    </h1>

    <form method="POST" action='{{route('soda.dyn.edit',['type'=>@$type->identifier, 'id'=>@$model->id])}}'
          class="form-wrapper">
        <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
        {{--TODO: swap for @each?? --}}
        @foreach($type->fields as $field)
            @include("soda::inputs.".$field->field_type,['field_name'=>$field->field_name,
                'field_value'=>$model->{$field->field_name}, 'field_label'=>$field->name,
                'field_info'=>$field->description, 'field_parameters' => $field->field_params,
                'related'=>$related
            ])
        @endforeach
        <button type="submit" class="btn btn-primary">Save</button>
    </form>
@endsection