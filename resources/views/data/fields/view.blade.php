@extends(soda_cms_view_path('layouts.inner'))

@section('breadcrumb')
    <ol class="breadcrumb">
        <li><a href="{{ route('soda.home') }}">Home</a></li>
        <li><a href="{{ route('soda.fields.index') }}">Fields</a></li>
        <li class="active">{{ $field->name ? $field->name : 'New Field' }}</li>
    </ol>
@stop

@section('head.title')
    <title>Edit Field</title>
@endsection

@section('content-heading-button')
    @include(soda_cms_view_path('partials.buttons.save'), ['submits' => '#field-form'])
@stop

@include(soda_cms_view_path('partials.heading'), [
    'icon'        => 'fa fa-pencil',
    'title'       => $field->name? ' Field: ' . $field->name : 'New Field',
])

@section('content')
    <div class="content-block">
        <form method="POST" id="field-form" action="{{ route('soda.fields.' . ($field->id ? 'update' : 'store'), $field->id) }}" enctype="multipart/form-data">
            {!! csrf_field() !!}
            {!! method_field($field->id ? 'PUT' : 'POST') !!}

            {!! SodaForm::text([
                'name'        => 'Field Label',
                'field_name'  => 'name',
                'description' => 'Label of the field, visible when entering forms'
            ])->setModel($field) !!}

            {!! SodaForm::dropdown([
                'name'         => 'Field Type',
                'field_name'   => 'field_type',
                'field_params' => ['options' => $fieldTypes],
                'description'  => 'Type of field to be used'
            ])->setModel($field) !!}

            {!! SodaForm::text([
                'name'         => 'Field Default Value',
                'field_name'   => 'value',
                'description'  => 'Default value for field'
            ])->setModel($field) !!}

            {!! SodaForm::text([
                'name'        => 'Field Name',
                'field_name'  => 'field_name',
                'description' => 'Name of the field, used when accessing models'
            ])->setModel($field) !!}

            {!! SodaForm::textarea([
                'name'        => 'Field Description',
                'field_name'  => 'description',
                'description'  => 'Informative text to guide users when inputting this field'
            ])->setModel($field) !!}

            {!! SodaForm::json([
                'name'        => 'Field Parameters',
                'field_name'  => 'field_params',
                'description'  => 'Parameters for the field'
            ])->setModel($field) !!}
        </form>
    </div>

    <div class="content-bottom">
        @include(soda_cms_view_path('partials.buttons.save'), ['submits' => '#field-form'])
    </div>
@endsection
