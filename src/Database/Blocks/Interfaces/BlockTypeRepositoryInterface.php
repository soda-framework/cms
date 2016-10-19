<?php
namespace Soda\Cms\Database\Blocks\Interfaces;

use Soda\Cms\Database\Support\Interfaces\CanBuildDataGrid;
use Soda\Cms\Database\Support\Interfaces\BaseRepositoryInterface;

interface BlockTypeRepositoryInterface extends CanBuildDataGrid, BaseRepositoryInterface
{
    public function getFilteredGrid($perPage);
}
