<?php

namespace Soda\Cms\Foundation\Support\Interfaces;

interface DynamicModelInterface
{
    public static function fromTable($table, $params = []);
    public function getDynamicModelTablePrefix();
}
