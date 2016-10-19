<?php
namespace Soda\Cms\Foundation\Blocks\Interfaces;

use Soda\Cms\Foundation\Support\Interfaces\BaseRepositoryInterface;
use Soda\Cms\Foundation\Support\Interfaces\CanBuildDataGrid;

interface BlockRepositoryInterface extends CanBuildDataGrid, BaseRepositoryInterface
{
    public function getTypes();
    public function getFilteredGrid($perPage);
    public function createStub($blockTypeId = null);
}
