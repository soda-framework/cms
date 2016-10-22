<?php

namespace Soda\Cms\Database\Support\Interfaces;

interface DynamicModelInterface
{
    public static function fromTable($table, $params = []);

    public function getDynamicModelTablePrefix();
}
