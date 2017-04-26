@extends(soda_cms_view_path('layouts.inner'))

@section('content-heading-button')
    @include(soda_cms_view_path('partials.buttons.save'), ['submits' => '#field-form'])
@stop

@section('content')
    <div class="content-block">
        <form method="POST" id="field-form" action="{{ route('soda.fields.' . ($field->id ? 'update' : 'store'), $field->id) }}" enctype="multipart/form-data">
            {!! csrf_field() !!}
            {!! method_field($field->id ? 'PUT' : 'POST') !!}

            {!! app('soda.form')->text([
                'name'        => 'Field Label',
                'field_name'  => 'name',
                'description' => 'Label of the field, visible when entering forms'
            ])->setModel($field) !!}

            {!! app('soda.form')->dropdown([
                'name'         => 'Field Type',
                'field_name'   => 'field_type',
                'field_params' => ['options' => $fieldTypes],
                'description'  => 'Type of field to be used'
            ])->setModel($field) !!}

            {!! app('soda.form')->text([
                'name'         => 'Field Default Value',
                'field_name'   => 'value',
                'description'  => 'Default value for field'
            ])->setModel($field) !!}

            {!! app('soda.form')->text([
                'name'        => 'Field Name',
                'field_name'  => 'field_name',
                'description' => 'Name of the field, used when accessing models'
            ])->setModel($field) !!}

            {!! app('soda.form')->textarea([
                'name'        => 'Field Description',
                'field_name'  => 'description',
                'description'  => 'Informative text to guide users when inputting this field'
            ])->setModel($field) !!}

            {!! app('soda.form')->json([
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
