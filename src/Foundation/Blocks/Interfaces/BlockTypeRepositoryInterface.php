<?php
namespace Soda\Cms\Foundation\Blocks\Interfaces;

use Soda\Cms\Foundation\Support\Interfaces\CanBuildDataGrid;
use Soda\Cms\Foundation\Support\Interfaces\BaseRepositoryInterface;

interface BlockTypeRepositoryInterface extends CanBuildDataGrid, BaseRepositoryInterface
{
    public function getFilteredGrid($perPage);
}
