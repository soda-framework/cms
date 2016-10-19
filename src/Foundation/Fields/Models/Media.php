<?php

namespace Soda\Cms\Models;

use Illuminate\Database\Eloquent\Model;
use Soda\Cms\Foundation\Fields\Interfaces\MediaInterface;

class Media extends Model implements MediaInterface
{
    protected $table = 'media';
    protected $fillable = ['related_id', 'related_table', 'related_field', 'position', 'media', 'media_type'];
}
