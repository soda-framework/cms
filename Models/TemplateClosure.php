<?php
namespace Soda\Models;

use Franzose\ClosureTable\Models\ClosureTable;

class TemplateClosure extends ClosureTable implements TemplateClosureInterface
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'template_closure';
}
