<?php
/**
 * Created by PhpStorm.
 * User: sidavies
 * Date: 3/02/2016
 * Time: 8:47 PM
 */

namespace Soda\Models\Scopes;


use Illuminate\Database\Eloquent\Scope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Soda\Models\Status;

class LiveScope implements Scope {

	public function apply(Builder $builder, Model $model)
	{
		return $builder->where('status_id', '=', Status::LIVE);
	}
}