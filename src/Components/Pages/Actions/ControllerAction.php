<?php

namespace Soda\Cms\Components\Pages\Actions;

use App;
use Soda\Cms\Components\Pages\ActionTypeInterface;
use Soda\Cms\Models\Page;

class ControllerAction implements ActionTypeInterface {
    public function handle(Page $page, $parameters = []) {
        return App::call($page->action);
    }
}
