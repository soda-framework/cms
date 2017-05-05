/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/
/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId])
/******/ 			return installedModules[moduleId].exports;
/******/
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			i: moduleId,
/******/ 			l: false,
/******/ 			exports: {}
/******/ 		};
/******/
/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/
/******/ 		// Flag the module as loaded
/******/ 		module.l = true;
/******/
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/
/******/
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;
/******/
/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;
/******/
/******/ 	// identity function for calling harmony imports with the correct context
/******/ 	__webpack_require__.i = function(value) { return value; };
/******/
/******/ 	// define getter function for harmony exports
/******/ 	__webpack_require__.d = function(exports, name, getter) {
/******/ 		if(!__webpack_require__.o(exports, name)) {
/******/ 			Object.defineProperty(exports, name, {
/******/ 				configurable: false,
/******/ 				enumerable: true,
/******/ 				get: getter
/******/ 			});
/******/ 		}
/******/ 	};
/******/
/******/ 	// getDefaultExport function for compatibility with non-harmony modules
/******/ 	__webpack_require__.n = function(module) {
/******/ 		var getter = module && module.__esModule ?
/******/ 			function getDefault() { return module['default']; } :
/******/ 			function getModuleExports() { return module; };
/******/ 		__webpack_require__.d(getter, 'a', getter);
/******/ 		return getter;
/******/ 	};
/******/
/******/ 	// Object.prototype.hasOwnProperty.call
/******/ 	__webpack_require__.o = function(object, property) { return Object.prototype.hasOwnProperty.call(object, property); };
/******/
/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "";
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = 34);
/******/ })
/************************************************************************/
/******/ ({

/***/ 34:
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(4);


/***/ }),

/***/ 4:
/***/ (function(module, exports) {

// https://toddmotto.com/mastering-the-module-pattern/
window.Soda = function () {
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
        }
    };

    var _log = function _log(message) {
        if (_debug === true) {
            console.log(message);
        }
    };

    var _getCsrf = function _getCsrf() {
        return $('meta[name="csrf-token"]').attr('content');
    };

    var _post = function _post(url, parameters) {
        var form = $('<form></form>');

        form.attr("method", "POST");
        form.attr("action", url);

        $.each(parameters, function (key, value) {
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
    };

    var _registerEvents = function _registerEvents() {
        $(elements.deleteButtons).on('click', function (e) {
            e.preventDefault();
            confirmDelete($(this));
        });

        $(elements.formSubmitters).on('click', function () {
            var form = $(this).data('submits');

            if ($(this).data('publishes') != null) {
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

        $('.nav-item > a.nav-link', elements.sidebar).on('click', function () {
            var navItem = $(this).closest('.nav-item');
            var navGroup = navItem.closest('.nav-item-group');

            $('.nav-item, .nav-item-group', elements.sidebar).removeClass('active');

            if (navGroup.length == 0) {
                $('.collapse', elements.sidebar).collapse('hide');
            }

            navItem.addClass('active');
            navGroup.addClass('active');
        });

        $('.nav-tabs a', '.nav-pills a').on('click', function (e) {
            history.pushState(null, null, $(this).attr('href'));
        });

        $(window).on('popstate', function () {
            _selectActiveTab();
        });

        $(window).on('beforeunload', function () {
            $('body').addClass('unloading');
        });
    };

    var _selectActiveTab = function _selectActiveTab() {
        var activeTab = $('a[href="' + window.location.hash + '"]');

        if (activeTab.length) {
            activeTab.tab('show');
        } else if (Soda.queryString.tab) {
            $('a[href="#tab_' + Soda.queryString.tab + '"]').tab('show');
        } else {
            var navTabs = $('.nav-tabs');

            if (!navTabs.length) {
                navTabs = $('.nav-pills');
            }

            if (navTabs.length) {
                $('a:first', navTabs).tab('show');
            }
        }
    };

    var initialize = function initialize() {
        if (_initialized === false) {
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
    };

    var confirmDelete = function confirmDelete(element, attributes) {
        var url = element.attr('href');
        var postData = $.extend({}, { _token: _getCsrf(), _method: 'DELETE' }, attributes);

        swal({
            title: "Are you sure?",
            text: "This action can not be reversed!",
            type: "warning",
            showCancelButton: true,
            confirmButtonClass: "btn-danger",
            confirmButtonText: "Yes, delete it!",
            closeOnConfirm: false
        }, function () {
            if (url) {
                _post(url, postData);
            } else {
                element.closest('form').submit();
            }
        });
    };

    var changePosition = function changePosition(requestData) {
        $.ajax({
            'url': Soda.urls.sort,
            'type': 'POST',
            'data': requestData,
            'success': function success(data) {
                if (data.errors) {
                    _log(data.errors);
                }
            },
            'error': function error() {
                _log('Something went wrong!');
            }
        });
    };

    var toggleSidebar = function toggleSidebar(e) {
        e.preventDefault();

        var isExpanded = $(elements.sidebar).hasClass('in');

        $(this).attr('aria-expanded', isExpanded ? false : true);
        $(elements.sidebar).toggleClass('in');
        $('body').toggleClass('sidebar-in').addClass('sidebar-transitioning');

        setTimeout(function () {
            $('body').removeClass('sidebar-transitioning');
        }, 250);
    };

    return {
        colours: colours,
        initialize: initialize,
        confirmDelete: confirmDelete,
        changePosition: changePosition,
        toggleSidebar: toggleSidebar
    };
}();

$(function () {
    Soda.initialize();
});

/***/ })

/******/ });