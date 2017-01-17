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
    protected $fillable = ['name', 'description'];
    protected $table = 'blocks';

    public $title = 'block';

    public $view_fields = [
        'name'=>[
            'label'=>'name',
            'type'=>'text',
            'name'=>'name',
        ],
        'description'=>[
            'label'=>'description',
            'type'=>'textarea',
            'name'=>'description',
        ],
    ];

    public function type()
    {
        return $this->belongsTo(BlockType::class, 'block_type_id');
    }
}
