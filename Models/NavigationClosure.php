<?php
namespace SodaModels;

use Franzose\ClosureTable\Models\ClosureTable;

class navigationClosure extends ClosureTable implements navigationClosureInterface
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'navigation_closure';
}
