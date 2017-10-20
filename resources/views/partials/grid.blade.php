@include('rapyd::toolbar', ['label' => $label, 'buttons_right' => $buttons['TR']])

<table{!! $dg->buildAttributes() !!}>
    <thead>
    <tr>
        @foreach ($dg->columns as $column)
            <th{!! $column->buildAttributes() !!}>
                {!! $column->label !!}
                @if ($column->orderby)
                    <div class="pull-right">
                        @if ($dg->onOrderby($column->orderby_field, 'asc'))
                            <span class="fa fa-caret-up"></span>
                        @else
                            <a href="{{ $dg->orderbyLink($column->orderby_field,'asc') }}">
                                <span class="fa fa-caret-up"></span>
                            </a>
                        @endif
                        @if ($dg->onOrderby($column->orderby_field, 'desc'))
                            <span class="fa fa-caret-down"></span>
                        @else
                            <a href="{{ $dg->orderbyLink($column->orderby_field,'desc') }}">
                                <span class="fa fa-caret-down"></span>
                            </a>
                        @endif
                    </div>
                @endif
            </th>
        @endforeach
    </tr>
    </thead>
    <tbody>
    @if (count($dg->rows) == 0)
        <tr>
            <td colspan="{!! count($dg->columns) !!}">{!! trans('rapyd::rapyd.no_records') !!}</td>
        </tr>
    @endif
    @foreach ($dg->rows as $row)
        <tr{!! $row->buildAttributes() !!}>
            @foreach ($row->cells as $cell)
                <td{!! $cell->buildAttributes() !!}>{!! $cell->value !!}</td>
            @endforeach
        </tr>
    @endforeach
    </tbody>
</table>


<div class="btn-toolbar" role="toolbar">
    @if ($dg->havePagination())
        <div class="pull-left">
            {!! $dg->links() !!}
        </div>
    @endif
</div>
