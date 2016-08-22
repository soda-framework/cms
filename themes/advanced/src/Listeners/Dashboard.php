<?php

namespace Themes\SodaExample\Listeners;

use Soda\Cms\Events\DashboardWasRendered;

class Dashboard {

    public function handle(DashboardWasRendered $event) {

        return view('soda-example::cms.dashboard');
    }
}
