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
/******/ 	return __webpack_require__(__webpack_require__.s = 133);
/******/ })
/************************************************************************/
/******/ ({

/***/ 133:
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(33);


/***/ }),

/***/ 33:
/***/ (function(module, exports) {

(function (Soda) {
    var elements = {
        tree: '#page-tree',
        row: '.tree-row',
        toleranceElement: '> .tree-item',
        handle: '> .tree-item > .handle',
        minify: '.minify'
    };

    var classes = {
        branchClass: 'has-sub-items',
        collapsedClass: 'collapsed',
        disableNestingClass: false,
        errorClass: false,
        expandedClass: 'expanded',
        hoveringClass: false,
        placeholder: 'tree-item hovering',
        leafClass: false,
        disabledClass: false
    };

    var _registerEvents = function _registerEvents() {
        var parameters = $.extend({}, {
            handle: elements.handle,
            items: 'li',
            listType: 'ul',
            toleranceElement: '> .tree-item',
            isTree: true,
            protectRoot: true,
            startCollapsed: true,
            expandOnHover: 300,
            tabSize: 75,
            relocate: function relocate(event, item) {
                _moveNode(item.item);
            }
        }, classes);

        $(elements.tree).nestedSortable(parameters);

        $(elements.minify).on('click', function (e) {
            e.preventDefault();

            $(this).closest(elements.row).toggleClass(classes.collapsedClass).toggleClass(classes.expandedClass);
        });
    };

    var _moveNode = function _moveNode($item) {
        var itemId = $item.data('id');
        var parent = $item.parent().closest(elements.row);
        var parentId = parent.data('id');

        if (typeof parentId !== 'undefined') {

            var $previous = $item.prev();
            var $next = $item.next();

            var data = {
                entityName: 'pages',
                id: itemId
            };

            if ($previous.length > 0) {
                data.type = 'moveAfter';
                data.positionEntityId = $previous.data('id');
                Soda.changePosition(data);
            } else if ($next.length > 0) {
                data.type = 'moveBefore';
                data.positionEntityId = $next.data('id');
                Soda.changePosition(data);
            } else {
                data.type = 'moveInto';
                data.positionEntityId = parentId;
                Soda.changePosition(data);
            }

            $item.data('parent-id');
        }
    };

    $(function () {
        _registerEvents();
    });

    Soda.pageTree = {
        elements: elements,
        classes: classes
    };

    return Soda;
})(Soda || {});

/***/ })

/******/ });