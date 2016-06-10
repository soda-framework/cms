<script>
	(function(d, s, id){
		var js, fjs = d.getElementsByTagName(s)[0];
		if (d.getElementById(id)) {return;}
		js = d.createElement(s); js.id = id;
		js.src = "{{asset_url("soda/soda/components/tinymce/tinymce.min.js")}}";
		fjs.parentNode.insertBefore(js, fjs);
	}(document, 'script', 'soda-tinymce-js'));
</script>
<span class="editable" id="{{$unique}}">{!! $field_value !!}</span>
{{@$field_name}}
<button class="hide btn btn-primary edit-{{$unique}}" data-edit_id="{{$unique}}" data-edit_link="{{$link}}"><span class="fa fa-check"></span></button>

<script type="application/javascript">
	$(function() {
		setTimeout(function(){
			tinymce.baseURL = "/sodacms/sodacms/components/tinymce";

			tiny = tinymce.init({
				selector: '#{{$unique}}.editable',
				inline: true,
				toolbar: 'undo redo',
				menubar: false,
				setup : function(ed) {
					$('.edit-{{$unique}}').hide();
					ed.on('change', function (e) {
						//show save button.
						$('.edit-{{$unique}}').removeClass('hide').show();
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
			});
		}, 500);
		{{--TODO: we need to implement a proper event onloaded in here - rather than waiting for .5 seconds and hoping for the best.--}}
	});
</script>
