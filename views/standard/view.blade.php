@extends(soda_cms_view_path('layouts.inner'))

@section('header')
    <title>View {{ucfirst($block->name)}}</title>
@endsection

@section('content')
    <h1>
        @if(!$block->id)
            Create {{ucfirst($block->name)}}
        @else
            Update {{ucfirst($block->name)}}
        @endif
    </h1>

    <form method="POST" action='{{route('soda.page.block.edit', ['page_id' => $page->id, 'type' => $block->identifier, 'id' => $model->id])}}' class="form-wrapper">
        <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
        @foreach($block->type->fields as $field)
            {!! SodaForm::field($field)->setModel($model) !!}
        @endforeach
        <button type="submit" class="btn btn-primary">Save</button>
    </form>
@endsection
