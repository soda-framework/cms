@extends(soda_cms_view_path('layouts.inner'))

@section('content')
    <table class="table">
        <thead>
            <tr>
                <th>Page name</th>
                <th>Date Edited</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($content as $contentItem)
                <tr>
                    <td>{{ $contentItem->name }}</td>
                    <td>{{ $contentItem->updated_at->toDateString() }}</td>
                    <td>{{ $contentItem->status }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@stop
