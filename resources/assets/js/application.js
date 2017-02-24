// https://toddmotto.com/mastering-the-module-pattern/

var Soda = (function () {
    var _initialized = false;
    var _debug = false;

    var elements = {
        deleteButtons: '[data-delete-button]',
        formSubmitters: '[data-submits]',
        sidebarToggle: '[data-sidebar-toggle]',
        sidebar: '.sidebar'
    };

    var _log = function (message) {
        if(_debug === true) {
            console.log(message);
        }
    }

    var _getCsrf = function(){
        return $('meta[name="csrf-token"]').attr('content');
    }

    var _post = function(url, parameters) {
        var form = $('<form></form>');

        form.attr("method", "POST");
        form.attr("action", url);

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

    var _registerEvents = function() {
        $(elements.deleteButtons).on('click', function(e) {
            e.preventDefault();
            confirmDelete($(this));
        });

        $(elements.formSubmitters).on('click', function() {
            var form = $(this).data('submits');

            if($(this).data('publishes') != null) {
                $(form).find('input[name="status"]').val(1);
            }

            $(form).submit();
        });

        $(elements.sidebarToggle).on('click', toggleSidebar);
    }

    var initialize = function() {
        if(_initialized === false) {
            _initialized = true;

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': _getCsrf()
                }
            });

            $('.soda-wrapper, .main-content').css('min-height', $(window).height());
            $(".nav-item-group.active .collapse").collapse('show');

            if(Soda.queryString.tab) {
                $('a[href="#tab_' + Soda.queryString.tab + '"]').tab('show');
            } else {
                $('.nav-tabs a[data-toggle="tab"]').first().tab('show');
            }

            _registerEvents();
        }
    }

    var confirmDelete = function(element, attributes) {
        var url = element.attr('href');
        var postData = $.extend({}, {_token: _getCsrf(), _method: 'DELETE'}, attributes);

        swal({
            title: "Are you sure?",
            text: "This action can not be reversed!",
            type: "warning",
            showCancelButton: true,
            confirmButtonClass: "btn-danger",
            confirmButtonText: "Yes, delete it!",
            closeOnConfirm: false
        }, function(){
            if(url) {
                _post(url, postData);
            } else {
                element.closest('form').submit();
            }
        });
    }

    var changePosition = function(requestData){
        $.ajax({
            'url':  Soda.urls.sort,
            'type': 'POST',
            'data': requestData,
            'success': function(data) {
                if (data.errors) {
                    _log(data.errors);
                }
            },
            'error': function(){
                _log('Something went wrong!');
            }
        });
    }

    var toggleSidebar = function(e) {
        e.preventDefault();

        var isExpanded = $(elements.sidebar).hasClass('in');

        $(this).attr('aria-expanded', isExpanded ? false : true);
        $(elements.sidebar).toggleClass('in');
        $('body').toggleClass('sidebar-in').addClass('sidebar-transitioning');

        setTimeout(function() {
            $('body').removeClass('sidebar-transitioning');
        }, 250);
    }

    return {
        initialize: initialize,
        confirmDelete: confirmDelete,
        changePosition: changePosition,
        toggleSidebar: toggleSidebar,
    }
})();

$(function () {
    Soda.initialize();
});
