@include(config('soda.hint_path').'::partials.heading',['icon'=>'fa fa-compass', 'title'=>$model->id?'Navigation Menu: '.$model->name:'New Navigation Menu'])

<form method="POST" action='{{route('soda.'.$hint.'.edit',['id'=>@$model->id])}}' class="form--wrapper" enctype="multipart/form-data">
<input type="hidden" name="_token" value="{{ csrf_token() }}" />
	@if($model->id)
		<span data-toggle="collapse" data-target="#collapseEditNavigation" aria-expanded="false" aria-controls="collapseEditNavigation">
			<button class="btn btn-primary btn-xs" type="button" data-toggle="button"  >
				Edit this menu item
			</button>
		</span>

		<div class="collapse" id="collapseEditNavigation">
			<div class="well">
				@include('soda::navigation.nav')
			</div>
		</div>
		{!! $tree !!}
		<a href="{{route('soda.navigation.create',['parent_id'=>$model->id])}}" class="btn btn-primary"><span class="fa fa-plus"></span> Create</a>
	@else
		@include('soda::navigation.nav')
	@endif
</form>