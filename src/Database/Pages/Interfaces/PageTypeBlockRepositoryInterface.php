<?php

namespace Soda\Cms\Database\Pages\Interfaces;

interface PageTypeBlockRepositoryInterface
{
    public function attach($pageTypeId, $blockId, $pageBlockParams = []);

    public function detach($pageTypeId, $blockId);
}
