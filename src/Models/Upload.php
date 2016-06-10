<?php

namespace Soda\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use \Soda\Models\Scopes\FromApplicationScope;
use \Soda\Models\Scopes\LiveScope;

class Upload extends Model {

    protected $table = 'uploads';
    protected $fillable = ['file_url'];


}