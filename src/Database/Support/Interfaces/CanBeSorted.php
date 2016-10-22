<?php

namespace Soda\Cms\Database\Support\Interfaces;

interface CanBeSorted
{
    public function moveAfter($entity);

    public function moveBefore($entity);

    public function moveInto($entity);
}
