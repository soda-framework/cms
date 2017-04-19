<div id="sidebar-collape" class="sidebar">
    <a class="soda-logo-block" href="{{ route('soda.home') }}">
        @include(soda_cms_view_path('layouts.partials.header-image'))
    </a>
    {!! SodaMenu::render('sidebar') !!}
</div>
