<?php
namespace Soda\Cms\Database\Blocks\Interfaces;

use Soda\Cms\Database\Support\Interfaces\BaseRepositoryInterface;
use Soda\Cms\Database\Support\Interfaces\CanBuildDataGrid;

interface BlockRepositoryInterface extends CanBuildDataGrid, BaseRepositoryInterface
{
    public function getTypes();

    public function createStub($blockTypeId = null);
}
