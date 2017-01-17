<?php

namespace Themes\SodaTheme\Listeners;

use Soda\Cms\Events\DashboardWasRendered;

class Dashboard
{
    public function handle(DashboardWasRendered $event)
    {
        return view('soda_theme_hint::cms.dashboard');
    }
}
