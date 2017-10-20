<?php

namespace Soda\Cms\Database\Models;

use Illuminate\Database\Eloquent\Model;
use Soda\Cms\Database\Models\Traits\OptionallyBoundToApplication;

class ContentShortcut extends Model
{
    use OptionallyBoundToApplication;

    public $fillable = [
        'text',
        'is_folder',
        'override_default',
        'parent_id',
        'application_id',
        'content_type_id',
    ];
    protected $table = 'content_shortcuts';

    public function parent()
    {
        return $this->belongsTo(Content::class, 'parent_id');
    }

    public function type()
    {
        return $this->belongsTo(ContentType::class, 'content_type_id');
    }
}
