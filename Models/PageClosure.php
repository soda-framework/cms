<?php
namespace Soda\Models;

use Franzose\ClosureTable\Models\ClosureTable;

class PageClosure extends ClosureTable implements PageClosureInterface
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'pages_closure';
}
