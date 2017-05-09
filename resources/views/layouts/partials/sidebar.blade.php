<div class="sidebar" id="sidebar-collape">
    <div class="sidebar__inner">
        <div class="sidebar__top">
            <a class="soda-logo-block" href="{{ route('soda.home') }}">
                @include(soda_cms_view_path('layouts.partials.header-image'))
            </a>
            <svg class="splash" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 18 18"><g id="Layer_2" data-name="Layer 2"><g id="Layer_1-2" data-name="Layer 1"><circle cx="9" cy="9" r="9" transform="translate(-3.73 9) rotate(-45)"/></g></g></svg>
            <svg class="wave" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 432.69 221.18"><g id="Layer_2" data-name="Layer 2"><g id="Layer_1-2" data-name="Layer 1"><path d="M432.69,34.77V221.18H0V45.5S19.23,88,69.89,88c62.23,0,52.91-71.5,162-32.14S359.47,38.41,365.33,20.19C370.47,4.21,404.87-22.08,432.69,34.77Z"/></g></g></svg>
        </div>

        <div class="sidebar__bg">
            <svg class="wave wave-top" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 433.12 145.81"><g id="Layer_2" data-name="Layer 2"><g id="Layer_1-2" data-name="Layer 1"><path d="M0,0V145.81H433.12V107s-28.09,44.73-96.57,34S249.83,38.88,130.12,77.67C48.67,104.06,0,0,0,0Z"/></g></g></svg>
            <svg class="splash splash-top" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 18 18"><g id="Layer_2" data-name="Layer 2"><g id="Layer_1-2" data-name="Layer 1"><circle cx="9" cy="9" r="9" transform="translate(-3.73 9) rotate(-45)"/></g></g></svg>

            <svg class="wave wave-bottom" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 433.12 145.81"><g id="Layer_2" data-name="Layer 2"><g id="Layer_1-2" data-name="Layer 1"><path d="M0,0V145.81H433.12V107s-28.09,44.73-96.57,34S249.83,38.88,130.12,77.67C48.67,104.06,0,0,0,0Z"/></g></g></svg>
            <svg class="splash splash-bottom" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 18 18"><g id="Layer_2" data-name="Layer 2"><g id="Layer_1-2" data-name="Layer 1"><circle cx="9" cy="9" r="9" transform="translate(-3.73 9) rotate(-45)"/></g></g></svg>
        </div>

        {!! app('soda.interface')->menuBuilder()->render('sidebar') !!}
    </div>
</div>
