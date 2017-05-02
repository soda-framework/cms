@extends(soda_cms_view_path('layouts.inner'))

@section('content-heading-button')
    @include(soda_cms_view_path('partials.buttons.save'), ['submits' => '#settings-form'])
@stop

@section('tab.settings')
    {!! app('soda.form')->text([
        "name"        => "Application Name",
        "description" => "The name of the application",
        "field_name"  => 'name',
    ])->setModel($application) !!}

    @permission('manage-application-urls')
    {!! app('soda.form')->tags([
        "name"         => "Application URLs",
        "description"  => "URLs that may be used to access the application",
        "field_name"   => 'application_urls',
        "field_params" => ['array-save' => null],
        "value"        => $application->urls->pluck('domain', 'domain')->toArray()
    ]) !!}
    @else
        {!! app('soda.form')->static_text([
            "name"         => "Application URLs",
            "description"  => "URLs that may be used to access the application",
            "field_name"   => 'application_urls',
            "value"        => implode(', ', $application->urls->pluck('domain', 'domain')->toArray()),
        ]) !!}
    @endif
@stop

@section('content')
    <ul class="nav nav-pills" role="tablist">
        @if(!isset($settingsByCategory['Settings']))
        <li role='presentation' aria-controls="tab_settings">
            <a role="tab" data-toggle="tab" href="#tab_settings">Settings</a>
        </li>
        @endif

        @foreach($settingsByCategory as $category => $settings)
            @if(count($settings))
                <li role='presentation' aria-controls="tab_{{ strtolower($category) }}">
                    <a role="tab" data-toggle="tab" href="#tab_{{ strtolower($category) }}">{{ $category }}</a>
                </li>
            @endif
        @endforeach
    </ul>

    <form method="POST" id="settings-form" action="{{ route('soda.settings.update', $application->id) }}" enctype="multipart/form-data">
        {!! csrf_field() !!}
        {!! method_field('PUT') !!}

        <div class="tab-content">
            @if(!isset($settingsByCategory['Settings']))
            <div class="tab-pane" id="tab_settings" role="tabpanel">
                <div class="row">
                    <div class="col-xs-12">
                        <div class="content-block">
                            @yield('tab.settings')
                        </div>
                    </div>
                </div>
            </div>
            @endif
            @foreach($settingsByCategory as $category => $settings)
                @if(count($settings))
                    <div class="tab-pane" id="tab_{{ strtolower($category) }}" role="tabpanel">
                        <div class="row">
                            <div class="col-xs-12">
                                <div class="content-block">
                                    @if($category == 'Settings')
                                        @yield('tab.settings')
                                        <hr />
                                    @endif
                                    @foreach($settings as $setting)
                                        {!! app('soda.form')->field($setting, 'settings')->setModel($setting) !!}
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>
    </form>

    <div class="content-bottom">
        @include(soda_cms_view_path('partials.buttons.save'), ['submits' => '#settings-form'])
    </div>
@stop
