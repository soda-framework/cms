<?php

namespace Soda\Cms\Database\Application\Interfaces;

interface ApplicationRepositoryInterface
{
    public function findById($id);

    public function findByUrl($url);
}
