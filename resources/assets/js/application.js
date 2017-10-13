// https://toddmotto.com/mastering-the-module-pattern/
window.Soda = (function () {
    var _initialized = false;
    var _debug = false;

    var elements = {
        deleteButtons: '[data-delete-button]',
        formSubmitters: '[data-submits]',
        sidebarToggle: '[data-sidebar-toggle]',
        sidebar: '.sidebar'
    };

    var colours = {
        default: {
            primary: "#242932",
            secondary: "#2f343f"
        },
        lime: {
            primary: "#69E815",
            secondary: "#0BD685"
        },
        strawberry: {
            primary: "#F75F86",
            secondary: "#EE25AF"
        },
        grape: {
            primary: "#8125EE",
            secondary: "#607EEE"
        },
    }

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

        $('.collapse', elements.sidebar).on('show.bs.collapse', function () {
            $(this).siblings('a.nav-link').find('.nav-dropdown-indicator').addClass('active');
            $('.collapse', elements.sidebar).not(this).collapse('hide');
        }).on('hide.bs.collapse', function () {
            $(this).siblings('a.nav-link').find('.nav-dropdown-indicator').removeClass('active');
        });

        $('.nav-item > a.nav-link', elements.sidebar).on('click', function() {
            var navItem = $(this).closest('.nav-item');
            var navGroup = navItem.closest('.nav-item-group');

            $('.nav-item, .nav-item-group', elements.sidebar).removeClass('active');

            if(navGroup.length == 0) {
                $('.collapse', elements.sidebar).collapse('hide');
            }

            navItem.addClass('active');
            navGroup.addClass('active');
        })

        $('.nav-tabs a', '.nav-pills a').on('click', function (e) {
            history.pushState(null, null, $(this).attr('href'));
        });

        $(window).on('popstate', function(){
            _selectActiveTab();
        });

        $(window).on('beforeunload', function(){
            $('body').addClass('unloading');
        });
    }

    var _selectActiveTab = function() {
        var activeTab = $('a[href="' + window.location.hash + '"]');

        if (activeTab.length) {
            activeTab.tab('show');
        } else if(Soda.queryString.tab) {
            $('a[href="#tab_' + Soda.queryString.tab + '"]').tab('show');
        } else {
            var navTabs = $('.nav-tabs');

            if(!navTabs.length) {
                navTabs = $('.nav-pills');
            }

            if(navTabs.length) {
                $('a:first', navTabs).tab('show');
            }
        }
    }

    var initialize = function() {
        if(_initialized === false) {
            _initialized = true;

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': _getCsrf()
                }
            });

            $('body').addClass('loaded');
            $('.soda-wrapper, .main-content').css('min-height', $(window).height() - $('.content-navbar').outerHeight(true));

            _selectActiveTab();
            _registerEvents();
        }
    }

    var confirmDelete = function(element, attributes) {
        var url = element.attr('href');
        var postData = $.extend({}, {_token: _getCsrf(), _method: 'DELETE'}, attributes);

        swal({
            title: Soda.lang.swal.title,
            text: Soda.lang.swal.text,
            type: "warning",
            showCancelButton: true,
            confirmButtonClass: "btn btn-danger",
            cancelButtonText: Soda.lang.swal.cancel,
            confirmButtonText: Soda.lang.swal.confirm,
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
        colours: colours,
        initialize: initialize,
        confirmDelete: confirmDelete,
        changePosition: changePosition,
        toggleSidebar: toggleSidebar,
    }
})();

$(function () {
    Soda.initialize();
});
