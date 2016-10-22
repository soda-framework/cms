@extends(soda_cms_view_path('layouts.inner'))

@section('breadcrumb')
    <ol class="breadcrumb">
        <li><a href="{{ route('soda.home') }}">Home</a></li>
        <li class="active">Application</li>
    </ol>
@stop

@section('head.title')
    <title>Soda CMS | Application</title>
@endsection

@section('content-heading-button')
    @include(soda_cms_view_path('partials.buttons.save'), ['submits' => '#application-form'])
@stop

@include(soda_cms_view_path('partials.heading'), [
    'icon'        => 'fa fa-database',
    'title'       => 'Application',
])
@section('tab.settings')
    <div class="row">
        <div class="col-xs-12">
            <div class="content-block">
                {!! SodaForm::text([
                    "name"        => "Name",
                    "description" => "The name of the application",
                    "field_name"  => 'name',
                ])->setModel($application) !!}

                @permission('manage-application-urls')
                {!! SodaForm::tags([
                    "name"        => "Application URLs",
                    "description" => "URLs that may be used to access the application",
                    "field_name"  => 'application_urls',
                    "value"       => $application->urls->pluck('domain', 'domain')
                ]) !!}
                @else
                    {!! SodaForm::static_text([
                        "name"        => "Application URLs",
                        "description" => "URLs that may be used to access the application",
                        "field_name"  => 'application_urls',
                        "value"       => implode(', ', $application->urls->pluck('domain', 'domain')->toArray()),
                    ]) !!}
                @endif
            </div>
        </div>
    </div>
@stop

@section('content')
    <ul class="nav nav-tabs" role="tablist">
        <li role='presentation' aria-controls="Page Settings">
            <a role="tab" data-toggle="tab" href="#tab_application_settings">Settings</a>
        </li>

        {{--
        @foreach($fieldCategories as $fieldCategory => $fields)
            @if(count($fields))
                <li role='presentation' aria-controls="block_{{ $fieldCategory }}">
                    <a role="tab" data-toggle="tab" href="#tab_block_{{ $fieldCategory }}">{{ $fieldCategory }}</a>
                </li>
            @endif
        @endforeach
        --}}
    </ul>

    <form method="POST" id="application-form" action="{{ route('soda.application.update', $application->id) }}">
        {!! csrf_field() !!}
        {!! method_field('PUT') !!}

        <div class="tab-content">
            <div class="tab-pane" id="tab_application_settings" role="tabpanel">
                @yield('tab.settings')
            </div>
            {{--
            @foreach($fieldCategories as $fieldCategory => $fields)
                @if(count($fields))
                    <div class="tab-pane" id="tab_block_{{ $block->id }}" role="tabpanel">

                        <div class="row">
                            <div class="col-xs-12">
                                <div class="content-block">
                                    @foreach($fields as $field)
                                        {!! SodaForm::field($field)->setModel($model) !!}
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            @endforeach
            --}}
        </div>
    </form>

    <div class="content-bottom">
        @include(soda_cms_view_path('partials.buttons.save'), ['submits' => '#application-form'])
    </div>
@stop

@section('footer.js')
    @parent
    <script>
        $('.nav-tabs a[data-toggle="tab"]').first().tab('show');
    </script>
@stop
