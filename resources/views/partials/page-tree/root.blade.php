<ul class="page-tree" id="page-tree">
    @foreach($tree as $treeItem)
        @include(soda_cms_view_path('partials.page-tree.branch'), ['page' => $treeItem])
    @endforeach
</ul>
@section('footer.js')
    @parent
    <script src="/soda/cms/js/forms/sortable.min.js"></script>
    <script src="/soda/cms/js/page-tree.min.js"></script>
@stop
