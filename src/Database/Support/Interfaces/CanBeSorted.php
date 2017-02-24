<?php

namespace Soda\Cms\Database\Support\Interfaces;

interface CanBeSorted
{
    /**
     * @return void
     */
    public function moveAfter($entity);

    /**
     * @return void
     */
    public function moveBefore($entity);

    /**
     * @return \Franzose\ClosureTable\Models\Entity
     */
    public function moveInto($entity);
}
