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

        var filteredText = generateSlug(text, allowExternal);

        if(filteredText !== text) {
            $(this).val(filteredText);
        }
    });

    $('[data-slug-generate]').on('click', function () {
        var target = $(this).data('slug-generate');
        var from = $(this).data('slug-generate-from');

        var prefix = $(target).data('slug-prefix');
        var title = $(from).val();

        $(target).val(prefixSlug(title, prefix));
    });

    $('[data-slug-external]').on('click', function () {
        var target = $(this).data('slug-external');

        $(target).val(externalSlug());
    });

    $('[data-submits]').on('click', function() {
        var form = $(this).data('submits');
        $(form).submit();
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


function generateSlug(text, allowExternal) {
    if (!allowExternal || (text.substr(0, 4) !== 'http' && text.indexOf('://') == -1)) {
        text = text.toString().toLowerCase()
            .replace(/<(?:.|\n)*?>/gm, '')         // remove html tags
            .replace(/\s+/g, '-')                  // Replace spaces with -
            .replace(/[^\w\-\/\%\+\?\[\]]+/g, '')  // Remove all non-word chars
            .replace(/\-\-+/g, '-')                // Replace multiple - with single -
            .replace(/\/\/+/g, '/')                // Replace multiple / with single /
            .replace(/^-+/, '')                    // Trim - from start of text
            .replace(/-+$/, '');                   // Trim - from end of text

        if(text.substring(0,1) != '/') {
            text = '/' + text;
        }
    }

    return text;
};

function prefixSlug(text, prefix) {
    // If the last character is not a slash append a slash to it.
    if (prefix && prefix.substr(-1) != '/') {
        prefix = prefix + '/';
    }

    return generateSlug(prefix + text);
};

function externalSlug() {
    var link  = prompt("Enter external URL", "http://");
    return (link.indexOf('://') == -1) ? 'http://' + link : link;
};
