@extends(config('soda.hint_path').'::layouts.inner')

@section('header')
    <title>View {{ucfirst($block->name)}}</title>
@endsection

@section('content')
    <h1>
        @if(!@$block->id)
            Create {{ucfirst($block->name)}}
        @else
            Update {{ucfirst($block->name)}}
        @endif
    </h1>

    <form method="POST" action='{{route('soda.dyn.edit', ['type'=>@$block->identifier, 'id'=>@$model->id])}}'
          class="form-wrapper">
        <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
        {{--TODO: swap for @each??, dont include model to view? Must be a better way to do what i want to do --}}
        @foreach($block->type->fields as $field)
            {!! SodaForm::field($field)->setModel($model) !!}
        @endforeach
        <button type="submit" class="btn btn-primary">Save</button>
    </form>
@endsection
