<div class="modal fade" id="newQuicklinkModal" tabindex="-1" role="dialog" aria-labelledby="newQuicklinkLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="newQuicklinkLabel">Enter a title for your quicklink...</h4>
            </div>

            <form method="POST" action="{{ route('soda.add-quicklink') }}">
                {!! csrf_field() !!}
                <input type="hidden" name="route_name" value="{{ Route::getCurrentRoute()->action['as'] }}" />
                <input type="hidden" name="route_params" value="{{ json_encode(Route::getCurrentRoute()->parameters) }}" />
                <input type="hidden" name="request_params" value="{{ json_encode(Request::input(), true) }}" />
                <div class="modal-body">
                    <input type="text" class="form-control" name="text" value="{{ app('soda.interface')->getTitle() }}" />
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button class="btn btn-primary">Add Quicklink</button>
                </div>
            </form>
        </div>
    </div>
</div>
