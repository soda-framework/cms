<?php

namespace Soda\Cms\Database\Models\Contracts;

interface DynamicModelInterface
{
    public static function fromTable($table, $params = []);

    public function getDynamicModelTablePrefix();
}
