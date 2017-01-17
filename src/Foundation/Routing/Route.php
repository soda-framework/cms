<?php

namespace Soda\Cms\Foundation\Routing;

use Illuminate\Routing\Route as IlluminateRoute;

class Route extends IlluminateRoute
{
    /**
     * @var int
     */
    protected $priority = Router::DEFAULT_PRIORITY;

    /**
     * @return int
     */
    public function getPriority()
    {
        return $this->priority;
    }

    /**
     * @param int $priority
     */
    public function setPriority($priority)
    {
        $this->priority = $priority;
    }
}
