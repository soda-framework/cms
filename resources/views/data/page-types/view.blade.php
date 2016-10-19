@extends(soda_cms_view_path('layouts.inner'))

@section('breadcrumb')
    <ol class="breadcrumb">
        <li><a href="{{ route('soda.home') }}">Home</a></li>
        <li><a href="{{ route('soda.page-type') }}">Page Types</a></li>
        <li class="active">{{ $model->name ? $model->name : 'New Page Type' }}</li>
    </ol>
@stop

@section('head.cms')
    <title>Soda CMS | Page Types</title>
@endsection

@section('content-heading-button')
    @include(soda_cms_view_path('partials.buttons.save'), ['submits' => '#page-type-form'])
@stop

@include(soda_cms_view_path('partials.heading'), [
    'icon'        => 'fa fa-edit',
    'title'       => $model->name ? 'Page Type: ' . $model->name : 'New Page Type',
])

@section('content')
    <div class="content-block">
        <form method="POST" id="page-type-form" action='{{route('soda.'.$hint.'.edit',['id' => $model->id])}}' enctype="multipart/form-data">
            <input type="hidden" name="_token" value="{{ csrf_token() }}"/>

            {!! SodaForm::text([
                "name"        => 'Page Type Name',
                "field_name"  => 'name',
            ])->setModel($model) !!}

            {!! SodaForm::toggle([
                'name'         => 'Status',
                'description'  => 'Determines whether pages of this type are visible on the live website',
                'field_name'   => 'status',
                'value'        => Soda\Cms\Support\Constants::STATUS_LIVE,
                'field_params' => ['checked-value' => Soda\Cms\Support\Constants::STATUS_LIVE, 'unchecked-value' => Soda\Cms\Support\Constants::STATUS_DRAFT],
            ])->setModel($model) !!}

            {!! SodaForm::textarea([
                "name"        => "Page Type Description",
                "field_name"  => 'description',
            ])->setModel($model) !!}

            {!! SodaForm::text([
                'name'        => 'Package Prefix',
                'field_name'  => 'package',
            ])->setModel($model) !!}

            <div class="row fieldset-group">
                <div class="col-sm-6 col-xs-12">
                    {!! SodaForm::dropdown([
                        'name'        => 'Default Action',
                        'field_name'  => 'action_type',
                        'value'       => 'view',
                        'field_params' => ['options' => Soda::getPageBuilder()->getActionTypes()],
                    ])->setModel($model)->setLayout(soda_cms_view_path('partials.inputs.layouts.inline-group')) !!}
                </div>
                <div class="col-sm-6 col-xs-12">
                    {!! SodaForm::text([
                        'name'        => null,
                        'field_name'  => 'action',
                    ])->setModel($model)->setLayout(soda_cms_view_path('partials.inputs.layouts.inline-group')) !!}
                </div>
            </div>

            {!! SodaForm::text([
                "name"        => "Identifier",
                "field_name"  => 'identifier',
            ])->setModel($model) !!}
        </form>
    </div>

    <div class="content-bottom">
        @include(soda_cms_view_path('partials.buttons.save'), ['submits' => '#page-type-form'])
    </div>
@endsection
