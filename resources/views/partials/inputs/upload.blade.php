@section("field")
    <img src="{{ $field_value }}" data-original-src="{{ $field_value }}" id="{{ $field_id }}_preview" style="display:{{ $field_value ? 'block' : 'none' }};margin-bottom:10px;max-width:500px;width:100%;height:auto;">
    <div class="input-group" style="width:100%">
        <input name="{{ $prefixed_field_name }}" id="{{ $field_id }}" type="file"
               class="form-control field_{{ $field_name }} {{ $field_name }}" />
        <span class="input-group-btn">
            <button id="{{ $field_id }}_clear" type="button" class="btn btn-warning" style="display:none">Clear</button>
        </span>
    </div>
@overwrite

@section("footer.js")
    @parent
    <script>
        $("#{{ $field_id }}").on('change', function () {

            if (this.files && this.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $('#{{ $field_id }}_preview').attr('src', e.target.result);
                    $('#{{ $field_id }}_preview').fadeIn();
                    $("#{{ $field_id }}_clear").fadeIn();
                }

                reader.readAsDataURL(this.files[0]);
            } else {
                $('#{{ $field_id }}_preview').fadeOut();
                $("#{{ $field_id }}_clear").fadeOut();
            }
        });

        $("#{{ $field_id }}_clear").on('click', function () {
            $('#{{ $field_id }}_preview').attr('src', $('#{{ $field_id }}_preview').data('original-src'));
            $('#{{ $field_id }}').val('');
        });
    </script>
@stop
