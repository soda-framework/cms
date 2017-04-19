@if($logoUrl = Soda::getApplication()->logo_url)
    <img src="{{ $logoUrl }}" />
@else
    <img src="/soda/cms/img/sodacms_droplime.png" />
    <img src="/soda/cms/img/sodacms_logowhite.png" />
@endif
