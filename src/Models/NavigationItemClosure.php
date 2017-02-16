<?php

namespace Soda\Models;

use Franzose\ClosureTable\Models\ClosureTable;

class NavigationItemClosure extends ClosureTable implements NavigationItemClosureInterface
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'navigation_items_closure';
}
