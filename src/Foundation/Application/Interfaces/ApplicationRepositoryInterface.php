<?php

namespace Soda\Cms\Foundation\Application\Interfaces;

interface ApplicationRepositoryInterface
{
    public function findById($id);

    public function findByUrl($url);
}
