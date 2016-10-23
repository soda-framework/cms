<?php

namespace Soda\Cms\Database\Blocks\Interfaces;

use Soda\Cms\Database\Support\Interfaces\CanBuildDynamicModels;

interface BlockTypeInterface extends CanBuildDynamicModels
{
    public function getDynamicModel();

    public function block($pageId = null);

    public function blockQuery($pageId = null);
}
