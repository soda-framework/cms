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
/******/ 	return __webpack_require__(__webpack_require__.s = 129);
/******/ })
/************************************************************************/
/******/ ({

/***/ 129:
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(29);


/***/ }),

/***/ 29:
/***/ (function(module, exports) {

(function (Soda) {
    var elements = {
        textField: '[data-slug]',
        generateSlugButton: '[data-slug-generate]',
        generateExternalButton: '[data-slug-external]'
    };

    var generate = function generate(text, allowExternal) {
        if (!allowExternal || text.substr(0, 4) !== 'http' && text.indexOf('://') == -1) {
            text = text.toString().toLowerCase().replace(/<(?:.|\n)*?>/gm, '') // remove html tags
            .replace(/\s+/g, '-') // Replace spaces with -
            .replace(/[^\w\-\/\%\+\?\[\]]+/g, '') // Remove all non-word chars
            .replace(/\-\-+/g, '-') // Replace multiple - with single -
            .replace(/\/\/+/g, '/') // Replace multiple / with single /
            .replace(/^-+/, '') // Trim - from start of text
            .replace(/-+$/, ''); // Trim - from end of text

            if (text.substring(0, 1) != '/') {
                text = '/' + text;
            }
        }

        return text;
    };

    var prefix = function prefix(text, _prefix) {
        // If the last character is not a slash append a slash to it.
        if (_prefix && _prefix.substr(-1) != '/') {
            _prefix = _prefix + '/';
        }

        return generate(_prefix + text);
    };

    var external = function external() {
        var link = prompt("Enter external URL", "http://");
        return link.indexOf('://') == -1 ? 'http://' + link : link;
    };

    var _registerEvents = function _registerEvents() {
        $(elements.textField).on('keyup', function () {
            var text = $(this).val();
            var allowExternal = $(this).data('slug') == true;

            var filteredText = Soda.slugs.generate(text, allowExternal);

            if (filteredText !== text) {
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

    $(function () {
        _registerEvents();
    });

    Soda.slugs = {
        elements: elements,
        generate: generate,
        prefix: prefix,
        external: external
    };

    return Soda;
})(Soda || {});

/***/ })

/******/ });