(function (Soda) {
    var elements = {
        textField: '[data-slug]',
        generateSlugButton: '[data-slug-generate]',
        generateExternalButton: '[data-slug-external]',
    };

    var generate = function(text, allowExternal) {
        if (!allowExternal || (text.substr(0, 4) !== 'http' && text.indexOf('://') == -1)) {
            text = text.toString().toLowerCase()
                .replace(/<(?:.|\n)*?>/gm, '')         // remove html tags
                .replace(/\s+/g, '-')                  // Replace spaces with -
                .replace(/[^\w\-\/\%\+\?\[\]]+/g, '')  // Remove all non-word chars
                .replace(/\-\-+/g, '-')                // Replace multiple - with single -
                .replace(/\/\/+/g, '/');                   // Trim - from end of text

            if(text.substring(0,1) != '/') {
                text = '/' + text;
            }
        }

        return text;
    };

    var prefix = function (text, prefix) {
        // If the last character is not a slash append a slash to it.
        if (prefix && prefix.substr(-1) != '/') {
            prefix = prefix + '/';
        }

        return generate(prefix + text);
    };

    var external = function () {
        var link  = prompt("Enter external URL", "http://");
        return (link.indexOf('://') == -1) ? 'http://' + link : link;
    };

    var _registerEvents = function () {
        $(elements.textField).on('keyup', function () {
            var text = $(this).val();
            var allowExternal = $(this).data('slug') == true;

            var filteredText = Soda.slugs.generate(text, allowExternal);

            if(filteredText !== text) {
                $(this).val(filteredText);
            }
        });

        $(elements.generateSlugButton).on('click', function () {
            var target = $(this).data('slug-generate');
            var from = $(this).data('slug-generate-from');

            var prefix = $(target).data('slug-prefix');
            var title = $(from).val();

            $(target).val(Soda.slugs.prefix(title, prefix));
        });

        $(elements.generateExternalButton).on('click', function () {
            var target = $(this).data('slug-external');

            $(target).val(Soda.slugs.external());
        });
    };

    $(function(){
        _registerEvents();
    })

    Soda.slugs = {
        elements: elements,
        generate: generate,
        prefix: prefix,
        external: external,
    };

    return Soda;

})(Soda || {});
