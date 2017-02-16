<?php
/**
 * Created by PhpStorm.
 * User: sidavies
 * Date: 6/02/2016
 * Time: 5:37 PM.
 */

namespace Soda\Models;

use Illuminate\Database\Eloquent\Model;

class Block extends Model
{
    protected $table = 'blocks';
    protected $fillable = [
        'name',
        'description',
        'identifier',
        'application_user_id',
        'application_id',
        'block_type_id',
    ];

    public function type()
    {
        return $this->belongsTo(BlockType::class, 'block_type_id');
    }
}
