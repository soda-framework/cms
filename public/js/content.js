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
/******/ 	return __webpack_require__(__webpack_require__.s = 28);
/******/ })
/************************************************************************/
/******/ ({

/***/ 1:
/***/ (function(module, exports) {

Soda.contentTable = new Vue({
    el: '#app',
    data: {
        content: {},
        contentItemTypes: {},
        contentFolderTypes: {},
        selectedContentType: null
    },
    mounted: function mounted() {
        window.initVue(this);
    },
    methods: {
        getFormattedDate: function getFormattedDate(date) {
            return moment(date, 'YYYY-MM-DD hh:mm:ss').format('Do MMMM YYYY');
        },
        routeTo: function routeTo(route, contentId) {
            return route.replace(new RegExp('###ID###', 'g'), contentId);
        },
        newContentItem: function newContentItem(contentTypeId) {
            var modal = $('#contentItemTypeModal');
            var form = modal.find('form');

            if (contentTypeId !== null && contentTypeId !== '') {
                this.$set(this, 'selectedContentType', contentTypeId);
                this.$nextTick(function () {
                    form.submit();
                });
            } else {
                var numContentTypes = Object.keys(this.contentItemTypes).length;
                this.$set(this, 'selectedContentType', numContentTypes ? Object.keys(this.contentItemTypes)[0] : null);

                if (numContentTypes > 1) {
                    modal.modal('show');
                } else {
                    this.$nextTick(function () {
                        form.submit();
                    });
                }
            }
        },
        newContentFolder: function newContentFolder(contentTypeId) {
            var modal = $('#contentFolderTypeModal');
            var form = modal.find('form');

            if (contentTypeId !== null && contentTypeId !== '') {
                this.$set(this, 'selectedContentType', contentTypeId);
                modal.modal('show');
            } else {
                var numContentTypes = Object.keys(this.contentFolderTypes).length;
                this.$set(this, 'selectedContentType', numContentTypes ? Object.keys(this.contentFolderTypes)[0] : null);
                modal.modal('show');
            }
        },
        deleteContent: function deleteContent(event) {
            Soda.confirmDelete($(event.target));
        }
    }
});

/***/ }),

/***/ 28:
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(1);


/***/ })

/******/ });