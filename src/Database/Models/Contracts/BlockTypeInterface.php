<?php

namespace Soda\Cms\Database\Models\Contracts;

interface BlockTypeInterface extends CanBuildDynamicModels
{
    public function getDynamicModel();

    public function block($pageId = null);

    public function blockQuery($pageId = null);
}
