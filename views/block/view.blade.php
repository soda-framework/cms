@extends(soda_cms_view_path('layouts.inner'))

@section('breadcrumb')
    <ol class="breadcrumb">
        <li><a href="{{ route('soda.home') }}">Home</a></li>
        <li><a href="{{ route('soda.block') }}">Blocks</a></li>
        <li class="active">{{ $model->name ? $model->name : 'New '. ($model->type ? $model->type->name . " Block" : "Block") }}</li>
    </ol>
@stop

@section('head.title')
    <title>Soda CMS | Edit Block</title>
@endsection

@section('content-heading-button')
    @include(soda_cms_view_path('partials.buttons.save'), ['submits' => '#block-form'])
@stop

@include(soda_cms_view_path('partials.heading'), [
    'icon'        => 'fa fa-square',
    'title'       => $model->name ? 'Block: ' . $model->name : 'New Block',
])

@section('content')
    <div class="content-block">
        <form id="block-form" method="POST" action='{{route('soda.block.edit',['id'=>@$model->id])}}'
              enctype="multipart/form-data">
            <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
            <input type="hidden" name="block_type_id" value="{{ $model->block_type_id }}"/>

            {!! SodaForm::text([
                'name'        => 'Block Name',
                'field_name'  => 'name',
            ])->setModel($model) !!}

            {!! SodaForm::toggle([
                'name'         => 'Status',
                'description'  => 'Determines whether the block is visible on the live website',
                'field_name'   => 'status',
                'field_params' => ['checked-value' => Soda\Cms\Support\Constants::STATUS_LIVE, 'unchecked-value' => Soda\Cms\Support\Constants::STATUS_DRAFT],
            ])->setModel($model) !!}

            {!! SodaForm::toggle([
                'name'         => 'Shared',
                'field_name'   => 'is_shared',
                'description'  => 'Whether or not the contents of this block should be shared across all pages. Changing this field affects current block contents.',
            ])->setModel($model) !!}

            {!! SodaForm::textarea([
                'name'        => 'Block Description',
                'field_name'  => 'description',
            ])->setModel($model) !!}

            {!! SodaForm::text([
                'name'        => 'Block Identifier',
                'field_name'  => 'identifier',
            ])->setModel($model) !!}
        </form>
    </div>

    <div class="content-bottom">
        @include(soda_cms_view_path('partials.buttons.save'), ['submits' => '#block-form'])
    </div>
@endsection
