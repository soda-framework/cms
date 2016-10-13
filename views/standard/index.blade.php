@extends(soda_cms_view_path('layouts.inner'))

@section('head.title')
    <title>Soda CMS | List {{ str_plural(ucfirst($models[0]->title)) }}</title>
@endsection

@include(soda_cms_view_path('partials.heading'), [
    'title' => 'List ' . str_plural(ucfirst($models[0]->title)),
])

@section('content')
    <table class="table">
        <thead>
        <tr>
            @foreach($fields as $key=>$field)
                <th>{{$key}}</th>
            @endforeach
            <th>update</th>
        </tr>
        </thead>
        <tbody>
        @foreach($models as $model)
            <tr>
                @foreach($fields as $key=>$field)
                    {{--TODO: additional logic in here for different field types--}}
                    <td>{{ $model->{$key} }}</td>
                @endforeach
                <td>
                    @if(@$type->id)
                        <a href="{{route('soda.'.$models[0]->title.'.view',['id'=>$model->id, 'type'=>@$type->id])}}" class="btn btn-success btn-sm">
                            <i class="fa fa-pencil"></i>
                        </a>
                        <a href="{{route('soda.'.$models[0]->title.'.delete',['id'=>$model->id, 'type'=>@$type->id])}}" class="btn btn-danger btn-sm">
                            <i class="fa fa-remove"></i>
                        </a>
                    @else
                        <a href="{{route('soda.'.$models[0]->title.'.view',['id'=>$model->id])}}" class="btn btn-success btn-sm">
                            <i class="fa fa-pencil"></i>
                        </a>
                        <a href="{{route('soda.'.$models[0]->title.'.delete',['id'=>$model->id])}}" class="btn btn-danger btn-sm">
                            <i class="fa fa-remove"></i>
                        </a>
                    @endif
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection
