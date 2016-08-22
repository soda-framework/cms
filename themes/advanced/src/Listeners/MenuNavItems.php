<?php

namespace Themes\SodaExample\Listeners;

use Soda\Cms\Events\NavigationWasRendered;

class MenuNavItems {

    public function handle(NavigationWasRendered $event) {
        return view('soda-example::cms.menu');
    }
}
