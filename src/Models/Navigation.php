<?php
namespace SodaModels;

use Franzose\ClosureTable\Models\Entity;

class navigation extends Entity implements navigationInterface
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'navigations';

    /**
     * ClosureTable model instance.
     *
     * @var navigationClosure
     */
    protected $closure = 'SodaModels\navigationClosure';
}
