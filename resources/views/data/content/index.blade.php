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

            <div class="btn-group">
                <div class="dropdown">
                    <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                        Create
                        <i class="mdi mdi-menu-down"></i>
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
                        <li><a href="#">New Content Item</a></li>
                        <li><a href="#">New Folder</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <br />
    <table class="table table-striped middle">
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
                <th colspan="2">Name</th>
                <th>Date Edited</th>
                <th colspan="2">Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($content as $contentItem)
                <tr>
                    {{--
                    <td class="row-select">
                        <div class="checkbox">
                            <input type="checkbox" value="{{ $contentItem->id }}" id="content-select-{{ $contentItem->id }}" name="selected_content[]" />
                            <label for="content-select-{{ $contentItem->id }}"></label>
                        </div>
                    </td>
                    --}}
                    <td width="20">
                        @if($contentItem->is_movable)
                            <i class="mdi mdi-drag-vertical"></i>
                        @endif
                    </td>
                    <td><i class="mdi {{ $contentItem->is_folder ? 'mdi-folder-outline' : 'mdi-file-outline' }}"></i> <span>{{ $contentItem->name }}</span></td>
                    <td>{{ $contentItem->updated_at->format('jS F Y') }}</td>
                    <td>
                        @if($contentItem->is_publishable && !$contentItem->is_folder)
                            <span class="{{ $contentItem->status == \Soda\Cms\Foundation\Constants::STATUS_DRAFT ? 'inactive' : 'active' }}-circle"></span> <span>{{ \Soda\Cms\Foundation\Constants::statuses()[$contentItem->status] }}</span>
                        @elseif(!$contentItem->is_folder)
                            <i class="mdi mdi-lock"></i> <span>Locked</span>
                        @endif
                    </td>
                    <td class="text-right">
                        <div class="btn-group">
                            <div class="dropdown">
                                <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                    Actions
                                    <i class="mdi mdi-menu-down"></i>
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
                                    @if($contentItem->is_folder)
                                        <li><a href="{{ route('soda.content.show', $contentItem->id) }}">Open folder</a></li>
                                    @endif
                                    @if($contentItem->is_sluggable)
                                        <li><a href="{{ route('soda.content.edit', $contentItem->id) }}">Edit</a></li>
                                        <li><a href="{{ $contentItem->slug }}" target="_blank">Preview</a></li>
                                    @endif
                                    @if(!$contentItem->is_folder && $contentItem->is_publishable)
                                        <li><a href="#">Publish</a></li>
                                    @endif
                                    @if($contentItem->is_deletable)
                                        <li class="warning"><a data-delete-button href="{{ route('soda.content.destroy', $contentItem->id) }}">Delete</a></li>
                                    @endif
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
            @endforeach
        </tbody>
    </table>

    {!! $content->render() !!}
@stop

@section('footer.js')
    @parent
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
        })
    </script>
@stop
