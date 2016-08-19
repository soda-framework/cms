<?php

namespace Themes\SodaTheme\Listeners;

use Soda\Cms\Events\NavigationWasRendered;

class MenuNavItems {

    public function handle(NavigationWasRendered $event) {
        return view('soda_theme_hint::cms.menu');
    }
}
