<?php

namespace Soda\Cms\Foundation\Blocks\Interfaces;

interface BlockInterface
{
    public function getDynamicModel();
    public function model($pageId = null);
    public function modelQuery($pageId = null);
}
