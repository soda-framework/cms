@extends(soda_cms_view_path('layouts.inner'))

@section('content-heading-button')
    @permission('create-pages')
	    @include(soda_cms_view_path('partials.buttons.create'), ['modal' => '#pageTypeModal'])
    @endpermission
@stop

@section('content')
	@include(soda_cms_view_path('partials.page-tree.root'), ['tree' => $pages])
@endsection

@section('modals')
    @parent
    @permission('create-pages')
    <div class="modal fade" id="pageTypeModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Select a content type..</h4>
                </div>
                <form method="GET" action="{{ route('soda.pages.create') }}">
                    <div class="modal-body">
                        <fieldset class="form-group field_page_type page_type  dropdown-field">
                            <label for="field_page_type">Content Type</label>

                            <input type="hidden" name="parentId" value="" />
                            <select name="pageTypeId" class="form-control" id="page_type_id">
                            </select>
                        </fieldset>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button  class="btn btn-primary">Create Content</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endpermission
@stop

@section('footer.js')
    @parent
    @permission('create-pages')

    <script>
        var pageTypes = {!! json_encode($page_types->pluck('name', 'id')->prepend('None', 0), JSON_FORCE_OBJECT) !!};
        var allowedSubpageTypes = {!! json_encode($page_types->keyBy('id')->transform(function($item) {
            return $item->subpageTypes ? $item->subpageTypes->pluck('id')->toArray() : [];
        })) !!};

        $('#pageTypeModal').on('show.bs.modal', function (event) {
            var parentId = $(event.relatedTarget).data('page-id');
            var pageTypeId = $(event.relatedTarget).data('page-type-id');

            configurePagetypeDropdown(pageTypeId);

            $('input[name="parentId"]', this).val(parentId ? parentId : "");
        });

        function configurePagetypeDropdown(pageType)
        {
            var allowedSubpages = getAllowedSubpages(pageType);
            var pageTypeSelector = $('select#page_type_id');

            pageTypeSelector.empty();
            $.each(allowedSubpages, function(id, name){
                pageTypeSelector.append('<option value="' + (id == 0 ? '' : id) + '">' + name + '</option>');
            });
        }

        function getAllowedSubpages(pageTypeId)
        {
            var allowedSubpages = allowedSubpageTypes[pageTypeId];
            if(allowedSubpages && allowedSubpages.length) {
                return filterByKeys(pageTypes, allowedSubpages);
            }

            return pageTypes;
        }

        // Avoid issues with `{hasOwnProperty: 5}`
        var hasOwnProperty = ({}).hasOwnProperty;
        function filterByKeys(obj, keep) {
            var result = {};
            for (var i = 0, len = keep.length; i < len; i++) {
                var key = keep[i];
                if (hasOwnProperty.call(obj, key)) {
                    result[key] = obj[key];
                }
            }

            return result;
        };
    </script>
    @endpermission
@stop
