@extends(soda_cms_view_path('layouts.inner'))

@section('breadcrumb')
    <ol class="breadcrumb">
        <li><a href="{{ route('soda.home') }}">Home</a></li>
        <li><a href="{{ route('soda.field') }}">Fields</a></li>
        <li class="active">{{ $model->name ? $model->name : 'New Field' }}</li>
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
    'title'       => $model->name? ' Field: ' . $model->name : 'New Field',
])

@section('content')
    <div class="content-block">
        <form id="field-form" method="POST" action='{{route('soda.'.$hint.'.edit',['id'=>@$model->id])}}' enctype="multipart/form-data">
            <input type="hidden" name="_token" value="{{ csrf_token() }}"/>

            {!! SodaForm::text([
                'name'        => 'Field Label',
                'field_name'  => 'name',
                'description' => 'Label of the field, visible when entering forms'
            ])->setModel($model) !!}

            {!! SodaForm::dropdown([
                'name'         => 'Field Type',
                'field_name'   => 'field_type',
                'field_params' => ['options' => Soda::getFormBuilder()->getFieldTypes()],
                'description'  => 'Type of field to be used'
            ])->setModel($model) !!}

            {!! SodaForm::text([
                'name'         => 'Field Default Value',
                'field_name'   => 'value',
                'description'  => 'Default value for field'
            ])->setModel($model) !!}

            {!! SodaForm::text([
                'name'        => 'Field Name',
                'field_name'  => 'field_name',
                'description' => 'Name of the field, used when accessing models'
            ])->setModel($model) !!}

            {!! SodaForm::textarea([
                'name'        => 'Field Description',
                'field_name'  => 'description',
                'description'  => 'Informative text to guide users when inputting this field'
            ])->setModel($model) !!}

            {!! SodaForm::json([
                'name'        => 'Field Parameters',
                'field_name'  => 'field_params',
                'description'  => 'Parameters for the field'
            ])->setModel($model) !!}

            {!! SodaForm::toggle([
                'name'        => 'Show in table',
                'field_name'  => 'show_in_table',
                'description' => 'Determines whether column for this field should be shown in CMS table-view',
            ])->setModel($model) !!}
        </form>
    </div>

    <div class="content-bottom">
        @include(soda_cms_view_path('partials.buttons.save'), ['submits' => '#field-form'])
    </div>
@endsection
