@extends(soda_cms_view_path('layouts.inner'))

@section('breadcrumb')
    <ol class="breadcrumb">
        <li><a href="{{ route('soda.home') }}">Home</a></li>
        <li><a href="{{ route('soda.page_type') }}">Page Types</a></li>
        <li class="active">{{ $model->name ? $model->name : 'New Page Type' }}</li>
    </ol>
@stop

@section('head.cms')
    <title>Soda CMS | Page Types</title>
@endsection

@include(soda_cms_view_path('partials.heading'), [
    'icon'        => 'fa fa-edit',
    'title'       => $model->name ? 'Page Type: ' . $model->name : 'New Page Type',
])

@section('content')
    <form method="POST" action='{{route('soda.'.$hint.'.edit',['id' => $model->id])}}' class="form--wrapper"
          enctype="multipart/form-data">
        <input type="hidden" name="_token" value="{{ csrf_token() }}"/>

        {!! SodaForm::text([
            "name"        => 'Page Type Name',
            "field_name"  => 'name',
        ])->setModel($model) !!}

        {!! SodaForm::toggle([
            'name'         => 'Status',
            'description'  => 'Determines whether pages of this type are visible on the live website',
            'field_name'   => 'status',
            'value'        => Soda\Cms\Components\Status::LIVE,
            'field_params' => ['checked-value' => Soda\Cms\Components\Status::LIVE, 'unchecked-value' => Soda\Cms\Components\Status::DRAFT],
        ])->setModel($model) !!}

        {!! SodaForm::textarea([
            "name"        => "Page Type Description",
            "field_name"  => 'description',
        ])->setModel($model) !!}

        {!! SodaForm::text([
            'name'        => 'Package Prefix',
            'field_name'  => 'package',
        ])->setModel($model) !!}

        <div class="row">
            <div class="col-sm-6 col-xs-12">
                {!! SodaForm::dropdown([
                    'name'        => 'Default Action',
                    'field_name'  => 'action_type',
                    'value'       => 'view',
                    'field_params' => ['options' => Soda::getPageBuilder()->getActionTypes()],
                ])->setModel($model)->setLayout('soda::partials.inputs.layouts.inline-group') !!}
            </div>
            <div class="col-sm-6 col-xs-12">
                {!! SodaForm::text([
                    'name'        => null,
                    'field_name'  => 'action',
                ])->setModel($model)->setLayout('soda::partials.inputs.layouts.inline-group') !!}
            </div>
        </div>

        {!! SodaForm::text([
            "name"        => "Identifier",
            "field_name"  => 'identifier',
        ])->setModel($model) !!}

        @if($model->name)
            <button class="btn btn-primary">
                <i class="fa fa-pencil"></i>
                <span>Update</span>
            </button>
        @else
            <button class="btn btn-primary">
                <i class="fa fa-plus"></i>
                <span>Create</span>
            </button>
        @endif
    </form>
@endsection
