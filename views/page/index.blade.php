@extends(soda_cms_view_path('layouts.inner'))

@section('breadcrumb')
	<ol class="breadcrumb">
		<li><a href="{{ route('soda.home') }}">Home</a></li>
		<li class="active">Pages</li>
	</ol>
@stop

@section('head.title')
	<title>Soda CMS | Pages</title>
@endsection

@include(soda_cms_view_path('partials.heading'), [
    'icon'        => 'fa fa-file-o',
    'title'       => 'Pages',
])

@section('content')
	{!! $tree !!}

	<a data-tree-add href="{{route('soda.'.$hint.'.create')}}" class="btn btn-primary btn-lg">
		<i class="fa fa-plus"></i>
		<span>Create Page</span>
	</a>

	<div class="modal fade" id="page_type_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
								aria-hidden="true">&times;</span></button>
					<h4 class="modal-title" id="myModalLabel">Select a page type..</h4>
				</div>
				<form method="GET" action="{{route('soda.'.$hint.'.create')}}">
					<div class="modal-body">
						<fieldset class="form-group field_page_type page_type  dropdown-field">
							<label for="field_page_type">Page Type</label>

							<select name="page_type_id" class="form-control" id="page_type_id">
								<option value="">None</option>
								@foreach($page_types as $page_type)
									<option value="{{$page_type->id}}">{{$page_type->name}}</option>
								@endforeach
							</select>
						</fieldset>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
						<button  class="btn btn-primary" >Create Page</button>
					</div>
				</form>
			</div>
		</div>
	</div>
	<script>
		$('[data-tree-add]').on('click', function(e){
			e.preventDefault();
			var modal = $('#page_type_modal');
			var action = $(this).attr('href');

			$('form', modal).attr('action', action);
			modal.modal('show');
		});
	</script>
@endsection
