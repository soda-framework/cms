<?php

namespace Soda\Cms\Models;

use Illuminate\Database\Eloquent\Model;

class Status extends Model {
    protected $table = 'statuses';
    const DRAFT = 0;
    const LIVE = 1;

}
