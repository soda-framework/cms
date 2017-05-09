<?php

namespace Soda\Cms\Database\Models\Contracts;

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
     * @return \Soda\ClosureTable\Models\Entity
     */
    public function moveInto($entity);
}
