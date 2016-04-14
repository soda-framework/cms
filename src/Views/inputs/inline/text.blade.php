<span class="editable" id="{{$unique}}">{!! $field_value !!}</span>
{{@$field_name}}
<button class="hide btn btn-primary edit-{{$unique}}" data-edit_id="{{$unique}}" data-edit_link="{{$link}}"><span class="fa fa-check"></span></button>

<script type="application/javascript">
	$(function() {
		tinymce.baseURL = "/soda/soda/components/tinymce";

		tiny = tinymce.init({
			selector: '#{{$unique}}.editable',
			inline: true,
			toolbar: 'undo redo',
			menubar: false,
			setup : function(ed) {
				$('.edit-{{$unique}}').hide();
				ed.on('change', function (e) {
					//show save button.
					$('.edit-{{$unique}}').show();
				});
			}
		});


		$('.edit-{{$unique}}').click(function(e){
			e.preventDefault();
			$me = $(this);
			link = $me.data('edit_link');

			$.post(link, {
				{{$element}}:tinymce.activeEditor.getContent({format : 'raw'}),
				_token:'{{csrf_token()}}'
			},function(data){
				//todo:handle response??
				$me.hide();
			});
		})
	});
</script>
