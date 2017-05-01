<?php

namespace Soda\Cms\Database\Models\Contracts;

interface BlockTypeInterface extends CanBuildDynamicModels
{
    public function getDynamicModel();

    public function block($contentId = null);

    public function blockQuery($contentId = null);
}
