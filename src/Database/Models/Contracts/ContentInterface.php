<?php

namespace Soda\Cms\Database\Models\Contracts;

use Soda\ClosureTable\Contracts\EntityInterface;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

interface ContentInterface extends EntityInterface, CanBeSorted, AuditableContract
{
    public function getDynamicModel();

    public function getBlockType($identifier);

    public function getBlock($identifier);

    public function block($identifier);

    public function properties();

    /**
     * @return string
     */
    public function generateSlug($slug);

    /**
     * @return bool
     */
    public function isFolder();

    /**
     * @return bool
     */
    public function isSluggable();

    /**
     * @return bool
     */
    public function isDeletable();

    /**
     * @return bool
     */
    public function isMovable();

    public function dynamicTableExists();
    public function shouldDynamicTableExist();
}
