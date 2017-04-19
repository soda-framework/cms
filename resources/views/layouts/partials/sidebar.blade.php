<div id="sidebar-collape" class="sidebar">
    <a class="soda-logo-block" href="{{ route('soda.home') }}">
        @include(soda_cms_view('layouts.partials.mobile-header'))
    </a>
    {!! SodaMenu::render('sidebar') !!}
</div>
