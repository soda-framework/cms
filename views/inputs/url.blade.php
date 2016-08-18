{{-- TODO: this should eventually suggest links as you type based off slug db --}}
<fieldset class="form-group field_{{ $field_name }} {{ $field_name }} {{ $field_class }} text-field">
	<label for="field_{{ $field_name }}">{{ $field_label }}</label>

	<select class="js-data-example-ajax">
		<option value="3620194" selected="selected">select2/select2</option>
	</select>

	<input name="{{ $prefixed_field_name }}" id="field_{{ $field_name }}" type="text"
		   class="form-control field_{{ $field_name }} {{ $field_name }} js-example-basic-single"
		   value="{{ $field_value }}"/>
	@if($field_info)
		<small class="text-muted">{{ $field_info }}</small>
	@endif
</fieldset>

<script type="text/javascript">

	$(".js-data-example-ajax").select2({
		ajax: {
			url: "https://api.github.com/search/repositories",
			dataType: 'json',
			delay: 250,
			data: function (params) {
				return {
					q: params.term, // search term
					page: params.page
				};
			},
			processResults: function (data, params) {
				// parse the results into the format expected by Select2
				// since we are using custom formatting functions we do not need to
				// alter the remote JSON data, except to indicate that infinite
				// scrolling can be used
				params.page = params.page || 1;

				return {
					results: data.items,
					pagination: {
						more: (params.page * 30) < data.total_count
					}
				};
			},
			cache: true
		},
		escapeMarkup: function (markup) {
			return markup;
		}, // let our custom formatter work
		minimumInputLength: 1,
		templateResult: formatRepo, // omitted for brevity, see the source of this page
		templateSelection: formatRepoSelection // omitted for brevity, see the source of this page
	});

	function formatRepo (repo) {
		if (repo.loading) return repo.text;

		var markup = "<div class='select2-result-repository clearfix'>" +
				"<div class='select2-result-repository__avatar'><img src='" + repo.owner.avatar_url + "' /></div>" +
				"<div class='select2-result-repository__meta'>" +
				"<div class='select2-result-repository__title'>" + repo.full_name + "</div>";

		if (repo.description) {
			markup += "<div class='select2-result-repository__description'>" + repo.description + "</div>";
		}

		markup += "<div class='select2-result-repository__statistics'>" +
				"<div class='select2-result-repository__forks'><i class='fa fa-flash'></i> " + repo.forks_count + " Forks</div>" +
				"<div class='select2-result-repository__stargazers'><i class='fa fa-star'></i> " + repo.stargazers_count + " Stars</div>" +
				"<div class='select2-result-repository__watchers'><i class='fa fa-eye'></i> " + repo.watchers_count + " Watchers</div>" +
				"</div>" +
				"</div></div>";

		return markup;
	}

	function formatRepoSelection (repo) {
		return repo.full_name || repo.text;
	}
</script>
