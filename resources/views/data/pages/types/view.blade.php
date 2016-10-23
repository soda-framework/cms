@extends(soda_cms_view_path('layouts.inner'))

@section('breadcrumb')
    <ol class="breadcrumb">
        <li><a href="{{ route('soda.home') }}">Home</a></li>
        <li><a href="{{ route('soda.page-types.index') }}">Page Types</a></li>
        <li class="active">{{ $pageType->name ? $pageType->name : 'New Page Type' }}</li>
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
    'title'       => $pageType->name ? 'Page Type: ' . $pageType->name : 'New Page Type',
])

@section('content')
    <div class="content-block">
        <form method="POST" id="page-type-form" action="{{ route('soda.page-types.' . ($pageType->id ? 'update' : 'store'), $pageType->id) }}">
            {!! csrf_field() !!}
            {!! method_field($pageType->id ? 'PUT' : 'POST') !!}

            {!! SodaForm::text([
                "name"        => 'Page Type Name',
                "field_name"  => 'name',
            ])->setModel($pageType) !!}

            {!! SodaForm::toggle([
                'name'         => 'Status',
                'description'  => 'Determines whether pages of this type are visible on the live website',
                'field_name'   => 'status',
                'value'        => Soda\Cms\Support\Constants::STATUS_LIVE,
                'field_params' => ['checked-value' => Soda\Cms\Support\Constants::STATUS_LIVE, 'unchecked-value' => Soda\Cms\Support\Constants::STATUS_DRAFT],
            ])->setModel($pageType) !!}

            {!! SodaForm::textarea([
                "name"        => "Page Type Description",
                "field_name"  => 'description',
            ])->setModel($pageType) !!}

            <div class="row fieldset-group">
                <div class="col-sm-6 col-xs-12">
                    {!! SodaForm::dropdown([
                        'name'        => 'View Action',
                        'field_name'  => 'view_action_type',
                        'field_params' => ['options' => app('soda.request-matcher')->getActionTypes()],
                        'description'  => 'Specifies the interface supplied when viewing this page.',
                    ])->setModel($pageType)->setLayout(soda_cms_view_path('partials.inputs.layouts.inline-group')) !!}
                </div>
                <div class="col-sm-6 col-xs-12">
                    {!! SodaForm::text([
                        'name'        => null,
                        'field_name'  => 'view_action',
                    ])->setModel($pageType)->setLayout(soda_cms_view_path('partials.inputs.layouts.inline-group')) !!}
                </div>
            </div>

            <div class="row fieldset-group">
                <div class="col-sm-6 col-xs-12">
                    {!! SodaForm::dropdown([
                        'name'        => 'Edit Action',
                        'field_name'  => 'edit_action_type',
                        'field_params' => ['options' => app('soda.request-matcher')->getActionTypes()],
                        'description'  => 'Specifies the interface supplied when editing this page.',

                    ])->setModel($pageType)->setLayout(soda_cms_view_path('partials.inputs.layouts.inline-group')) !!}
                </div>
                <div class="col-sm-6 col-xs-12">
                    {!! SodaForm::text([
                        'name'        => null,
                        'field_name'  => 'edit_action',
                    ])->setModel($pageType)->setLayout(soda_cms_view_path('partials.inputs.layouts.inline-group')) !!}
                </div>
            </div>

            {!! SodaForm::text([
                "name"        => "Identifier",
                "field_name"  => 'identifier',
            ])->setModel($pageType) !!}
        </form>
    </div>

    <div class="content-bottom">
        @include(soda_cms_view_path('partials.buttons.save'), ['submits' => '#page-type-form'])
    </div>
@endsection
