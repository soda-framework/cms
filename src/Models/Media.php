<?php

namespace Soda\Models;

use Illuminate\Database\Eloquent\Model;

class Media extends Model{
    protected $table = 'media';
    protected $fillable = ['related_id', 'related_table', 'related_field', 'position', 'media', 'media_type'];


}
