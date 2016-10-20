$(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $('.soda-wrapper, .main-content').css('min-height', $(window).height());

    $('[data-slug]').on('keyup', function () {
        var text = $(this).val();
        var allowExternal = $(this).data('slug') == true;

        if (!allowExternal || (text.substr(0, 4) !== 'http' && text.indexOf('://') == -1)) {
            var slug = slugify(text);
            if(slug !== text) {
                $(this).val(slug);
            }
        }
    });

    $('[data-slug-generate]').on('click', function () {
        var target = $(this).data('slug-generate');
        var from = $(this).data('slug-generate-from');
        var prefix = $(target).data('slug-prefix');
        var title = $(from).val();

        // If the last character is not a slash append a slash to it.
        if (prefix && prefix.substr(-1) != '/') {
            prefix = prefix + '/';
        }

        title = title.replace(/<(?:.|\n)*?>/gm, ''); // remove html tags

        $(target).val(slugify(prefix + title));
    });

    $('[data-slug-external]').on('click', function () {
        var target = $(this).data('slug-external');
        link = prompt("Enter external URL", "http://");
        link = (link.indexOf('://') == -1) ? 'http://' + link : link;

        $(target).val(link);
    });

    $('[data-submits]').on('click', function() {
        var form = $(this).data('submits');
        $(form).submit();
    });

    $('[data-delete-button]').on('click', function(e) {
        e.preventDefault();

        var url = $(this).attr('href');
        var attributes = {
            _method: 'DELETE',
            _token: $('meta[name="csrf-token"]').attr('content'),
        }

        swal({
            title: "Are you sure?",
            text: "This action can not be reversed!",
            type: "warning",
            showCancelButton: true,
            confirmButtonClass: "btn-danger",
            confirmButtonText: "Yes, delete it!",
            closeOnConfirm: false
        }, function(){
            post(url, attributes);
        });
    });
});

function slugify(text) {
    text = text.toString().toLowerCase()
        .replace(/\s+/g, '-')           // Replace spaces with -
        .replace(/[^\w\-\/\%\+\?\[\]]+/g, '')       // Remove all non-word chars
        .replace(/\-\-+/g, '-')         // Replace multiple - with single -
        .replace(/^-+/, '')             // Trim - from start of text
        .replace(/-+$/, '');            // Trim - from end of text

    if(text.substring(0,1) != '/') {
        text = '/' + text;
    }

    return text;
}

function post(path, parameters) {
    var form = $('<form></form>');

    form.attr("method", "post");
    form.attr("action", path);

    $.each(parameters, function(key, value) {
        var field = $('<input></input>');

        field.attr("type", "hidden");
        field.attr("name", key);
        field.attr("value", value);

        form.append(field);
    });

    // The form needs to be a part of the document in
    // order for us to be able to submit it.
    $(document.body).append(form);
    form.submit();
}
