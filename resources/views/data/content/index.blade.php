<?php
    $isMovable = $content->where('is_movable', true)->count() ? true : false;
?>

@extends(soda_cms_view_path('layouts.inner'))

@section('content')

    <div class="clearfix">
        <div class="pull-right">
            <form class="form-inline">
                @foreach(['page', 'order', 'dir'] as $input)
                    @if(Request::has($input))
                        <input type="hidden" name="{{ $input }}" value="{{ Request::input($input) }}" />
                    @endif
                @endforeach
                <div class="form-group">
                    <label>View</label>
                    <select class="form-control" name="show">
                        <option value="20" {!! Request::input('show', 20) == 20 ? 'selected="selected"' : '' !!}>20</option>
                        <option value="50" {!! Request::input('show') == 50 ? 'selected="selected"' : '' !!}>50</option>
                        <option value="100" {!! Request::input('show') == 100 ? 'selected="selected"' : '' !!}>100</option>
                    </select>
                </div>
            </form>
        </div>
    </div>
    <Br />
    <div class="clearfix">
        <div class="pull-right">
            {{--
            <div class="btn-group">
                <div class="dropdown">
                    <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                        Actions
                        <i class="mdi mdi-menu-down"></i>
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
                        <li><a href="#">Pin</a></li>
                        <li><a href="#">Edit</a></li>
                        <li><a href="#">Publish</a></li>
                        <li><a href="#">Lock</a></li>
                        <li class="warning"><a href="#">Delete</a></li>
                    </ul>
                </div>
            </div>
            --}}
            @if(count($shortcuts))
                @permission('create-pages')
                <div class="btn-group">
                    <div class="dropdown">
                        <button class="btn btn-success dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                            Create
                            <i class="mdi mdi-menu-down"></i>
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
                            @foreach($shortcuts->where('is_folder', false) as $shortcut)
                                <li><a href="{{ route('soda.content.create') }}" v-on:click.prevent="newContentItem('{{ $shortcut->content_type_id }}')">{{ $shortcut->text }}</a></li>
                            @endforeach
                            @foreach($shortcuts->where('is_folder', true) as $shortcut)
                                <li><a href="{{ route('soda.content.create') }}" v-on:click.prevent="newContentFolder('{{ $shortcut->content_type_id }}')">{{ $shortcut->text }}</a></li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                @endpermission
            @endif
        </div>
    </div>
    <br />
    <table class="table table-striped sortable middle">
        <thead>
            <tr>
                {{--
                <th class="row-select" width="20">
                    <div class="checkbox">
                        <input type="checkbox" id="content-select-all" />
                        <label for="content-select-all"></label>
                    </div>
                </th>
                --}}
                <th colspan="{{ $isMovable ? 2 : 1 }}">Name</th>
                <th>Date Edited</th>
                <th colspan="2">Status</th>
            </tr>
        </thead>
        <tbody class="sortable" data-entityname="content" v-cloak>
            <tr v-for="contentItem in content" v-bind:data-itemid="contentItem.id" v-bind:data-parentid="contentItem.parent_id">
                {{--
                <td class="row-select">
                    <div class="checkbox">
                        <input type="checkbox" value="{{ $contentItem->id }}" id="content-select-{{ $contentItem->id }}" name="selected_content[]" />
                        <label for="content-select-{{ $contentItem->id }}"></label>
                    </div>
                </td>
                --}}
                @if($isMovable)
                <td class="text-center" width="20">
                    <span class="sortable-handle" v-if="contentItem.is_movable">
                        <i class="mdi mdi-drag-vertical"></i>
                    </span>
                </td>
                @endif
                <td>
                    <i class="mdi mdi-folder-outline" v-if="contentItem.is_folder"></i>
                    <i class="mdi mdi-file-outline" v-else-if="contentItem.is_sluggable"></i>
                    <i class="mdi mdi-vector-arrange-above" v-else></i>
                    <span v-text="contentItem.name"></span>
                </td>
                <td v-text="getFormattedDate(contentItem.updated_at)"></td> {{-- $contentItem->updated_at->format('jS F Y') --}}
                <td>
                    <template v-if="contentItem.is_publishable && contentItem.is_folder">
                        <span v-bind:class="{ 'active-circle': contentItem.status == {{ Soda\Cms\Foundation\Constants::STATUS_LIVE }}, 'inactive-circle': contentItem.status == {{ Soda\Cms\Foundation\Constants::STATUS_DRAFT }} }"></span>
                        <span v-if="contentItem.status == {{ Soda\Cms\Foundation\Constants::STATUS_LIVE }}">{{ Soda\Cms\Foundation\Constants::statuses()[Soda\Cms\Foundation\Constants::STATUS_LIVE] }}</span>
                        <span v-if="contentItem.status == {{ Soda\Cms\Foundation\Constants::STATUS_DRAFT }}">{{ Soda\Cms\Foundation\Constants::statuses()[Soda\Cms\Foundation\Constants::STATUS_DRAFT] }}</span>
                    </template>
                </td>
                <td class="text-right">
                    <div class="btn-group">
                        <div class="dropdown">
                            <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                Actions
                                <i class="mdi mdi-menu-down"></i>
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
                                <template v-if="contentItem.is_folder == 1">
                                    <li><a v-bind:href="routeTo('{{ route('soda.content.show', '###ID###') }}', contentItem.id)">Open folder</a></li>
                                </template>

                                @permission('edit-pages')
                                    <li><a v-bind:href="routeTo('{{ route('soda.content.edit', '###ID###') }}', contentItem.id)">Edit</a></li>
                                @else
                                    <li class="disabled"><a href="#"><i class="mdi mdi-lock"></i> <span>Edit</span></a></li>
                                @endpermission

                                <template v-if="contentItem.is_sluggable">
                                    <li><a v-bind:href="contentItem.slug" target="_blank">Preview</a></li>
                                </template>

                                <template v-if="1 == 0 && contentItem.is_folder !== 1 && contentItem.is_publishable">
                                    <li><a href="#">Publish</a></li>
                                </template>

                                <template v-if="contentItem.is_deletable">
                                    @permission('delete-pages')
                                    <li class="warning"><a v-bind:href="routeTo('{{ route('soda.content.destroy', '###ID###') }}', contentItem.id)" v-on:click.prevent="deleteContent">Delete</a></li>
                                    @else
                                        <li class="disabled"><a href="#"><i class="mdi mdi-lock"></i> <span>Delete</span></a></li>
                                        @endpermission
                                </template>
                                <template v-else>
                                    <li class="disabled"><a href="#"><i class="mdi mdi-lock"></i> <span>Delete</span></a></li>
                                </template>
                            </ul>
                        </div>
                    </div>

                    {{--
                    @if($contentItem->is_sluggable)
                        <a href="{{ $contentItem->slug }}" target="_blank"><i class="mdi mdi-eye"></i></a>
                        <a href="{{ route('soda.content.edit', $contentItem->id) }}"><i class="mdi mdi-pencil"></i></a>
                    @endif
                    @if($contentItem->is_folder)
                        <a href="{{ route('soda.content.show', $contentItem->id) }}"><i class="mdi mdi-folder-open"></i></a>
                    @endif
                    @if($contentItem->is_movable && !Request::has('order') && !Request::has('dir'))
                        <a href="#"><i class="mdi mdi-arrow-all"></i></a>
                    @endif
                    @if($contentItem->is_deletable)
                        <a data-delete-button href="{{ route('soda.content.destroy', $contentItem->id) }}"><i class="mdi mdi-delete"></i></a>
                    @endif
                    --}}
                </td>
            </tr>

            <tr v-if="!Object.keys(content).length">
                <td colspan="5">No content to display</td>
            </tr>
        </tbody>
    </table>

    {!! $content->render() !!}
@stop

@section('modals')
    @parent
    @permission('create-pages')
    <div class="modal fade" id="contentItemTypeModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title" id="myModalLabel">Select a content type..</h4>
                </div>

                <form method="GET" action="{{ route('soda.content.create') }}">
                    <input type="hidden" name="parentId" value="{{ $contentFolder->id }}" />
                    <div class="modal-body">
                        <fieldset class="form-group field_content_type dropdown-field">
                            <label for="field_page_type">Content Type</label>
                            <input type="hidden" name="contentTypeId" v-bind:value="selectedContentType" />
                            <select class="form-control" v-bind:value="selectedContentType" v-on:input="selectedContentType = $event.target.value">
                                <option v-for="option in contentItemTypes" v-bind:value="option.id" v-text="option.name"></option>
                            </select>
                        </fieldset>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button class="btn btn-primary">Create Content</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="contentFolderTypeModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title" id="myModalLabel">Select a folder type..</h4>
                </div>

                <form method="GET" action="{{ route('soda.content.create') }}">
                    <input type="hidden" name="parentId" value="{{ $contentFolder->id }}" />
                    <input type="hidden" name="folder" value="true" />
                    <div class="modal-body">
                        <fieldset class="form-group field_content_name">
                            <label>Name</label>
                            <input name="name" type="text" class="form-control" />
                        </fieldset>
                        <fieldset class="form-group field_content_type dropdown-field" v-if="Object.keys(this.contentFolderTypes).length">
                            <label>Folder Type</label>
                            <input type="hidden" name="contentTypeId" v-bind:value="selectedContentType" />
                            <select class="form-control" v-bind:value="selectedContentType" v-on:input="selectedContentType = $event.target.value">
                                <option v-for="option in contentFolderTypes" v-bind:value="option.id" v-text="option.name"></option>
                            </select>
                        </fieldset>
                        <input type="hidden" name="contentTypeId" v-else-if="selectedContentType !== null" v-model="selectedContentType" />
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button class="btn btn-primary">Create Folder</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endpermission
@stop

@section('footer.js')
    @parent
    <script>
        window.initVue = function(vueInstance) {
            vueInstance.content = {!! $content->getCollection()->keyBy('id')->toJson() !!};
            vueInstance.contentItemTypes = {!! $contentTypes->where('is_folder', 0)->keyBy('id')->toJson() !!};
            vueInstance.contentFolderTypes = {!! $contentTypes->where('is_folder', 1)->keyBy('id')->toJson() !!};
        }
    </script>
    <script src="/soda/cms/js/forms/sortable.js"></script>
    <script src="/soda/cms/js/content.js"></script>
    <script>
        $(function() {
            $('#content-select-all').on('change', function() {
                if($(this).is(':checked')) {
                    $(this).closest('table').find('input[name="selected_content[]"]').prop('checked', true).trigger('change');
                } else {
                    $(this).closest('table').find('input[name="selected_content[]"]').prop('checked', false).trigger('change');
                }
            });

            $('input[name="selected_content[]"]').on('change', function() {
                if($(this).is(':checked')) {
                    $(this).closest('tr').addClass('highlighted');
                } else {
                    $('#content-select-all').prop('checked', false);
                    $(this).closest('tr').removeClass('highlighted');
                }
            });

            $('select[name="show"]').on('change', function() {
                $(this).closest('form').submit();
            });

            var $sortableTable = $('.sortable');
            if ($sortableTable.length > 0) {
                $sortableTable.sortable({
                    handle: '.sortable-handle',
                    axis: 'y',
                    update: function (a, b) {

                        var entityName = $(this).data('entityname');
                        var $sorted = b.item;

                        var $previous = $sorted.prev();
                        var $next = $sorted.next();

                        if ($previous.length > 0) {
                            Soda.changePosition({
                                parentId: $sorted.data('parentid'),
                                type: 'moveAfter',
                                entityName: entityName,
                                id: $sorted.data('itemid'),
                                positionEntityId: $previous.data('itemid'),
                                '_token': '{{ csrf_token() }}'
                            });
                        } else if ($next.length > 0) {
                            Soda.changePosition({
                                parentId: $sorted.data('parentid'),
                                type: 'moveBefore',
                                entityName: entityName,
                                id: $sorted.data('itemid'),
                                positionEntityId: $next.data('itemid'),
                                '_token': '{{ csrf_token() }}'
                            });
                        } else {
                            console.error('Something went wrong!');
                        }
                    },
                    cursor: "move"
                });
            }
        })
    </script>
@stop
