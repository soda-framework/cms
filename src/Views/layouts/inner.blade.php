@extends(config('soda.hint_path').'::layouts.app')

@section('main-title')
	@parent
	@yield('main-title')
@endsection

@section('main-header')
	@parent
	@yield('main-header')
@endsection

@section('main-content')
	<div class="col-sm-2 sidebar" >
		<ul class="nav nav-pills nav-stacked">
			<li class="nav-item">
				<a class="nav-link {{Request::is('cms/pages*')?'active':''}}"  href="{{route('soda.pages')}}"><span class="fa fa-file-o"></span> Pages</a>
			</li>
			<li class="nav-item">
				{{--todo: --}}
				<a class="nav-link {{Request::is('cms/page-types*')?'active':''}}" href="{{route('soda.page_type')}}"><span class="fa fa-edit"></span> Page Types</a>
			</li>
			<li class="nav-item">
				<a class="nav-link {{Request::is('cms/blocks*')?'active':''}}" href="{{route('soda.block')}}"><span class="fa fa-square"></span> Blocks</a>
			</li>
			<li class="nav-item">
				{{--todo: --}}
				<a class="nav-link {{Request::is('cms/block-types*')?'active':''}}" href="{{route('soda.block_type')}}"><span class="fa fa-edit"></span> Block Types</a>
			</li>
			<li class="nav-item">
				<a class="nav-link {{Request::is('cms/field*')?'active':''}}" href="{{route('soda.field')}}"><span class="fa fa-pencil"></span> Fields</a>
			</li>
			<li class="nav-item">
				<a class="nav-link {{Request::is('cms/user*')?'active':''}}" href="{{route('soda.user')}}"><span class="fa fa-users"></span> Users</a>
			</li>
			<li class="nav-item">
				<a class="nav-link {{Request::is('cms/applications*')?'active':''}}" href="#"><span class="fa fa-desktop"></span> Applications</a>
			</li>
			<li class="nav-item">
				<a class="nav-link {{Request::is('cms/settings*')?'active':''}}" href="#"><span class="fa fa-cog"></span> Application Settings</a>
			</li>
			@foreach( event(new Soda\Events\NavigationWasRendered()) as $item)
				{!! $item !!}
			@endforeach



		</ul>
	</div>
	<div class="col-sm-offset-2 col-sm-10 main-content">
		@yield('content')
	</div>
@endsection
