@extends(soda_cms_view_path('layouts.inner'))

@section('breadcrumb')
    <ol class="breadcrumb">
        <li><a href="{{ route('soda.home') }}">Home</a></li>
        <li><a href="{{ route('soda.block-type') }}">Block Types</a></li>
        <li class="active">{{ $model->name ? $model->name : 'New Block Type' }}</li>
    </ol>
@stop

@section('head.title')
    <title>Soda CMS | Edit Block Type</title>
@endsection

@section('content-heading-button')
    @include(soda_cms_view_path('partials.buttons.save'), ['submits' => '#block-type-form'])
@stop

@include(soda_cms_view_path('partials.heading'), [
    'icon'        => 'fa fa-square',
    'title'       => $model->name ? 'Block Type: ' . $model->name : 'New Block Type',
])

@section('content')
    <div class="content-block">
        <form id="block-type-form" method="POST" action='{{ route('soda.'.$hint.'.edit',['id'=>@$model->id]) }}' enctype="multipart/form-data">
            <input type="hidden" name="_token" value="{{ csrf_token() }}"/>

            {!! SodaForm::text([
                'name'        => 'Block Type Name',
                'field_name'  => 'name',
            ])->setModel($model)->render() !!}

            {!! SodaForm::textarea([
                'name'        => 'Block Type Description',
                'field_name'  => 'description',
            ])->setModel($model) !!}

            <div class="row fieldset-group">
                <div class="col-sm-6 col-xs-12">
                    {!! SodaForm::dropdown([
                        'name'        => 'List Action',
                        'field_name'  => 'list_action_type',
                        'field_params' => ['options' => app('soda.request-matcher')->getActionTypes()],
                        'description'  => 'Specifies the interface supplied when listing this block.',
                    ])->setModel($block)->setLayout(soda_cms_view_path('partials.inputs.layouts.inline-group')) !!}
                </div>
                <div class="col-sm-6 col-xs-12">
                    {!! SodaForm::text([
                        'name'        => null,
                        'field_name'  => 'list_action',
                    ])->setModel($block)->setLayout(soda_cms_view_path('partials.inputs.layouts.inline-group')) !!}
                </div>
            </div>

            <div class="row fieldset-group">
                <div class="col-sm-6 col-xs-12">
                    {!! SodaForm::dropdown([
                        'name'        => 'Edit Action',
                        'field_name'  => 'edit_action_type',
                        'field_params' => ['options' => app('soda.request-matcher')->getActionTypes()],
                        'description'  => 'Specifies the interface supplied when editing this block.',

                    ])->setModel($block)->setLayout(soda_cms_view_path('partials.inputs.layouts.inline-group')) !!}
                </div>
                <div class="col-sm-6 col-xs-12">
                    {!! SodaForm::text([
                        'name'        => null,
                        'field_name'  => 'edit_action',
                    ])->setModel($block)->setLayout(soda_cms_view_path('partials.inputs.layouts.inline-group')) !!}
                </div>
            </div>

            {!! SodaForm::text([
                'name'        => 'Identifier',
                'field_name'  => 'identifier',
            ])->setModel($model) !!}
        </form>
    </div>

    <div class="content-bottom">
        @include(soda_cms_view_path('partials.buttons.save'), ['submits' => '#block-type-form'])
    </div>
@endsection
