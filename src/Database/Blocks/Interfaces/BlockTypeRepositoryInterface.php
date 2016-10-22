<?php
namespace Soda\Cms\Database\Blocks\Interfaces;

use Soda\Cms\Database\Support\Interfaces\BaseRepositoryInterface;
use Soda\Cms\Database\Support\Interfaces\CanBuildDataGrid;

interface BlockTypeRepositoryInterface extends CanBuildDataGrid, BaseRepositoryInterface
{
    public function getFilteredGrid($perPage);
}
