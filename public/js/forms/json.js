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
/******/ 	return __webpack_require__(__webpack_require__.s = 127);
/******/ })
/************************************************************************/
/******/ ([
/* 0 */,
/* 1 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";



module.exports = {
  copy: copy,
  checkDataType: checkDataType,
  checkDataTypes: checkDataTypes,
  coerceToTypes: coerceToTypes,
  toHash: toHash,
  getProperty: getProperty,
  escapeQuotes: escapeQuotes,
  ucs2length: __webpack_require__(51),
  varOccurences: varOccurences,
  varReplace: varReplace,
  cleanUpCode: cleanUpCode,
  cleanUpVarErrors: cleanUpVarErrors,
  schemaHasRules: schemaHasRules,
  schemaHasRulesExcept: schemaHasRulesExcept,
  stableStringify: __webpack_require__(5),
  toQuotedString: toQuotedString,
  getPathExpr: getPathExpr,
  getPath: getPath,
  getData: getData,
  unescapeFragment: unescapeFragment,
  escapeFragment: escapeFragment,
  escapeJsonPointer: escapeJsonPointer
};


function copy(o, to) {
  to = to || {};
  for (var key in o) to[key] = o[key];
  return to;
}


function checkDataType(dataType, data, negate) {
  var EQUAL = negate ? ' !== ' : ' === '
    , AND = negate ? ' || ' : ' && '
    , OK = negate ? '!' : ''
    , NOT = negate ? '' : '!';
  switch (dataType) {
    case 'null': return data + EQUAL + 'null';
    case 'array': return OK + 'Array.isArray(' + data + ')';
    case 'object': return '(' + OK + data + AND +
                          'typeof ' + data + EQUAL + '"object"' + AND +
                          NOT + 'Array.isArray(' + data + '))';
    case 'integer': return '(typeof ' + data + EQUAL + '"number"' + AND +
                           NOT + '(' + data + ' % 1)' +
                           AND + data + EQUAL + data + ')';
    default: return 'typeof ' + data + EQUAL + '"' + dataType + '"';
  }
}


function checkDataTypes(dataTypes, data) {
  switch (dataTypes.length) {
    case 1: return checkDataType(dataTypes[0], data, true);
    default:
      var code = '';
      var types = toHash(dataTypes);
      if (types.array && types.object) {
        code = types.null ? '(': '(!' + data + ' || ';
        code += 'typeof ' + data + ' !== "object")';
        delete types.null;
        delete types.array;
        delete types.object;
      }
      if (types.number) delete types.integer;
      for (var t in types)
        code += (code ? ' && ' : '' ) + checkDataType(t, data, true);

      return code;
  }
}


var COERCE_TO_TYPES = toHash([ 'string', 'number', 'integer', 'boolean', 'null' ]);
function coerceToTypes(optionCoerceTypes, dataTypes) {
  if (Array.isArray(dataTypes)) {
    var types = [];
    for (var i=0; i<dataTypes.length; i++) {
      var t = dataTypes[i];
      if (COERCE_TO_TYPES[t]) types[types.length] = t;
      else if (optionCoerceTypes === 'array' && t === 'array') types[types.length] = t;
    }
    if (types.length) return types;
  } else if (COERCE_TO_TYPES[dataTypes]) {
    return [dataTypes];
  } else if (optionCoerceTypes === 'array' && dataTypes === 'array') {
    return ['array'];
  }
}


function toHash(arr) {
  var hash = {};
  for (var i=0; i<arr.length; i++) hash[arr[i]] = true;
  return hash;
}


var IDENTIFIER = /^[a-z$_][a-z$_0-9]*$/i;
var SINGLE_QUOTE = /'|\\/g;
function getProperty(key) {
  return typeof key == 'number'
          ? '[' + key + ']'
          : IDENTIFIER.test(key)
            ? '.' + key
            : "['" + escapeQuotes(key) + "']";
}


function escapeQuotes(str) {
  return str.replace(SINGLE_QUOTE, '\\$&')
            .replace(/\n/g, '\\n')
            .replace(/\r/g, '\\r')
            .replace(/\f/g, '\\f')
            .replace(/\t/g, '\\t');
}


function varOccurences(str, dataVar) {
  dataVar += '[^0-9]';
  var matches = str.match(new RegExp(dataVar, 'g'));
  return matches ? matches.length : 0;
}


function varReplace(str, dataVar, expr) {
  dataVar += '([^0-9])';
  expr = expr.replace(/\$/g, '$$$$');
  return str.replace(new RegExp(dataVar, 'g'), expr + '$1');
}


var EMPTY_ELSE = /else\s*{\s*}/g
  , EMPTY_IF_NO_ELSE = /if\s*\([^)]+\)\s*\{\s*\}(?!\s*else)/g
  , EMPTY_IF_WITH_ELSE = /if\s*\(([^)]+)\)\s*\{\s*\}\s*else(?!\s*if)/g;
function cleanUpCode(out) {
  return out.replace(EMPTY_ELSE, '')
            .replace(EMPTY_IF_NO_ELSE, '')
            .replace(EMPTY_IF_WITH_ELSE, 'if (!($1))');
}


var ERRORS_REGEXP = /[^v\.]errors/g
  , REMOVE_ERRORS = /var errors = 0;|var vErrors = null;|validate.errors = vErrors;/g
  , REMOVE_ERRORS_ASYNC = /var errors = 0;|var vErrors = null;/g
  , RETURN_VALID = 'return errors === 0;'
  , RETURN_TRUE = 'validate.errors = null; return true;'
  , RETURN_ASYNC = /if \(errors === 0\) return true;\s*else throw new ValidationError\(vErrors\);/
  , RETURN_TRUE_ASYNC = 'return true;';

function cleanUpVarErrors(out, async) {
  var matches = out.match(ERRORS_REGEXP);
  if (!matches || matches.length !== 2) return out;
  return async
          ? out.replace(REMOVE_ERRORS_ASYNC, '')
               .replace(RETURN_ASYNC, RETURN_TRUE_ASYNC)
          : out.replace(REMOVE_ERRORS, '')
               .replace(RETURN_VALID, RETURN_TRUE);
}


function schemaHasRules(schema, rules) {
  for (var key in schema) if (rules[key]) return true;
}


function schemaHasRulesExcept(schema, rules, exceptKeyword) {
  for (var key in schema) if (key != exceptKeyword && rules[key]) return true;
}


function toQuotedString(str) {
  return '\'' + escapeQuotes(str) + '\'';
}


function getPathExpr(currentPath, expr, jsonPointers, isNumber) {
  var path = jsonPointers // false by default
              ? '\'/\' + ' + expr + (isNumber ? '' : '.replace(/~/g, \'~0\').replace(/\\//g, \'~1\')')
              : (isNumber ? '\'[\' + ' + expr + ' + \']\'' : '\'[\\\'\' + ' + expr + ' + \'\\\']\'');
  return joinPaths(currentPath, path);
}


function getPath(currentPath, prop, jsonPointers) {
  var path = jsonPointers // false by default
              ? toQuotedString('/' + escapeJsonPointer(prop))
              : toQuotedString(getProperty(prop));
  return joinPaths(currentPath, path);
}


var JSON_POINTER = /^\/(?:[^~]|~0|~1)*$/;
var RELATIVE_JSON_POINTER = /^([0-9]+)(#|\/(?:[^~]|~0|~1)*)?$/;
function getData($data, lvl, paths) {
  var up, jsonPointer, data, matches;
  if ($data === '') return 'rootData';
  if ($data[0] == '/') {
    if (!JSON_POINTER.test($data)) throw new Error('Invalid JSON-pointer: ' + $data);
    jsonPointer = $data;
    data = 'rootData';
  } else {
    matches = $data.match(RELATIVE_JSON_POINTER);
    if (!matches) throw new Error('Invalid JSON-pointer: ' + $data);
    up = +matches[1];
    jsonPointer = matches[2];
    if (jsonPointer == '#') {
      if (up >= lvl) throw new Error('Cannot access property/index ' + up + ' levels up, current level is ' + lvl);
      return paths[lvl - up];
    }

    if (up > lvl) throw new Error('Cannot access data ' + up + ' levels up, current level is ' + lvl);
    data = 'data' + ((lvl - up) || '');
    if (!jsonPointer) return data;
  }

  var expr = data;
  var segments = jsonPointer.split('/');
  for (var i=0; i<segments.length; i++) {
    var segment = segments[i];
    if (segment) {
      data += getProperty(unescapeJsonPointer(segment));
      expr += ' && ' + data;
    }
  }
  return expr;
}


function joinPaths (a, b) {
  if (a == '""') return b;
  return (a + ' + ' + b).replace(/' \+ '/g, '');
}


function unescapeFragment(str) {
  return unescapeJsonPointer(decodeURIComponent(str));
}


function escapeFragment(str) {
  return encodeURIComponent(escapeJsonPointer(str));
}


function escapeJsonPointer(str) {
  return str.replace(/~/g, '~0').replace(/\//g, '~1');
}


function unescapeJsonPointer(str) {
  return str.replace(/~1/g, '/').replace(/~0/g, '~');
}


/***/ }),
/* 2 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


var jsonlint = __webpack_require__(104);

/**
 * Parse JSON using the parser built-in in the browser.
 * On exception, the jsonString is validated and a detailed error is thrown.
 * @param {String} jsonString
 * @return {JSON} json
 */
exports.parse = function parse(jsonString) {
  try {
    return JSON.parse(jsonString);
  }
  catch (err) {
    // try to throw a more detailed error message using validate
    exports.validate(jsonString);

    // rethrow the original error
    throw err;
  }
};

/**
 * Sanitize a JSON-like string containing. For example changes JavaScript
 * notation into JSON notation.
 * This function for example changes a string like "{a: 2, 'b': {c: 'd'}"
 * into '{"a": 2, "b": {"c": "d"}'
 * @param {string} jsString
 * @returns {string} json
 */
exports.sanitize = function (jsString) {
  // escape all single and double quotes inside strings
  var chars = [];
  var i = 0;

  //If JSON starts with a function (characters/digits/"_-"), remove this function.
  //This is useful for "stripping" JSONP objects to become JSON
  //For example: /* some comment */ function_12321321 ( [{"a":"b"}] ); => [{"a":"b"}]
  var match = jsString.match(/^\s*(\/\*(.|[\r\n])*?\*\/)?\s*[\da-zA-Z_$]+\s*\(([\s\S]*)\)\s*;?\s*$/);
  if (match) {
    jsString = match[3];
  }

  // helper functions to get the current/prev/next character
  function curr () { return jsString.charAt(i);     }
  function next()  { return jsString.charAt(i + 1); }
  function prev()  { return jsString.charAt(i - 1); }

  // get the last parsed non-whitespace character
  function lastNonWhitespace () {
    var p = chars.length - 1;

    while (p >= 0) {
      var pp = chars[p];
      if (pp !== ' ' && pp !== '\n' && pp !== '\r' && pp !== '\t') { // non whitespace
        return pp;
      }
      p--;
    }

    return '';
  }

  // skip a block comment '/* ... */'
  function skipBlockComment () {
    i += 2;
    while (i < jsString.length && (curr() !== '*' || next() !== '/')) {
      i++;
    }
    i += 2;
  }

  // skip a comment '// ...'
  function skipComment () {
    i += 2;
    while (i < jsString.length && (curr() !== '\n')) {
      i++;
    }
  }

  // parse single or double quoted string
  function parseString(quote) {
    chars.push('"');
    i++;
    var c = curr();
    while (i < jsString.length && c !== quote) {
      if (c === '"' && prev() !== '\\') {
        // unescaped double quote, escape it
        chars.push('\\');
      }

      // handle escape character
      if (c === '\\') {
        i++;
        c = curr();

        // remove the escape character when followed by a single quote ', not needed
        if (c !== '\'') {
          chars.push('\\');
        }
      }
      chars.push(c);

      i++;
      c = curr();
    }
    if (c === quote) {
      chars.push('"');
      i++;
    }
  }

  // parse an unquoted key
  function parseKey() {
    var specialValues = ['null', 'true', 'false'];
    var key = '';
    var c = curr();

    var regexp = /[a-zA-Z_$\d]/; // letter, number, underscore, dollar character
    while (regexp.test(c)) {
      key += c;
      i++;
      c = curr();
    }

    if (specialValues.indexOf(key) === -1) {
      chars.push('"' + key + '"');
    }
    else {
      chars.push(key);
    }
  }

  while(i < jsString.length) {
    var c = curr();

    if (c === '/' && next() === '*') {
      skipBlockComment();
    }
    else if (c === '/' && next() === '/') {
      skipComment();
    }
    else if (c === '\'' || c === '"') {
      parseString(c);
    }
    else if (/[a-zA-Z_$]/.test(c) && ['{', ','].indexOf(lastNonWhitespace()) !== -1) {
      // an unquoted object key (like a in '{a:2}')
      parseKey();
    }
    else {
      chars.push(c);
      i++;
    }
  }

  return chars.join('');
};

/**
 * Escape unicode characters.
 * For example input '\u2661' (length 1) will output '\\u2661' (length 5).
 * @param {string} text
 * @return {string}
 */
exports.escapeUnicodeChars = function (text) {
  // see https://www.wikiwand.com/en/UTF-16
  // note: we leave surrogate pairs as two individual chars,
  // as JSON doesn't interpret them as a single unicode char.
  return text.replace(/[\u007F-\uFFFF]/g, function(c) {
    return '\\u'+('0000' + c.charCodeAt(0).toString(16)).slice(-4);
  })
};

/**
 * Validate a string containing a JSON object
 * This method uses JSONLint to validate the String. If JSONLint is not
 * available, the built-in JSON parser of the browser is used.
 * @param {String} jsonString   String with an (invalid) JSON object
 * @throws Error
 */
exports.validate = function validate(jsonString) {
  if (typeof(jsonlint) != 'undefined') {
    jsonlint.parse(jsonString);
  }
  else {
    JSON.parse(jsonString);
  }
};

/**
 * Extend object a with the properties of object b
 * @param {Object} a
 * @param {Object} b
 * @return {Object} a
 */
exports.extend = function extend(a, b) {
  for (var prop in b) {
    if (b.hasOwnProperty(prop)) {
      a[prop] = b[prop];
    }
  }
  return a;
};

/**
 * Remove all properties from object a
 * @param {Object} a
 * @return {Object} a
 */
exports.clear = function clear (a) {
  for (var prop in a) {
    if (a.hasOwnProperty(prop)) {
      delete a[prop];
    }
  }
  return a;
};

/**
 * Get the type of an object
 * @param {*} object
 * @return {String} type
 */
exports.type = function type (object) {
  if (object === null) {
    return 'null';
  }
  if (object === undefined) {
    return 'undefined';
  }
  if ((object instanceof Number) || (typeof object === 'number')) {
    return 'number';
  }
  if ((object instanceof String) || (typeof object === 'string')) {
    return 'string';
  }
  if ((object instanceof Boolean) || (typeof object === 'boolean')) {
    return 'boolean';
  }
  if ((object instanceof RegExp) || (typeof object === 'regexp')) {
    return 'regexp';
  }
  if (exports.isArray(object)) {
    return 'array';
  }

  return 'object';
};

/**
 * Test whether a text contains a url (matches when a string starts
 * with 'http://*' or 'https://*' and has no whitespace characters)
 * @param {String} text
 */
var isUrlRegex = /^https?:\/\/\S+$/;
exports.isUrl = function isUrl (text) {
  return (typeof text == 'string' || text instanceof String) &&
      isUrlRegex.test(text);
};

/**
 * Tes whether given object is an Array
 * @param {*} obj
 * @returns {boolean} returns true when obj is an array
 */
exports.isArray = function (obj) {
  return Object.prototype.toString.call(obj) === '[object Array]';
};

/**
 * Retrieve the absolute left value of a DOM element
 * @param {Element} elem    A dom element, for example a div
 * @return {Number} left    The absolute left position of this element
 *                          in the browser page.
 */
exports.getAbsoluteLeft = function getAbsoluteLeft(elem) {
  var rect = elem.getBoundingClientRect();
  return rect.left + window.pageXOffset || document.scrollLeft || 0;
};

/**
 * Retrieve the absolute top value of a DOM element
 * @param {Element} elem    A dom element, for example a div
 * @return {Number} top     The absolute top position of this element
 *                          in the browser page.
 */
exports.getAbsoluteTop = function getAbsoluteTop(elem) {
  var rect = elem.getBoundingClientRect();
  return rect.top + window.pageYOffset || document.scrollTop || 0;
};

/**
 * add a className to the given elements style
 * @param {Element} elem
 * @param {String} className
 */
exports.addClassName = function addClassName(elem, className) {
  var classes = elem.className.split(' ');
  if (classes.indexOf(className) == -1) {
    classes.push(className); // add the class to the array
    elem.className = classes.join(' ');
  }
};

/**
 * add a className to the given elements style
 * @param {Element} elem
 * @param {String} className
 */
exports.removeClassName = function removeClassName(elem, className) {
  var classes = elem.className.split(' ');
  var index = classes.indexOf(className);
  if (index != -1) {
    classes.splice(index, 1); // remove the class from the array
    elem.className = classes.join(' ');
  }
};

/**
 * Strip the formatting from the contents of a div
 * the formatting from the div itself is not stripped, only from its childs.
 * @param {Element} divElement
 */
exports.stripFormatting = function stripFormatting(divElement) {
  var childs = divElement.childNodes;
  for (var i = 0, iMax = childs.length; i < iMax; i++) {
    var child = childs[i];

    // remove the style
    if (child.style) {
      // TODO: test if child.attributes does contain style
      child.removeAttribute('style');
    }

    // remove all attributes
    var attributes = child.attributes;
    if (attributes) {
      for (var j = attributes.length - 1; j >= 0; j--) {
        var attribute = attributes[j];
        if (attribute.specified === true) {
          child.removeAttribute(attribute.name);
        }
      }
    }

    // recursively strip childs
    exports.stripFormatting(child);
  }
};

/**
 * Set focus to the end of an editable div
 * code from Nico Burns
 * http://stackoverflow.com/users/140293/nico-burns
 * http://stackoverflow.com/questions/1125292/how-to-move-cursor-to-end-of-contenteditable-entity
 * @param {Element} contentEditableElement   A content editable div
 */
exports.setEndOfContentEditable = function setEndOfContentEditable(contentEditableElement) {
  var range, selection;
  if(document.createRange) {
    range = document.createRange();//Create a range (a range is a like the selection but invisible)
    range.selectNodeContents(contentEditableElement);//Select the entire contents of the element with the range
    range.collapse(false);//collapse the range to the end point. false means collapse to end rather than the start
    selection = window.getSelection();//get the selection object (allows you to change selection)
    selection.removeAllRanges();//remove any selections already made
    selection.addRange(range);//make the range you have just created the visible selection
  }
};

/**
 * Select all text of a content editable div.
 * http://stackoverflow.com/a/3806004/1262753
 * @param {Element} contentEditableElement   A content editable div
 */
exports.selectContentEditable = function selectContentEditable(contentEditableElement) {
  if (!contentEditableElement || contentEditableElement.nodeName != 'DIV') {
    return;
  }

  var sel, range;
  if (window.getSelection && document.createRange) {
    range = document.createRange();
    range.selectNodeContents(contentEditableElement);
    sel = window.getSelection();
    sel.removeAllRanges();
    sel.addRange(range);
  }
};

/**
 * Get text selection
 * http://stackoverflow.com/questions/4687808/contenteditable-selected-text-save-and-restore
 * @return {Range | TextRange | null} range
 */
exports.getSelection = function getSelection() {
  if (window.getSelection) {
    var sel = window.getSelection();
    if (sel.getRangeAt && sel.rangeCount) {
      return sel.getRangeAt(0);
    }
  }
  return null;
};

/**
 * Set text selection
 * http://stackoverflow.com/questions/4687808/contenteditable-selected-text-save-and-restore
 * @param {Range | TextRange | null} range
 */
exports.setSelection = function setSelection(range) {
  if (range) {
    if (window.getSelection) {
      var sel = window.getSelection();
      sel.removeAllRanges();
      sel.addRange(range);
    }
  }
};

/**
 * Get selected text range
 * @return {Object} params  object containing parameters:
 *                              {Number}  startOffset
 *                              {Number}  endOffset
 *                              {Element} container  HTML element holding the
 *                                                   selected text element
 *                          Returns null if no text selection is found
 */
exports.getSelectionOffset = function getSelectionOffset() {
  var range = exports.getSelection();

  if (range && 'startOffset' in range && 'endOffset' in range &&
      range.startContainer && (range.startContainer == range.endContainer)) {
    return {
      startOffset: range.startOffset,
      endOffset: range.endOffset,
      container: range.startContainer.parentNode
    };
  }

  return null;
};

/**
 * Set selected text range in given element
 * @param {Object} params   An object containing:
 *                              {Element} container
 *                              {Number} startOffset
 *                              {Number} endOffset
 */
exports.setSelectionOffset = function setSelectionOffset(params) {
  if (document.createRange && window.getSelection) {
    var selection = window.getSelection();
    if(selection) {
      var range = document.createRange();

      if (!params.container.firstChild) {
        params.container.appendChild(document.createTextNode(''));
      }

      // TODO: do not suppose that the first child of the container is a textnode,
      //       but recursively find the textnodes
      range.setStart(params.container.firstChild, params.startOffset);
      range.setEnd(params.container.firstChild, params.endOffset);

      exports.setSelection(range);
    }
  }
};

/**
 * Get the inner text of an HTML element (for example a div element)
 * @param {Element} element
 * @param {Object} [buffer]
 * @return {String} innerText
 */
exports.getInnerText = function getInnerText(element, buffer) {
  var first = (buffer == undefined);
  if (first) {
    buffer = {
      'text': '',
      'flush': function () {
        var text = this.text;
        this.text = '';
        return text;
      },
      'set': function (text) {
        this.text = text;
      }
    };
  }

  // text node
  if (element.nodeValue) {
    return buffer.flush() + element.nodeValue;
  }

  // divs or other HTML elements
  if (element.hasChildNodes()) {
    var childNodes = element.childNodes;
    var innerText = '';

    for (var i = 0, iMax = childNodes.length; i < iMax; i++) {
      var child = childNodes[i];

      if (child.nodeName == 'DIV' || child.nodeName == 'P') {
        var prevChild = childNodes[i - 1];
        var prevName = prevChild ? prevChild.nodeName : undefined;
        if (prevName && prevName != 'DIV' && prevName != 'P' && prevName != 'BR') {
          innerText += '\n';
          buffer.flush();
        }
        innerText += exports.getInnerText(child, buffer);
        buffer.set('\n');
      }
      else if (child.nodeName == 'BR') {
        innerText += buffer.flush();
        buffer.set('\n');
      }
      else {
        innerText += exports.getInnerText(child, buffer);
      }
    }

    return innerText;
  }
  else {
    if (element.nodeName == 'P' && exports.getInternetExplorerVersion() != -1) {
      // On Internet Explorer, a <p> with hasChildNodes()==false is
      // rendered with a new line. Note that a <p> with
      // hasChildNodes()==true is rendered without a new line
      // Other browsers always ensure there is a <br> inside the <p>,
      // and if not, the <p> does not render a new line
      return buffer.flush();
    }
  }

  // br or unknown
  return '';
};

/**
 * Returns the version of Internet Explorer or a -1
 * (indicating the use of another browser).
 * Source: http://msdn.microsoft.com/en-us/library/ms537509(v=vs.85).aspx
 * @return {Number} Internet Explorer version, or -1 in case of an other browser
 */
exports.getInternetExplorerVersion = function getInternetExplorerVersion() {
  if (_ieVersion == -1) {
    var rv = -1; // Return value assumes failure.
    if (navigator.appName == 'Microsoft Internet Explorer')
    {
      var ua = navigator.userAgent;
      var re  = new RegExp("MSIE ([0-9]{1,}[\.0-9]{0,})");
      if (re.exec(ua) != null) {
        rv = parseFloat( RegExp.$1 );
      }
    }

    _ieVersion = rv;
  }

  return _ieVersion;
};

/**
 * Test whether the current browser is Firefox
 * @returns {boolean} isFirefox
 */
exports.isFirefox = function isFirefox () {
  return (navigator.userAgent.indexOf("Firefox") != -1);
};

/**
 * cached internet explorer version
 * @type {Number}
 * @private
 */
var _ieVersion = -1;

/**
 * Add and event listener. Works for all browsers
 * @param {Element}     element    An html element
 * @param {string}      action     The action, for example "click",
 *                                 without the prefix "on"
 * @param {function}    listener   The callback function to be executed
 * @param {boolean}     [useCapture] false by default
 * @return {function}   the created event listener
 */
exports.addEventListener = function addEventListener(element, action, listener, useCapture) {
  if (element.addEventListener) {
    if (useCapture === undefined)
      useCapture = false;

    if (action === "mousewheel" && exports.isFirefox()) {
      action = "DOMMouseScroll";  // For Firefox
    }

    element.addEventListener(action, listener, useCapture);
    return listener;
  } else if (element.attachEvent) {
    // Old IE browsers
    var f = function () {
      return listener.call(element, window.event);
    };
    element.attachEvent("on" + action, f);
    return f;
  }
};

/**
 * Remove an event listener from an element
 * @param {Element}  element   An html dom element
 * @param {string}   action    The name of the event, for example "mousedown"
 * @param {function} listener  The listener function
 * @param {boolean}  [useCapture]   false by default
 */
exports.removeEventListener = function removeEventListener(element, action, listener, useCapture) {
  if (element.removeEventListener) {
    if (useCapture === undefined)
      useCapture = false;

    if (action === "mousewheel" && exports.isFirefox()) {
      action = "DOMMouseScroll";  // For Firefox
    }

    element.removeEventListener(action, listener, useCapture);
  } else if (element.detachEvent) {
    // Old IE browsers
    element.detachEvent("on" + action, listener);
  }
};

/**
 * Parse a JSON path like '.items[3].name' into an array
 * @param {string} jsonPath
 * @return {Array}
 */
exports.parsePath = function parsePath(jsonPath) {
  var prop, remainder;

  if (jsonPath.length === 0) {
    return [];
  }

  // find a match like '.prop'
  var match = jsonPath.match(/^\.(\w+)/);
  if (match) {
    prop = match[1];
    remainder = jsonPath.substr(prop.length + 1);
  }
  else if (jsonPath[0] === '[') {
    // find a match like
    var end = jsonPath.indexOf(']');
    if (end === -1) {
      throw new SyntaxError('Character ] expected in path');
    }
    if (end === 1) {
      throw new SyntaxError('Index expected after [');
    }

    var value = jsonPath.substring(1, end);
    if (value[0] === '\'') {
      // ajv produces string prop names with single quotes, so we need
      // to reformat them into valid double-quoted JSON strings
      value = '\"' + value.substring(1, value.length - 1) + '\"';
    }

    prop = value === '*' ? value : JSON.parse(value); // parse string and number
    remainder = jsonPath.substr(end + 1);
  }
  else {
    throw new SyntaxError('Failed to parse path');
  }

  return [prop].concat(parsePath(remainder))
};

/**
 * Improve the error message of a JSON schema error
 * @param {Object} error
 * @return {Object} The error
 */
exports.improveSchemaError = function (error) {
  if (error.keyword === 'enum' && Array.isArray(error.schema)) {
    var enums = error.schema;
    if (enums) {
      enums = enums.map(function (value) {
        return JSON.stringify(value);
      });

      if (enums.length > 5) {
        var more = ['(' + (enums.length - 5) + ' more...)'];
        enums = enums.slice(0, 5);
        enums.push(more);
      }
      error.message = 'should be equal to one of: ' + enums.join(', ');
    }
  }

  if (error.keyword === 'additionalProperties') {
    error.message = 'should NOT have additional property: ' + error.params.additionalProperty;
  }

  return error;
};

/**
 * Test whether the child rect fits completely inside the parent rect.
 * @param {ClientRect} parent
 * @param {ClientRect} child
 * @param {number} margin
 */
exports.insideRect = function (parent, child, margin) {
  var _margin = margin !== undefined ? margin : 0;
  return child.left   - _margin >= parent.left
      && child.right  + _margin <= parent.right
      && child.top    - _margin >= parent.top
      && child.bottom + _margin <= parent.bottom;
};

/**
 * Returns a function, that, as long as it continues to be invoked, will not
 * be triggered. The function will be called after it stops being called for
 * N milliseconds.
 *
 * Source: https://davidwalsh.name/javascript-debounce-function
 *
 * @param {function} func
 * @param {number} wait                 Number in milliseconds
 * @param {boolean} [immediate=false]   If `immediate` is passed, trigger the
 *                                      function on the leading edge, instead
 *                                      of the trailing.
 * @return {function} Return the debounced function
 */
exports.debounce = function debounce(func, wait, immediate) {
  var timeout;
  return function() {
    var context = this, args = arguments;
    var later = function() {
      timeout = null;
      if (!immediate) func.apply(context, args);
    };
    var callNow = immediate && !timeout;
    clearTimeout(timeout);
    timeout = setTimeout(later, wait);
    if (callNow) func.apply(context, args);
  };
};

/**
 * Determines the difference between two texts.
 * Can only detect one removed or inserted block of characters.
 * @param {string} oldText
 * @param {string} newText
 * @return {{start: number, end: number}} Returns the start and end
 *                                        of the changed part in newText.
 */
exports.textDiff = function textDiff(oldText, newText) {
  var len = newText.length;
  var start = 0;
  var oldEnd = oldText.length;
  var newEnd = newText.length;

  while (newText.charAt(start) === oldText.charAt(start)
  && start < len) {
    start++;
  }

  while (newText.charAt(newEnd - 1) === oldText.charAt(oldEnd - 1)
  && newEnd > start && oldEnd > 0) {
    newEnd--;
    oldEnd--;
  }

  return {start: start, end: newEnd};
};


/***/ }),
/* 3 */,
/* 4 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


var util = __webpack_require__(2);

/**
 * A context menu
 * @param {Object[]} items    Array containing the menu structure
 *                            TODO: describe structure
 * @param {Object} [options]  Object with options. Available options:
 *                            {function} close    Callback called when the
 *                                                context menu is being closed.
 * @constructor
 */
function ContextMenu (items, options) {
  this.dom = {};

  var me = this;
  var dom = this.dom;
  this.anchor = undefined;
  this.items = items;
  this.eventListeners = {};
  this.selection = undefined; // holds the selection before the menu was opened
  this.onClose = options ? options.close : undefined;

  // create root element
  var root = document.createElement('div');
  root.className = 'jsoneditor-contextmenu-root';
  dom.root = root;

  // create a container element
  var menu = document.createElement('div');
  menu.className = 'jsoneditor-contextmenu';
  dom.menu = menu;
  root.appendChild(menu);

  // create a list to hold the menu items
  var list = document.createElement('ul');
  list.className = 'jsoneditor-menu';
  menu.appendChild(list);
  dom.list = list;
  dom.items = []; // list with all buttons

  // create a (non-visible) button to set the focus to the menu
  var focusButton = document.createElement('button');
  focusButton.type = 'button';
  dom.focusButton = focusButton;
  var li = document.createElement('li');
  li.style.overflow = 'hidden';
  li.style.height = '0';
  li.appendChild(focusButton);
  list.appendChild(li);

  function createMenuItems (list, domItems, items) {
    items.forEach(function (item) {
      if (item.type == 'separator') {
        // create a separator
        var separator = document.createElement('div');
        separator.className = 'jsoneditor-separator';
        li = document.createElement('li');
        li.appendChild(separator);
        list.appendChild(li);
      }
      else {
        var domItem = {};

        // create a menu item
        var li = document.createElement('li');
        list.appendChild(li);

        // create a button in the menu item
        var button = document.createElement('button');
        button.type = 'button';
        button.className = item.className;
        domItem.button = button;
        if (item.title) {
          button.title = item.title;
        }
        if (item.click) {
          button.onclick = function (event) {
            event.preventDefault();
            me.hide();
            item.click();
          };
        }
        li.appendChild(button);

        // create the contents of the button
        if (item.submenu) {
          // add the icon to the button
          var divIcon = document.createElement('div');
          divIcon.className = 'jsoneditor-icon';
          button.appendChild(divIcon);
          button.appendChild(document.createTextNode(item.text));

          var buttonSubmenu;
          if (item.click) {
            // submenu and a button with a click handler
            button.className += ' jsoneditor-default';

            var buttonExpand = document.createElement('button');
            buttonExpand.type = 'button';
            domItem.buttonExpand = buttonExpand;
            buttonExpand.className = 'jsoneditor-expand';
            buttonExpand.innerHTML = '<div class="jsoneditor-expand"></div>';
            li.appendChild(buttonExpand);
            if (item.submenuTitle) {
              buttonExpand.title = item.submenuTitle;
            }

            buttonSubmenu = buttonExpand;
          }
          else {
            // submenu and a button without a click handler
            var divExpand = document.createElement('div');
            divExpand.className = 'jsoneditor-expand';
            button.appendChild(divExpand);

            buttonSubmenu = button;
          }

          // attach a handler to expand/collapse the submenu
          buttonSubmenu.onclick = function (event) {
            event.preventDefault();
            me._onExpandItem(domItem);
            buttonSubmenu.focus();
          };

          // create the submenu
          var domSubItems = [];
          domItem.subItems = domSubItems;
          var ul = document.createElement('ul');
          domItem.ul = ul;
          ul.className = 'jsoneditor-menu';
          ul.style.height = '0';
          li.appendChild(ul);
          createMenuItems(ul, domSubItems, item.submenu);
        }
        else {
          // no submenu, just a button with clickhandler
          button.innerHTML = '<div class="jsoneditor-icon"></div>' + item.text;
        }

        domItems.push(domItem);
      }
    });
  }
  createMenuItems(list, this.dom.items, items);

  // TODO: when the editor is small, show the submenu on the right instead of inline?

  // calculate the max height of the menu with one submenu expanded
  this.maxHeight = 0; // height in pixels
  items.forEach(function (item) {
    var height = (items.length + (item.submenu ? item.submenu.length : 0)) * 24;
    me.maxHeight = Math.max(me.maxHeight, height);
  });
}

/**
 * Get the currently visible buttons
 * @return {Array.<HTMLElement>} buttons
 * @private
 */
ContextMenu.prototype._getVisibleButtons = function () {
  var buttons = [];
  var me = this;
  this.dom.items.forEach(function (item) {
    buttons.push(item.button);
    if (item.buttonExpand) {
      buttons.push(item.buttonExpand);
    }
    if (item.subItems && item == me.expandedItem) {
      item.subItems.forEach(function (subItem) {
        buttons.push(subItem.button);
        if (subItem.buttonExpand) {
          buttons.push(subItem.buttonExpand);
        }
        // TODO: change to fully recursive method
      });
    }
  });

  return buttons;
};

// currently displayed context menu, a singleton. We may only have one visible context menu
ContextMenu.visibleMenu = undefined;

/**
 * Attach the menu to an anchor
 * @param {HTMLElement} anchor          Anchor where the menu will be attached
 *                                      as sibling.
 * @param {HTMLElement} [contentWindow] The DIV with with the (scrollable) contents
 */
ContextMenu.prototype.show = function (anchor, contentWindow) {
  this.hide();

  // determine whether to display the menu below or above the anchor
  var showBelow = true;
  if (contentWindow) {
    var anchorRect = anchor.getBoundingClientRect();
    var contentRect = contentWindow.getBoundingClientRect();

    if (anchorRect.bottom + this.maxHeight < contentRect.bottom) {
      // fits below -> show below
    }
    else if (anchorRect.top - this.maxHeight > contentRect.top) {
      // fits above -> show above
      showBelow = false;
    }
    else {
      // doesn't fit above nor below -> show below
    }
  }

  // position the menu
  if (showBelow) {
    // display the menu below the anchor
    var anchorHeight = anchor.offsetHeight;
    this.dom.menu.style.left = '0px';
    this.dom.menu.style.top = anchorHeight + 'px';
    this.dom.menu.style.bottom = '';
  }
  else {
    // display the menu above the anchor
    this.dom.menu.style.left = '0px';
    this.dom.menu.style.top = '';
    this.dom.menu.style.bottom = '0px';
  }

  // attach the menu to the parent of the anchor
  var parent = anchor.parentNode;
  parent.insertBefore(this.dom.root, parent.firstChild);

  // create and attach event listeners
  var me = this;
  var list = this.dom.list;
  this.eventListeners.mousedown = util.addEventListener(window, 'mousedown', function (event) {
    // hide menu on click outside of the menu
    var target = event.target;
    if ((target != list) && !me._isChildOf(target, list)) {
      me.hide();
      event.stopPropagation();
      event.preventDefault();
    }
  });
  this.eventListeners.keydown = util.addEventListener(window, 'keydown', function (event) {
    me._onKeyDown(event);
  });

  // move focus to the first button in the context menu
  this.selection = util.getSelection();
  this.anchor = anchor;
  setTimeout(function () {
    me.dom.focusButton.focus();
  }, 0);

  if (ContextMenu.visibleMenu) {
    ContextMenu.visibleMenu.hide();
  }
  ContextMenu.visibleMenu = this;
};

/**
 * Hide the context menu if visible
 */
ContextMenu.prototype.hide = function () {
  // remove the menu from the DOM
  if (this.dom.root.parentNode) {
    this.dom.root.parentNode.removeChild(this.dom.root);
    if (this.onClose) {
      this.onClose();
    }
  }

  // remove all event listeners
  // all event listeners are supposed to be attached to document.
  for (var name in this.eventListeners) {
    if (this.eventListeners.hasOwnProperty(name)) {
      var fn = this.eventListeners[name];
      if (fn) {
        util.removeEventListener(window, name, fn);
      }
      delete this.eventListeners[name];
    }
  }

  if (ContextMenu.visibleMenu == this) {
    ContextMenu.visibleMenu = undefined;
  }
};

/**
 * Expand a submenu
 * Any currently expanded submenu will be hided.
 * @param {Object} domItem
 * @private
 */
ContextMenu.prototype._onExpandItem = function (domItem) {
  var me = this;
  var alreadyVisible = (domItem == this.expandedItem);

  // hide the currently visible submenu
  var expandedItem = this.expandedItem;
  if (expandedItem) {
    //var ul = expandedItem.ul;
    expandedItem.ul.style.height = '0';
    expandedItem.ul.style.padding = '';
    setTimeout(function () {
      if (me.expandedItem != expandedItem) {
        expandedItem.ul.style.display = '';
        util.removeClassName(expandedItem.ul.parentNode, 'jsoneditor-selected');
      }
    }, 300); // timeout duration must match the css transition duration
    this.expandedItem = undefined;
  }

  if (!alreadyVisible) {
    var ul = domItem.ul;
    ul.style.display = 'block';
    var height = ul.clientHeight; // force a reflow in Firefox
    setTimeout(function () {
      if (me.expandedItem == domItem) {
        ul.style.height = (ul.childNodes.length * 24) + 'px';
        ul.style.padding = '5px 10px';
      }
    }, 0);
    util.addClassName(ul.parentNode, 'jsoneditor-selected');
    this.expandedItem = domItem;
  }
};

/**
 * Handle onkeydown event
 * @param {Event} event
 * @private
 */
ContextMenu.prototype._onKeyDown = function (event) {
  var target = event.target;
  var keynum = event.which;
  var handled = false;
  var buttons, targetIndex, prevButton, nextButton;

  if (keynum == 27) { // ESC
    // hide the menu on ESC key

    // restore previous selection and focus
    if (this.selection) {
      util.setSelection(this.selection);
    }
    if (this.anchor) {
      this.anchor.focus();
    }

    this.hide();

    handled = true;
  }
  else if (keynum == 9) { // Tab
    if (!event.shiftKey) { // Tab
      buttons = this._getVisibleButtons();
      targetIndex = buttons.indexOf(target);
      if (targetIndex == buttons.length - 1) {
        // move to first button
        buttons[0].focus();
        handled = true;
      }
    }
    else { // Shift+Tab
      buttons = this._getVisibleButtons();
      targetIndex = buttons.indexOf(target);
      if (targetIndex == 0) {
        // move to last button
        buttons[buttons.length - 1].focus();
        handled = true;
      }
    }
  }
  else if (keynum == 37) { // Arrow Left
    if (target.className == 'jsoneditor-expand') {
      buttons = this._getVisibleButtons();
      targetIndex = buttons.indexOf(target);
      prevButton = buttons[targetIndex - 1];
      if (prevButton) {
        prevButton.focus();
      }
    }
    handled = true;
  }
  else if (keynum == 38) { // Arrow Up
    buttons = this._getVisibleButtons();
    targetIndex = buttons.indexOf(target);
    prevButton = buttons[targetIndex - 1];
    if (prevButton && prevButton.className == 'jsoneditor-expand') {
      // skip expand button
      prevButton = buttons[targetIndex - 2];
    }
    if (!prevButton) {
      // move to last button
      prevButton = buttons[buttons.length - 1];
    }
    if (prevButton) {
      prevButton.focus();
    }
    handled = true;
  }
  else if (keynum == 39) { // Arrow Right
    buttons = this._getVisibleButtons();
    targetIndex = buttons.indexOf(target);
    nextButton = buttons[targetIndex + 1];
    if (nextButton && nextButton.className == 'jsoneditor-expand') {
      nextButton.focus();
    }
    handled = true;
  }
  else if (keynum == 40) { // Arrow Down
    buttons = this._getVisibleButtons();
    targetIndex = buttons.indexOf(target);
    nextButton = buttons[targetIndex + 1];
    if (nextButton && nextButton.className == 'jsoneditor-expand') {
      // skip expand button
      nextButton = buttons[targetIndex + 2];
    }
    if (!nextButton) {
      // move to first button
      nextButton = buttons[0];
    }
    if (nextButton) {
      nextButton.focus();
      handled = true;
    }
    handled = true;
  }
  // TODO: arrow left and right

  if (handled) {
    event.stopPropagation();
    event.preventDefault();
  }
};

/**
 * Test if an element is a child of a parent element.
 * @param {Element} child
 * @param {Element} parent
 * @return {boolean} isChild
 */
ContextMenu.prototype._isChildOf = function (child, parent) {
  var e = child.parentNode;
  while (e) {
    if (e == parent) {
      return true;
    }
    e = e.parentNode;
  }

  return false;
};

module.exports = ContextMenu;


/***/ }),
/* 5 */
/***/ (function(module, exports, __webpack_require__) {

var json = typeof JSON !== 'undefined' ? JSON : __webpack_require__(107);

module.exports = function (obj, opts) {
    if (!opts) opts = {};
    if (typeof opts === 'function') opts = { cmp: opts };
    var space = opts.space || '';
    if (typeof space === 'number') space = Array(space+1).join(' ');
    var cycles = (typeof opts.cycles === 'boolean') ? opts.cycles : false;
    var replacer = opts.replacer || function(key, value) { return value; };

    var cmp = opts.cmp && (function (f) {
        return function (node) {
            return function (a, b) {
                var aobj = { key: a, value: node[a] };
                var bobj = { key: b, value: node[b] };
                return f(aobj, bobj);
            };
        };
    })(opts.cmp);

    var seen = [];
    return (function stringify (parent, key, node, level) {
        var indent = space ? ('\n' + new Array(level + 1).join(space)) : '';
        var colonSeparator = space ? ': ' : ':';

        if (node && node.toJSON && typeof node.toJSON === 'function') {
            node = node.toJSON();
        }

        node = replacer.call(parent, key, node);

        if (node === undefined) {
            return;
        }
        if (typeof node !== 'object' || node === null) {
            return json.stringify(node);
        }
        if (isArray(node)) {
            var out = [];
            for (var i = 0; i < node.length; i++) {
                var item = stringify(node, i, node[i], level+1) || json.stringify(null);
                out.push(indent + space + item);
            }
            return '[' + out.join(',') + indent + ']';
        }
        else {
            if (seen.indexOf(node) !== -1) {
                if (cycles) return json.stringify('__cycle__');
                throw new TypeError('Converting circular structure to JSON');
            }
            else seen.push(node);

            var keys = objectKeys(node).sort(cmp && cmp(node));
            var out = [];
            for (var i = 0; i < keys.length; i++) {
                var key = keys[i];
                var value = stringify(node, key, node[key], level+1);

                if(!value) continue;

                var keyValue = json.stringify(key)
                    + colonSeparator
                    + value;
                ;
                out.push(indent + space + keyValue);
            }
            seen.splice(seen.indexOf(node), 1);
            return '{' + out.join(',') + indent + '}';
        }
    })({ '': obj }, '', obj, 0);
};

var isArray = Array.isArray || function (x) {
    return {}.toString.call(x) === '[object Array]';
};

var objectKeys = Object.keys || function (obj) {
    var has = Object.prototype.hasOwnProperty || function () { return true };
    var keys = [];
    for (var key in obj) {
        if (has.call(obj, key)) keys.push(key);
    }
    return keys;
};


/***/ }),
/* 6 */
/***/ (function(module, exports) {

var g;

// This works in non-strict mode
g = (function() {
	return this;
})();

try {
	// This works if eval is allowed (see CSP)
	g = g || Function("return this")() || (1,eval)("this");
} catch(e) {
	// This works if the window reference is available
	if(typeof window === "object")
		g = window;
}

// g can still be undefined, but nothing to do about it...
// We return undefined, instead of nothing here, so it's
// easier to handle this case. if(!global) { ...}

module.exports = g;


/***/ }),
/* 7 */
/***/ (function(module, exports) {

module.exports = function(module) {
	if(!module.webpackPolyfill) {
		module.deprecate = function() {};
		module.paths = [];
		// module.parent = undefined by default
		if(!module.children) module.children = [];
		Object.defineProperty(module, "loaded", {
			enumerable: true,
			get: function() {
				return module.l;
			}
		});
		Object.defineProperty(module, "id", {
			enumerable: true,
			get: function() {
				return module.i;
			}
		});
		module.webpackPolyfill = 1;
	}
	return module;
};


/***/ }),
/* 8 */
/***/ (function(module, exports) {

function webpackEmptyContext(req) {
	throw new Error("Cannot find module '" + req + "'.");
}
webpackEmptyContext.keys = function() { return []; };
webpackEmptyContext.resolve = webpackEmptyContext;
module.exports = webpackEmptyContext;
webpackEmptyContext.id = 8;

/***/ }),
/* 9 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


module.exports = {
  setup: setupAsync,
  compile: compileAsync
};


var util = __webpack_require__(1);

var ASYNC = {
  '*': checkGenerators,
  'co*': checkGenerators,
  'es7': checkAsyncFunction
};

var TRANSPILE = {
  'nodent': getNodent,
  'regenerator': getRegenerator
};

var MODES = [
  { async: 'co*' },
  { async: 'es7', transpile: 'nodent' },
  { async: 'co*', transpile: 'regenerator' }
];


var regenerator, nodent;


function setupAsync(opts, required) {
  if (required !== false) required = true;
  var async = opts.async
    , transpile = opts.transpile
    , check;

  switch (typeof transpile) {
    case 'string':
      var get = TRANSPILE[transpile];
      if (!get) throw new Error('bad transpiler: ' + transpile);
      return (opts._transpileFunc = get(opts, required));
    case 'undefined':
    case 'boolean':
      if (typeof async == 'string') {
        check = ASYNC[async];
        if (!check) throw new Error('bad async mode: ' + async);
        return (opts.transpile = check(opts, required));
      }

      for (var i=0; i<MODES.length; i++) {
        var _opts = MODES[i];
        if (setupAsync(_opts, false)) {
          util.copy(_opts, opts);
          return opts.transpile;
        }
      }
      /* istanbul ignore next */
      throw new Error('generators, nodent and regenerator are not available');
    case 'function':
      return (opts._transpileFunc = opts.transpile);
    default:
      throw new Error('bad transpiler: ' + transpile);
  }
}


function checkGenerators(opts, required) {
  /* jshint evil: true */
  try {
    (new Function('(function*(){})()'))();
    return true;
  } catch(e) {
    /* istanbul ignore next */
    if (required) throw new Error('generators not supported');
  }
}


function checkAsyncFunction(opts, required) {
  /* jshint evil: true */
  try {
    (new Function('(async function(){})()'))();
    /* istanbul ignore next */
    return true;
  } catch(e) {
    if (required) throw new Error('es7 async functions not supported');
  }
}


function getRegenerator(opts, required) {
  try {
    if (!regenerator) {
      var name = 'regenerator';
      regenerator = !(function webpackMissingModule() { var e = new Error("Cannot find module \".\""); e.code = 'MODULE_NOT_FOUND'; throw e; }());
      regenerator.runtime();
    }
    if (!opts.async || opts.async === true)
      opts.async = 'es7';
    return regeneratorTranspile;
  } catch(e) {
    /* istanbul ignore next */
    if (required) throw new Error('regenerator not available');
  }
}


function regeneratorTranspile(code) {
  return regenerator.compile(code).code;
}


function getNodent(opts, required) {
  /* jshint evil: true */
  try {
    if (!nodent) {
      var name = 'nodent';
      nodent = !(function webpackMissingModule() { var e = new Error("Cannot find module \".\""); e.code = 'MODULE_NOT_FOUND'; throw e; }())({ log: false, dontInstallRequireHook: true });
    }
    if (opts.async != 'es7') {
      if (opts.async && opts.async !== true) console.warn('nodent transpiles only es7 async functions');
      opts.async = 'es7';
    }
    return nodentTranspile;
  } catch(e) {
    /* istanbul ignore next */
    if (required) throw new Error('nodent not available');
  }
}


function nodentTranspile(code) {
  return nodent.compile(code, '', { promises: true, sourcemap: false }).code;
}


/**
 * Creates validating function for passed schema with asynchronous loading of missing schemas.
 * `loadSchema` option should be a function that accepts schema uri and node-style callback.
 * @this  Ajv
 * @param {Object}   schema schema object
 * @param {Function} callback node-style callback, it is always called with 2 parameters: error (or null) and validating function.
 */
function compileAsync(schema, callback) {
  /* eslint no-shadow: 0 */
  /* jshint validthis: true */
  var schemaObj;
  var self = this;
  try {
    schemaObj = this._addSchema(schema);
  } catch(e) {
    setTimeout(function() { callback(e); });
    return;
  }
  if (schemaObj.validate) {
    setTimeout(function() { callback(null, schemaObj.validate); });
  } else {
    if (typeof this._opts.loadSchema != 'function')
      throw new Error('options.loadSchema should be a function');
    _compileAsync(schema, callback, true);
  }


  function _compileAsync(schema, callback, firstCall) {
    var validate;
    try { validate = self.compile(schema); }
    catch(e) {
      if (e.missingSchema) loadMissingSchema(e);
      else deferCallback(e);
      return;
    }
    deferCallback(null, validate);

    function loadMissingSchema(e) {
      var ref = e.missingSchema;
      if (self._refs[ref] || self._schemas[ref])
        return callback(new Error('Schema ' + ref + ' is loaded but ' + e.missingRef + ' cannot be resolved'));
      var _callbacks = self._loadingSchemas[ref];
      if (_callbacks) {
        if (typeof _callbacks == 'function')
          self._loadingSchemas[ref] = [_callbacks, schemaLoaded];
        else
          _callbacks[_callbacks.length] = schemaLoaded;
      } else {
        self._loadingSchemas[ref] = schemaLoaded;
        self._opts.loadSchema(ref, function (err, sch) {
          var _callbacks = self._loadingSchemas[ref];
          delete self._loadingSchemas[ref];
          if (typeof _callbacks == 'function') {
            _callbacks(err, sch);
          } else {
            for (var i=0; i<_callbacks.length; i++)
              _callbacks[i](err, sch);
          }
        });
      }

      function schemaLoaded(err, sch) {
        if (err) return callback(err);
        if (!(self._refs[ref] || self._schemas[ref])) {
          try {
            self.addSchema(sch, ref);
          } catch(e) {
            callback(e);
            return;
          }
        }
        _compileAsync(schema, callback);
      }
    }

    function deferCallback(err, validate) {
      if (firstCall) setTimeout(function() { callback(err, validate); });
      else return callback(err, validate);
    }
  }
}


/***/ }),
/* 10 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


/*eslint complexity: 0*/

module.exports = function equal(a, b) {
  if (a === b) return true;

  var arrA = Array.isArray(a)
    , arrB = Array.isArray(b)
    , i;

  if (arrA && arrB) {
    if (a.length != b.length) return false;
    for (i = 0; i < a.length; i++)
      if (!equal(a[i], b[i])) return false;
    return true;
  }

  if (arrA != arrB) return false;

  if (a && b && typeof a === 'object' && typeof b === 'object') {
    var keys = Object.keys(a);
    if (keys.length !== Object.keys(b).length) return false;

    var dateA = a instanceof Date
      , dateB = b instanceof Date;
    if (dateA && dateB) return a.getTime() == b.getTime();
    if (dateA != dateB) return false;

    var regexpA = a instanceof RegExp
      , regexpB = b instanceof RegExp;
    if (regexpA && regexpB) return a.toString() == b.toString();
    if (regexpA != regexpB) return false;

    for (i = 0; i < keys.length; i++)
      if (!Object.prototype.hasOwnProperty.call(b, keys[i])) return false;

    for (i = 0; i < keys.length; i++)
      if(!equal(a[keys[i]], b[keys[i]])) return false;

    return true;
  }

  return false;
};


/***/ }),
/* 11 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


var url = __webpack_require__(121)
  , equal = __webpack_require__(10)
  , util = __webpack_require__(1)
  , SchemaObject = __webpack_require__(12);

module.exports = resolve;

resolve.normalizeId = normalizeId;
resolve.fullPath = getFullPath;
resolve.url = resolveUrl;
resolve.ids = resolveIds;
resolve.inlineRef = inlineRef;
resolve.schema = resolveSchema;

/**
 * [resolve and compile the references ($ref)]
 * @this   Ajv
 * @param  {Function} compile reference to schema compilation funciton (localCompile)
 * @param  {Object} root object with information about the root schema for the current schema
 * @param  {String} ref reference to resolve
 * @return {Object|Function} schema object (if the schema can be inlined) or validation function
 */
function resolve(compile, root, ref) {
  /* jshint validthis: true */
  var refVal = this._refs[ref];
  if (typeof refVal == 'string') {
    if (this._refs[refVal]) refVal = this._refs[refVal];
    else return resolve.call(this, compile, root, refVal);
  }

  refVal = refVal || this._schemas[ref];
  if (refVal instanceof SchemaObject) {
    return inlineRef(refVal.schema, this._opts.inlineRefs)
            ? refVal.schema
            : refVal.validate || this._compile(refVal);
  }

  var res = resolveSchema.call(this, root, ref);
  var schema, v, baseId;
  if (res) {
    schema = res.schema;
    root = res.root;
    baseId = res.baseId;
  }

  if (schema instanceof SchemaObject) {
    v = schema.validate || compile.call(this, schema.schema, root, undefined, baseId);
  } else if (schema) {
    v = inlineRef(schema, this._opts.inlineRefs)
        ? schema
        : compile.call(this, schema, root, undefined, baseId);
  }

  return v;
}


/**
 * Resolve schema, its root and baseId
 * @this Ajv
 * @param  {Object} root root object with properties schema, refVal, refs
 * @param  {String} ref  reference to resolve
 * @return {Object} object with properties schema, root, baseId
 */
function resolveSchema(root, ref) {
  /* jshint validthis: true */
  var p = url.parse(ref, false, true)
    , refPath = _getFullPath(p)
    , baseId = getFullPath(root.schema.id);
  if (refPath !== baseId) {
    var id = normalizeId(refPath);
    var refVal = this._refs[id];
    if (typeof refVal == 'string') {
      return resolveRecursive.call(this, root, refVal, p);
    } else if (refVal instanceof SchemaObject) {
      if (!refVal.validate) this._compile(refVal);
      root = refVal;
    } else {
      refVal = this._schemas[id];
      if (refVal instanceof SchemaObject) {
        if (!refVal.validate) this._compile(refVal);
        if (id == normalizeId(ref))
          return { schema: refVal, root: root, baseId: baseId };
        root = refVal;
      } else {
        return;
      }
    }
    if (!root.schema) return;
    baseId = getFullPath(root.schema.id);
  }
  return getJsonPointer.call(this, p, baseId, root.schema, root);
}


/* @this Ajv */
function resolveRecursive(root, ref, parsedRef) {
  /* jshint validthis: true */
  var res = resolveSchema.call(this, root, ref);
  if (res) {
    var schema = res.schema;
    var baseId = res.baseId;
    root = res.root;
    if (schema.id) baseId = resolveUrl(baseId, schema.id);
    return getJsonPointer.call(this, parsedRef, baseId, schema, root);
  }
}


var PREVENT_SCOPE_CHANGE = util.toHash(['properties', 'patternProperties', 'enum', 'dependencies', 'definitions']);
/* @this Ajv */
function getJsonPointer(parsedRef, baseId, schema, root) {
  /* jshint validthis: true */
  parsedRef.hash = parsedRef.hash || '';
  if (parsedRef.hash.slice(0,2) != '#/') return;
  var parts = parsedRef.hash.split('/');

  for (var i = 1; i < parts.length; i++) {
    var part = parts[i];
    if (part) {
      part = util.unescapeFragment(part);
      schema = schema[part];
      if (!schema) break;
      if (schema.id && !PREVENT_SCOPE_CHANGE[part]) baseId = resolveUrl(baseId, schema.id);
      if (schema.$ref) {
        var $ref = resolveUrl(baseId, schema.$ref);
        var res = resolveSchema.call(this, root, $ref);
        if (res) {
          schema = res.schema;
          root = res.root;
          baseId = res.baseId;
        }
      }
    }
  }
  if (schema && schema != root.schema)
    return { schema: schema, root: root, baseId: baseId };
}


var SIMPLE_INLINED = util.toHash([
  'type', 'format', 'pattern',
  'maxLength', 'minLength',
  'maxProperties', 'minProperties',
  'maxItems', 'minItems',
  'maximum', 'minimum',
  'uniqueItems', 'multipleOf',
  'required', 'enum'
]);
function inlineRef(schema, limit) {
  if (limit === false) return false;
  if (limit === undefined || limit === true) return checkNoRef(schema);
  else if (limit) return countKeys(schema) <= limit;
}


function checkNoRef(schema) {
  var item;
  if (Array.isArray(schema)) {
    for (var i=0; i<schema.length; i++) {
      item = schema[i];
      if (typeof item == 'object' && !checkNoRef(item)) return false;
    }
  } else {
    for (var key in schema) {
      if (key == '$ref') return false;
      item = schema[key];
      if (typeof item == 'object' && !checkNoRef(item)) return false;
    }
  }
  return true;
}


function countKeys(schema) {
  var count = 0, item;
  if (Array.isArray(schema)) {
    for (var i=0; i<schema.length; i++) {
      item = schema[i];
      if (typeof item == 'object') count += countKeys(item);
      if (count == Infinity) return Infinity;
    }
  } else {
    for (var key in schema) {
      if (key == '$ref') return Infinity;
      if (SIMPLE_INLINED[key]) {
        count++;
      } else {
        item = schema[key];
        if (typeof item == 'object') count += countKeys(item) + 1;
        if (count == Infinity) return Infinity;
      }
    }
  }
  return count;
}


function getFullPath(id, normalize) {
  if (normalize !== false) id = normalizeId(id);
  var p = url.parse(id, false, true);
  return _getFullPath(p);
}


function _getFullPath(p) {
  var protocolSeparator = p.protocol || p.href.slice(0,2) == '//' ? '//' : '';
  return (p.protocol||'') + protocolSeparator + (p.host||'') + (p.path||'')  + '#';
}


var TRAILING_SLASH_HASH = /#\/?$/;
function normalizeId(id) {
  return id ? id.replace(TRAILING_SLASH_HASH, '') : '';
}


function resolveUrl(baseId, id) {
  id = normalizeId(id);
  return url.resolve(baseId, id);
}


/* @this Ajv */
function resolveIds(schema) {
  /* eslint no-shadow: 0 */
  /* jshint validthis: true */
  var id = normalizeId(schema.id);
  var localRefs = {};
  _resolveIds.call(this, schema, getFullPath(id, false), id);
  return localRefs;

  /* @this Ajv */
  function _resolveIds(schema, fullPath, baseId) {
    /* jshint validthis: true */
    if (Array.isArray(schema)) {
      for (var i=0; i<schema.length; i++)
        _resolveIds.call(this, schema[i], fullPath+'/'+i, baseId);
    } else if (schema && typeof schema == 'object') {
      if (typeof schema.id == 'string') {
        var id = baseId = baseId
                          ? url.resolve(baseId, schema.id)
                          : schema.id;
        id = normalizeId(id);

        var refVal = this._refs[id];
        if (typeof refVal == 'string') refVal = this._refs[refVal];
        if (refVal && refVal.schema) {
          if (!equal(schema, refVal.schema))
            throw new Error('id "' + id + '" resolves to more than one schema');
        } else if (id != normalizeId(fullPath)) {
          if (id[0] == '#') {
            if (localRefs[id] && !equal(schema, localRefs[id]))
              throw new Error('id "' + id + '" resolves to more than one schema');
            localRefs[id] = schema;
          } else {
            this._refs[id] = fullPath;
          }
        }
      }
      for (var key in schema)
        _resolveIds.call(this, schema[key], fullPath+'/'+util.escapeFragment(key), baseId);
    }
  }
}


/***/ }),
/* 12 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


var util = __webpack_require__(1);

module.exports = SchemaObject;

function SchemaObject(obj) {
  util.copy(obj, this);
}


/***/ }),
/* 13 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


module.exports = ValidationError;


function ValidationError(errors) {
  this.message = 'validation failed';
  this.errors = errors;
  this.ajv = this.validation = true;
}


ValidationError.prototype = Object.create(Error.prototype);
ValidationError.prototype.constructor = ValidationError;


/***/ }),
/* 14 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";

module.exports = function generate__limit(it, $keyword) {
  var out = ' ';
  var $lvl = it.level;
  var $dataLvl = it.dataLevel;
  var $schema = it.schema[$keyword];
  var $schemaPath = it.schemaPath + it.util.getProperty($keyword);
  var $errSchemaPath = it.errSchemaPath + '/' + $keyword;
  var $breakOnError = !it.opts.allErrors;
  var $errorKeyword;
  var $data = 'data' + ($dataLvl || '');
  var $isData = it.opts.v5 && $schema && $schema.$data,
    $schemaValue;
  if ($isData) {
    out += ' var schema' + ($lvl) + ' = ' + (it.util.getData($schema.$data, $dataLvl, it.dataPathArr)) + '; ';
    $schemaValue = 'schema' + $lvl;
  } else {
    $schemaValue = $schema;
  }
  var $isMax = $keyword == 'maximum',
    $exclusiveKeyword = $isMax ? 'exclusiveMaximum' : 'exclusiveMinimum',
    $schemaExcl = it.schema[$exclusiveKeyword],
    $isDataExcl = it.opts.v5 && $schemaExcl && $schemaExcl.$data,
    $op = $isMax ? '<' : '>',
    $notOp = $isMax ? '>' : '<';
  if ($isDataExcl) {
    var $schemaValueExcl = it.util.getData($schemaExcl.$data, $dataLvl, it.dataPathArr),
      $exclusive = 'exclusive' + $lvl,
      $opExpr = 'op' + $lvl,
      $opStr = '\' + ' + $opExpr + ' + \'';
    out += ' var schemaExcl' + ($lvl) + ' = ' + ($schemaValueExcl) + '; ';
    $schemaValueExcl = 'schemaExcl' + $lvl;
    out += ' var exclusive' + ($lvl) + '; if (typeof ' + ($schemaValueExcl) + ' != \'boolean\' && typeof ' + ($schemaValueExcl) + ' != \'undefined\') { ';
    var $errorKeyword = $exclusiveKeyword;
    var $$outStack = $$outStack || [];
    $$outStack.push(out);
    out = ''; /* istanbul ignore else */
    if (it.createErrors !== false) {
      out += ' { keyword: \'' + ($errorKeyword || '_exclusiveLimit') + '\' , dataPath: (dataPath || \'\') + ' + (it.errorPath) + ' , schemaPath: ' + (it.util.toQuotedString($errSchemaPath)) + ' , params: {} ';
      if (it.opts.messages !== false) {
        out += ' , message: \'' + ($exclusiveKeyword) + ' should be boolean\' ';
      }
      if (it.opts.verbose) {
        out += ' , schema: validate.schema' + ($schemaPath) + ' , parentSchema: validate.schema' + (it.schemaPath) + ' , data: ' + ($data) + ' ';
      }
      out += ' } ';
    } else {
      out += ' {} ';
    }
    var __err = out;
    out = $$outStack.pop();
    if (!it.compositeRule && $breakOnError) { /* istanbul ignore if */
      if (it.async) {
        out += ' throw new ValidationError([' + (__err) + ']); ';
      } else {
        out += ' validate.errors = [' + (__err) + ']; return false; ';
      }
    } else {
      out += ' var err = ' + (__err) + ';  if (vErrors === null) vErrors = [err]; else vErrors.push(err); errors++; ';
    }
    out += ' } else if( ';
    if ($isData) {
      out += ' (' + ($schemaValue) + ' !== undefined && typeof ' + ($schemaValue) + ' != \'number\') || ';
    }
    out += ' ((exclusive' + ($lvl) + ' = ' + ($schemaValueExcl) + ' === true) ? ' + ($data) + ' ' + ($notOp) + '= ' + ($schemaValue) + ' : ' + ($data) + ' ' + ($notOp) + ' ' + ($schemaValue) + ') || ' + ($data) + ' !== ' + ($data) + ') { var op' + ($lvl) + ' = exclusive' + ($lvl) + ' ? \'' + ($op) + '\' : \'' + ($op) + '=\';';
  } else {
    var $exclusive = $schemaExcl === true,
      $opStr = $op;
    if (!$exclusive) $opStr += '=';
    var $opExpr = '\'' + $opStr + '\'';
    out += ' if ( ';
    if ($isData) {
      out += ' (' + ($schemaValue) + ' !== undefined && typeof ' + ($schemaValue) + ' != \'number\') || ';
    }
    out += ' ' + ($data) + ' ' + ($notOp);
    if ($exclusive) {
      out += '=';
    }
    out += ' ' + ($schemaValue) + ' || ' + ($data) + ' !== ' + ($data) + ') {';
  }
  var $errorKeyword = $keyword;
  var $$outStack = $$outStack || [];
  $$outStack.push(out);
  out = ''; /* istanbul ignore else */
  if (it.createErrors !== false) {
    out += ' { keyword: \'' + ($errorKeyword || '_limit') + '\' , dataPath: (dataPath || \'\') + ' + (it.errorPath) + ' , schemaPath: ' + (it.util.toQuotedString($errSchemaPath)) + ' , params: { comparison: ' + ($opExpr) + ', limit: ' + ($schemaValue) + ', exclusive: ' + ($exclusive) + ' } ';
    if (it.opts.messages !== false) {
      out += ' , message: \'should be ' + ($opStr) + ' ';
      if ($isData) {
        out += '\' + ' + ($schemaValue);
      } else {
        out += '' + ($schema) + '\'';
      }
    }
    if (it.opts.verbose) {
      out += ' , schema:  ';
      if ($isData) {
        out += 'validate.schema' + ($schemaPath);
      } else {
        out += '' + ($schema);
      }
      out += '         , parentSchema: validate.schema' + (it.schemaPath) + ' , data: ' + ($data) + ' ';
    }
    out += ' } ';
  } else {
    out += ' {} ';
  }
  var __err = out;
  out = $$outStack.pop();
  if (!it.compositeRule && $breakOnError) { /* istanbul ignore if */
    if (it.async) {
      out += ' throw new ValidationError([' + (__err) + ']); ';
    } else {
      out += ' validate.errors = [' + (__err) + ']; return false; ';
    }
  } else {
    out += ' var err = ' + (__err) + ';  if (vErrors === null) vErrors = [err]; else vErrors.push(err); errors++; ';
  }
  out += ' } ';
  if ($breakOnError) {
    out += ' else { ';
  }
  return out;
}


/***/ }),
/* 15 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";

module.exports = function generate__limitItems(it, $keyword) {
  var out = ' ';
  var $lvl = it.level;
  var $dataLvl = it.dataLevel;
  var $schema = it.schema[$keyword];
  var $schemaPath = it.schemaPath + it.util.getProperty($keyword);
  var $errSchemaPath = it.errSchemaPath + '/' + $keyword;
  var $breakOnError = !it.opts.allErrors;
  var $errorKeyword;
  var $data = 'data' + ($dataLvl || '');
  var $isData = it.opts.v5 && $schema && $schema.$data,
    $schemaValue;
  if ($isData) {
    out += ' var schema' + ($lvl) + ' = ' + (it.util.getData($schema.$data, $dataLvl, it.dataPathArr)) + '; ';
    $schemaValue = 'schema' + $lvl;
  } else {
    $schemaValue = $schema;
  }
  var $op = $keyword == 'maxItems' ? '>' : '<';
  out += 'if ( ';
  if ($isData) {
    out += ' (' + ($schemaValue) + ' !== undefined && typeof ' + ($schemaValue) + ' != \'number\') || ';
  }
  out += ' ' + ($data) + '.length ' + ($op) + ' ' + ($schemaValue) + ') { ';
  var $errorKeyword = $keyword;
  var $$outStack = $$outStack || [];
  $$outStack.push(out);
  out = ''; /* istanbul ignore else */
  if (it.createErrors !== false) {
    out += ' { keyword: \'' + ($errorKeyword || '_limitItems') + '\' , dataPath: (dataPath || \'\') + ' + (it.errorPath) + ' , schemaPath: ' + (it.util.toQuotedString($errSchemaPath)) + ' , params: { limit: ' + ($schemaValue) + ' } ';
    if (it.opts.messages !== false) {
      out += ' , message: \'should NOT have ';
      if ($keyword == 'maxItems') {
        out += 'more';
      } else {
        out += 'less';
      }
      out += ' than ';
      if ($isData) {
        out += '\' + ' + ($schemaValue) + ' + \'';
      } else {
        out += '' + ($schema);
      }
      out += ' items\' ';
    }
    if (it.opts.verbose) {
      out += ' , schema:  ';
      if ($isData) {
        out += 'validate.schema' + ($schemaPath);
      } else {
        out += '' + ($schema);
      }
      out += '         , parentSchema: validate.schema' + (it.schemaPath) + ' , data: ' + ($data) + ' ';
    }
    out += ' } ';
  } else {
    out += ' {} ';
  }
  var __err = out;
  out = $$outStack.pop();
  if (!it.compositeRule && $breakOnError) { /* istanbul ignore if */
    if (it.async) {
      out += ' throw new ValidationError([' + (__err) + ']); ';
    } else {
      out += ' validate.errors = [' + (__err) + ']; return false; ';
    }
  } else {
    out += ' var err = ' + (__err) + ';  if (vErrors === null) vErrors = [err]; else vErrors.push(err); errors++; ';
  }
  out += '} ';
  if ($breakOnError) {
    out += ' else { ';
  }
  return out;
}


/***/ }),
/* 16 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";

module.exports = function generate__limitLength(it, $keyword) {
  var out = ' ';
  var $lvl = it.level;
  var $dataLvl = it.dataLevel;
  var $schema = it.schema[$keyword];
  var $schemaPath = it.schemaPath + it.util.getProperty($keyword);
  var $errSchemaPath = it.errSchemaPath + '/' + $keyword;
  var $breakOnError = !it.opts.allErrors;
  var $errorKeyword;
  var $data = 'data' + ($dataLvl || '');
  var $isData = it.opts.v5 && $schema && $schema.$data,
    $schemaValue;
  if ($isData) {
    out += ' var schema' + ($lvl) + ' = ' + (it.util.getData($schema.$data, $dataLvl, it.dataPathArr)) + '; ';
    $schemaValue = 'schema' + $lvl;
  } else {
    $schemaValue = $schema;
  }
  var $op = $keyword == 'maxLength' ? '>' : '<';
  out += 'if ( ';
  if ($isData) {
    out += ' (' + ($schemaValue) + ' !== undefined && typeof ' + ($schemaValue) + ' != \'number\') || ';
  }
  if (it.opts.unicode === false) {
    out += ' ' + ($data) + '.length ';
  } else {
    out += ' ucs2length(' + ($data) + ') ';
  }
  out += ' ' + ($op) + ' ' + ($schemaValue) + ') { ';
  var $errorKeyword = $keyword;
  var $$outStack = $$outStack || [];
  $$outStack.push(out);
  out = ''; /* istanbul ignore else */
  if (it.createErrors !== false) {
    out += ' { keyword: \'' + ($errorKeyword || '_limitLength') + '\' , dataPath: (dataPath || \'\') + ' + (it.errorPath) + ' , schemaPath: ' + (it.util.toQuotedString($errSchemaPath)) + ' , params: { limit: ' + ($schemaValue) + ' } ';
    if (it.opts.messages !== false) {
      out += ' , message: \'should NOT be ';
      if ($keyword == 'maxLength') {
        out += 'longer';
      } else {
        out += 'shorter';
      }
      out += ' than ';
      if ($isData) {
        out += '\' + ' + ($schemaValue) + ' + \'';
      } else {
        out += '' + ($schema);
      }
      out += ' characters\' ';
    }
    if (it.opts.verbose) {
      out += ' , schema:  ';
      if ($isData) {
        out += 'validate.schema' + ($schemaPath);
      } else {
        out += '' + ($schema);
      }
      out += '         , parentSchema: validate.schema' + (it.schemaPath) + ' , data: ' + ($data) + ' ';
    }
    out += ' } ';
  } else {
    out += ' {} ';
  }
  var __err = out;
  out = $$outStack.pop();
  if (!it.compositeRule && $breakOnError) { /* istanbul ignore if */
    if (it.async) {
      out += ' throw new ValidationError([' + (__err) + ']); ';
    } else {
      out += ' validate.errors = [' + (__err) + ']; return false; ';
    }
  } else {
    out += ' var err = ' + (__err) + ';  if (vErrors === null) vErrors = [err]; else vErrors.push(err); errors++; ';
  }
  out += '} ';
  if ($breakOnError) {
    out += ' else { ';
  }
  return out;
}


/***/ }),
/* 17 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";

module.exports = function generate__limitProperties(it, $keyword) {
  var out = ' ';
  var $lvl = it.level;
  var $dataLvl = it.dataLevel;
  var $schema = it.schema[$keyword];
  var $schemaPath = it.schemaPath + it.util.getProperty($keyword);
  var $errSchemaPath = it.errSchemaPath + '/' + $keyword;
  var $breakOnError = !it.opts.allErrors;
  var $errorKeyword;
  var $data = 'data' + ($dataLvl || '');
  var $isData = it.opts.v5 && $schema && $schema.$data,
    $schemaValue;
  if ($isData) {
    out += ' var schema' + ($lvl) + ' = ' + (it.util.getData($schema.$data, $dataLvl, it.dataPathArr)) + '; ';
    $schemaValue = 'schema' + $lvl;
  } else {
    $schemaValue = $schema;
  }
  var $op = $keyword == 'maxProperties' ? '>' : '<';
  out += 'if ( ';
  if ($isData) {
    out += ' (' + ($schemaValue) + ' !== undefined && typeof ' + ($schemaValue) + ' != \'number\') || ';
  }
  out += ' Object.keys(' + ($data) + ').length ' + ($op) + ' ' + ($schemaValue) + ') { ';
  var $errorKeyword = $keyword;
  var $$outStack = $$outStack || [];
  $$outStack.push(out);
  out = ''; /* istanbul ignore else */
  if (it.createErrors !== false) {
    out += ' { keyword: \'' + ($errorKeyword || '_limitProperties') + '\' , dataPath: (dataPath || \'\') + ' + (it.errorPath) + ' , schemaPath: ' + (it.util.toQuotedString($errSchemaPath)) + ' , params: { limit: ' + ($schemaValue) + ' } ';
    if (it.opts.messages !== false) {
      out += ' , message: \'should NOT have ';
      if ($keyword == 'maxProperties') {
        out += 'more';
      } else {
        out += 'less';
      }
      out += ' than ';
      if ($isData) {
        out += '\' + ' + ($schemaValue) + ' + \'';
      } else {
        out += '' + ($schema);
      }
      out += ' properties\' ';
    }
    if (it.opts.verbose) {
      out += ' , schema:  ';
      if ($isData) {
        out += 'validate.schema' + ($schemaPath);
      } else {
        out += '' + ($schema);
      }
      out += '         , parentSchema: validate.schema' + (it.schemaPath) + ' , data: ' + ($data) + ' ';
    }
    out += ' } ';
  } else {
    out += ' {} ';
  }
  var __err = out;
  out = $$outStack.pop();
  if (!it.compositeRule && $breakOnError) { /* istanbul ignore if */
    if (it.async) {
      out += ' throw new ValidationError([' + (__err) + ']); ';
    } else {
      out += ' validate.errors = [' + (__err) + ']; return false; ';
    }
  } else {
    out += ' var err = ' + (__err) + ';  if (vErrors === null) vErrors = [err]; else vErrors.push(err); errors++; ';
  }
  out += '} ';
  if ($breakOnError) {
    out += ' else { ';
  }
  return out;
}


/***/ }),
/* 18 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";

module.exports = function generate_validate(it, $keyword) {
  var out = '';
  var $async = it.schema.$async === true;
  if (it.isTop) {
    var $top = it.isTop,
      $lvl = it.level = 0,
      $dataLvl = it.dataLevel = 0,
      $data = 'data';
    it.rootId = it.resolve.fullPath(it.root.schema.id);
    it.baseId = it.baseId || it.rootId;
    if ($async) {
      it.async = true;
      var $es7 = it.opts.async == 'es7';
      it.yieldAwait = $es7 ? 'await' : 'yield';
    }
    delete it.isTop;
    it.dataPathArr = [undefined];
    out += ' var validate = ';
    if ($async) {
      if ($es7) {
        out += ' (async function ';
      } else {
        if (it.opts.async == 'co*') {
          out += 'co.wrap';
        }
        out += '(function* ';
      }
    } else {
      out += ' (function ';
    }
    out += ' (data, dataPath, parentData, parentDataProperty, rootData) { \'use strict\'; var vErrors = null; ';
    out += ' var errors = 0;     ';
    out += ' if (rootData === undefined) rootData = data;';
  } else {
    var $lvl = it.level,
      $dataLvl = it.dataLevel,
      $data = 'data' + ($dataLvl || '');
    if (it.schema.id) it.baseId = it.resolve.url(it.baseId, it.schema.id);
    if ($async && !it.async) throw new Error('async schema in sync schema');
    out += ' var errs_' + ($lvl) + ' = errors;';
  }
  var $valid = 'valid' + $lvl,
    $breakOnError = !it.opts.allErrors,
    $closingBraces1 = '',
    $closingBraces2 = '';
  var $typeSchema = it.schema.type,
    $typeIsArray = Array.isArray($typeSchema);
  if ($typeSchema && it.opts.coerceTypes) {
    var $coerceToTypes = it.util.coerceToTypes(it.opts.coerceTypes, $typeSchema);
    if ($coerceToTypes) {
      var $schemaPath = it.schemaPath + '.type',
        $errSchemaPath = it.errSchemaPath + '/type',
        $method = $typeIsArray ? 'checkDataTypes' : 'checkDataType';
      out += ' if (' + (it.util[$method]($typeSchema, $data, true)) + ') {  ';
      var $dataType = 'dataType' + $lvl,
        $coerced = 'coerced' + $lvl;
      out += ' var ' + ($dataType) + ' = typeof ' + ($data) + '; ';
      if (it.opts.coerceTypes == 'array') {
        out += ' if (' + ($dataType) + ' == \'object\' && Array.isArray(' + ($data) + ')) ' + ($dataType) + ' = \'array\'; ';
      }
      out += ' var ' + ($coerced) + ' = undefined; ';
      var $bracesCoercion = '';
      var arr1 = $coerceToTypes;
      if (arr1) {
        var $type, $i = -1,
          l1 = arr1.length - 1;
        while ($i < l1) {
          $type = arr1[$i += 1];
          if ($i) {
            out += ' if (' + ($coerced) + ' === undefined) { ';
            $bracesCoercion += '}';
          }
          if (it.opts.coerceTypes == 'array' && $type != 'array') {
            out += ' if (' + ($dataType) + ' == \'array\' && ' + ($data) + '.length == 1) { ' + ($coerced) + ' = ' + ($data) + ' = ' + ($data) + '[0]; ' + ($dataType) + ' = typeof ' + ($data) + ';  } ';
          }
          if ($type == 'string') {
            out += ' if (' + ($dataType) + ' == \'number\' || ' + ($dataType) + ' == \'boolean\') ' + ($coerced) + ' = \'\' + ' + ($data) + '; else if (' + ($data) + ' === null) ' + ($coerced) + ' = \'\'; ';
          } else if ($type == 'number' || $type == 'integer') {
            out += ' if (' + ($dataType) + ' == \'boolean\' || ' + ($data) + ' === null || (' + ($dataType) + ' == \'string\' && ' + ($data) + ' && ' + ($data) + ' == +' + ($data) + ' ';
            if ($type == 'integer') {
              out += ' && !(' + ($data) + ' % 1)';
            }
            out += ')) ' + ($coerced) + ' = +' + ($data) + '; ';
          } else if ($type == 'boolean') {
            out += ' if (' + ($data) + ' === \'false\' || ' + ($data) + ' === 0 || ' + ($data) + ' === null) ' + ($coerced) + ' = false; else if (' + ($data) + ' === \'true\' || ' + ($data) + ' === 1) ' + ($coerced) + ' = true; ';
          } else if ($type == 'null') {
            out += ' if (' + ($data) + ' === \'\' || ' + ($data) + ' === 0 || ' + ($data) + ' === false) ' + ($coerced) + ' = null; ';
          } else if (it.opts.coerceTypes == 'array' && $type == 'array') {
            out += ' if (' + ($dataType) + ' == \'string\' || ' + ($dataType) + ' == \'number\' || ' + ($dataType) + ' == \'boolean\' || ' + ($data) + ' == null) ' + ($coerced) + ' = [' + ($data) + ']; ';
          }
        }
      }
      out += ' ' + ($bracesCoercion) + ' if (' + ($coerced) + ' === undefined) {   ';
      var $$outStack = $$outStack || [];
      $$outStack.push(out);
      out = ''; /* istanbul ignore else */
      if (it.createErrors !== false) {
        out += ' { keyword: \'' + ('type') + '\' , dataPath: (dataPath || \'\') + ' + (it.errorPath) + ' , schemaPath: ' + (it.util.toQuotedString($errSchemaPath)) + ' , params: { type: \'';
        if ($typeIsArray) {
          out += '' + ($typeSchema.join(","));
        } else {
          out += '' + ($typeSchema);
        }
        out += '\' } ';
        if (it.opts.messages !== false) {
          out += ' , message: \'should be ';
          if ($typeIsArray) {
            out += '' + ($typeSchema.join(","));
          } else {
            out += '' + ($typeSchema);
          }
          out += '\' ';
        }
        if (it.opts.verbose) {
          out += ' , schema: validate.schema' + ($schemaPath) + ' , parentSchema: validate.schema' + (it.schemaPath) + ' , data: ' + ($data) + ' ';
        }
        out += ' } ';
      } else {
        out += ' {} ';
      }
      var __err = out;
      out = $$outStack.pop();
      if (!it.compositeRule && $breakOnError) { /* istanbul ignore if */
        if (it.async) {
          out += ' throw new ValidationError([' + (__err) + ']); ';
        } else {
          out += ' validate.errors = [' + (__err) + ']; return false; ';
        }
      } else {
        out += ' var err = ' + (__err) + ';  if (vErrors === null) vErrors = [err]; else vErrors.push(err); errors++; ';
      }
      out += ' } else {  ';
      var $parentData = $dataLvl ? 'data' + (($dataLvl - 1) || '') : 'parentData',
        $parentDataProperty = $dataLvl ? it.dataPathArr[$dataLvl] : 'parentDataProperty';
      out += ' ' + ($data) + ' = ' + ($coerced) + '; ';
      if (!$dataLvl) {
        out += 'if (' + ($parentData) + ' !== undefined)';
      }
      out += ' ' + ($parentData) + '[' + ($parentDataProperty) + '] = ' + ($coerced) + '; } } ';
    }
  }
  var $refKeywords;
  if (it.schema.$ref && ($refKeywords = it.util.schemaHasRulesExcept(it.schema, it.RULES.all, '$ref'))) {
    if (it.opts.extendRefs == 'fail') {
      throw new Error('$ref: validation keywords used in schema at path "' + it.errSchemaPath + '"');
    } else if (it.opts.extendRefs == 'ignore') {
      $refKeywords = false;
      console.log('$ref: keywords ignored in schema at path "' + it.errSchemaPath + '"');
    } else if (it.opts.extendRefs !== true) {
      console.log('$ref: all keywords used in schema at path "' + it.errSchemaPath + '". It will change in the next major version, see issue #260. Use option { extendRefs: true } to keep current behaviour');
    }
  }
  if (it.schema.$ref && !$refKeywords) {
    out += ' ' + (it.RULES.all.$ref.code(it, '$ref')) + ' ';
    if ($breakOnError) {
      out += ' } if (errors === ';
      if ($top) {
        out += '0';
      } else {
        out += 'errs_' + ($lvl);
      }
      out += ') { ';
      $closingBraces2 += '}';
    }
  } else {
    var arr2 = it.RULES;
    if (arr2) {
      var $rulesGroup, i2 = -1,
        l2 = arr2.length - 1;
      while (i2 < l2) {
        $rulesGroup = arr2[i2 += 1];
        if ($shouldUseGroup($rulesGroup)) {
          if ($rulesGroup.type) {
            out += ' if (' + (it.util.checkDataType($rulesGroup.type, $data)) + ') { ';
          }
          if (it.opts.useDefaults && !it.compositeRule) {
            if ($rulesGroup.type == 'object' && it.schema.properties) {
              var $schema = it.schema.properties,
                $schemaKeys = Object.keys($schema);
              var arr3 = $schemaKeys;
              if (arr3) {
                var $propertyKey, i3 = -1,
                  l3 = arr3.length - 1;
                while (i3 < l3) {
                  $propertyKey = arr3[i3 += 1];
                  var $sch = $schema[$propertyKey];
                  if ($sch.default !== undefined) {
                    var $passData = $data + it.util.getProperty($propertyKey);
                    out += '  if (' + ($passData) + ' === undefined) ' + ($passData) + ' = ';
                    if (it.opts.useDefaults == 'shared') {
                      out += ' ' + (it.useDefault($sch.default)) + ' ';
                    } else {
                      out += ' ' + (JSON.stringify($sch.default)) + ' ';
                    }
                    out += '; ';
                  }
                }
              }
            } else if ($rulesGroup.type == 'array' && Array.isArray(it.schema.items)) {
              var arr4 = it.schema.items;
              if (arr4) {
                var $sch, $i = -1,
                  l4 = arr4.length - 1;
                while ($i < l4) {
                  $sch = arr4[$i += 1];
                  if ($sch.default !== undefined) {
                    var $passData = $data + '[' + $i + ']';
                    out += '  if (' + ($passData) + ' === undefined) ' + ($passData) + ' = ';
                    if (it.opts.useDefaults == 'shared') {
                      out += ' ' + (it.useDefault($sch.default)) + ' ';
                    } else {
                      out += ' ' + (JSON.stringify($sch.default)) + ' ';
                    }
                    out += '; ';
                  }
                }
              }
            }
          }
          var arr5 = $rulesGroup.rules;
          if (arr5) {
            var $rule, i5 = -1,
              l5 = arr5.length - 1;
            while (i5 < l5) {
              $rule = arr5[i5 += 1];
              if ($shouldUseRule($rule)) {
                out += ' ' + ($rule.code(it, $rule.keyword)) + ' ';
                if ($breakOnError) {
                  $closingBraces1 += '}';
                }
              }
            }
          }
          if ($breakOnError) {
            out += ' ' + ($closingBraces1) + ' ';
            $closingBraces1 = '';
          }
          if ($rulesGroup.type) {
            out += ' } ';
            if ($typeSchema && $typeSchema === $rulesGroup.type) {
              var $typeChecked = true;
              out += ' else { ';
              var $schemaPath = it.schemaPath + '.type',
                $errSchemaPath = it.errSchemaPath + '/type';
              var $$outStack = $$outStack || [];
              $$outStack.push(out);
              out = ''; /* istanbul ignore else */
              if (it.createErrors !== false) {
                out += ' { keyword: \'' + ('type') + '\' , dataPath: (dataPath || \'\') + ' + (it.errorPath) + ' , schemaPath: ' + (it.util.toQuotedString($errSchemaPath)) + ' , params: { type: \'';
                if ($typeIsArray) {
                  out += '' + ($typeSchema.join(","));
                } else {
                  out += '' + ($typeSchema);
                }
                out += '\' } ';
                if (it.opts.messages !== false) {
                  out += ' , message: \'should be ';
                  if ($typeIsArray) {
                    out += '' + ($typeSchema.join(","));
                  } else {
                    out += '' + ($typeSchema);
                  }
                  out += '\' ';
                }
                if (it.opts.verbose) {
                  out += ' , schema: validate.schema' + ($schemaPath) + ' , parentSchema: validate.schema' + (it.schemaPath) + ' , data: ' + ($data) + ' ';
                }
                out += ' } ';
              } else {
                out += ' {} ';
              }
              var __err = out;
              out = $$outStack.pop();
              if (!it.compositeRule && $breakOnError) { /* istanbul ignore if */
                if (it.async) {
                  out += ' throw new ValidationError([' + (__err) + ']); ';
                } else {
                  out += ' validate.errors = [' + (__err) + ']; return false; ';
                }
              } else {
                out += ' var err = ' + (__err) + ';  if (vErrors === null) vErrors = [err]; else vErrors.push(err); errors++; ';
              }
              out += ' } ';
            }
          }
          if ($breakOnError) {
            out += ' if (errors === ';
            if ($top) {
              out += '0';
            } else {
              out += 'errs_' + ($lvl);
            }
            out += ') { ';
            $closingBraces2 += '}';
          }
        }
      }
    }
  }
  if ($typeSchema && !$typeChecked && !(it.opts.coerceTypes && $coerceToTypes)) {
    var $schemaPath = it.schemaPath + '.type',
      $errSchemaPath = it.errSchemaPath + '/type',
      $method = $typeIsArray ? 'checkDataTypes' : 'checkDataType';
    out += ' if (' + (it.util[$method]($typeSchema, $data, true)) + ') {   ';
    var $$outStack = $$outStack || [];
    $$outStack.push(out);
    out = ''; /* istanbul ignore else */
    if (it.createErrors !== false) {
      out += ' { keyword: \'' + ('type') + '\' , dataPath: (dataPath || \'\') + ' + (it.errorPath) + ' , schemaPath: ' + (it.util.toQuotedString($errSchemaPath)) + ' , params: { type: \'';
      if ($typeIsArray) {
        out += '' + ($typeSchema.join(","));
      } else {
        out += '' + ($typeSchema);
      }
      out += '\' } ';
      if (it.opts.messages !== false) {
        out += ' , message: \'should be ';
        if ($typeIsArray) {
          out += '' + ($typeSchema.join(","));
        } else {
          out += '' + ($typeSchema);
        }
        out += '\' ';
      }
      if (it.opts.verbose) {
        out += ' , schema: validate.schema' + ($schemaPath) + ' , parentSchema: validate.schema' + (it.schemaPath) + ' , data: ' + ($data) + ' ';
      }
      out += ' } ';
    } else {
      out += ' {} ';
    }
    var __err = out;
    out = $$outStack.pop();
    if (!it.compositeRule && $breakOnError) { /* istanbul ignore if */
      if (it.async) {
        out += ' throw new ValidationError([' + (__err) + ']); ';
      } else {
        out += ' validate.errors = [' + (__err) + ']; return false; ';
      }
    } else {
      out += ' var err = ' + (__err) + ';  if (vErrors === null) vErrors = [err]; else vErrors.push(err); errors++; ';
    }
    out += ' }';
  }
  if ($breakOnError) {
    out += ' ' + ($closingBraces2) + ' ';
  }
  if ($top) {
    if ($async) {
      out += ' if (errors === 0) return true;           ';
      out += ' else throw new ValidationError(vErrors); ';
    } else {
      out += ' validate.errors = vErrors; ';
      out += ' return errors === 0;       ';
    }
    out += ' }); return validate;';
  } else {
    out += ' var ' + ($valid) + ' = errors === errs_' + ($lvl) + ';';
  }
  out = it.util.cleanUpCode(out);
  if ($top && $breakOnError) {
    out = it.util.cleanUpVarErrors(out, $async);
  }

  function $shouldUseGroup($rulesGroup) {
    for (var i = 0; i < $rulesGroup.rules.length; i++)
      if ($shouldUseRule($rulesGroup.rules[i])) return true;
  }

  function $shouldUseRule($rule) {
    return it.schema[$rule.keyword] !== undefined || ($rule.keyword == 'properties' && (it.schema.additionalProperties === false || typeof it.schema.additionalProperties == 'object' || (it.schema.patternProperties && Object.keys(it.schema.patternProperties).length) || (it.opts.v5 && it.schema.patternGroups && Object.keys(it.schema.patternGroups).length)));
  }
  return out;
}


/***/ }),
/* 19 */
/***/ (function(module, exports) {


/**
 * slice() reference.
 */

var slice = Array.prototype.slice;

/**
 * Expose `co`.
 */

module.exports = co['default'] = co.co = co;

/**
 * Wrap the given generator `fn` into a
 * function that returns a promise.
 * This is a separate function so that
 * every `co()` call doesn't create a new,
 * unnecessary closure.
 *
 * @param {GeneratorFunction} fn
 * @return {Function}
 * @api public
 */

co.wrap = function (fn) {
  createPromise.__generatorFunction__ = fn;
  return createPromise;
  function createPromise() {
    return co.call(this, fn.apply(this, arguments));
  }
};

/**
 * Execute the generator function or a generator
 * and return a promise.
 *
 * @param {Function} fn
 * @return {Promise}
 * @api public
 */

function co(gen) {
  var ctx = this;
  var args = slice.call(arguments, 1)

  // we wrap everything in a promise to avoid promise chaining,
  // which leads to memory leak errors.
  // see https://github.com/tj/co/issues/180
  return new Promise(function(resolve, reject) {
    if (typeof gen === 'function') gen = gen.apply(ctx, args);
    if (!gen || typeof gen.next !== 'function') return resolve(gen);

    onFulfilled();

    /**
     * @param {Mixed} res
     * @return {Promise}
     * @api private
     */

    function onFulfilled(res) {
      var ret;
      try {
        ret = gen.next(res);
      } catch (e) {
        return reject(e);
      }
      next(ret);
    }

    /**
     * @param {Error} err
     * @return {Promise}
     * @api private
     */

    function onRejected(err) {
      var ret;
      try {
        ret = gen.throw(err);
      } catch (e) {
        return reject(e);
      }
      next(ret);
    }

    /**
     * Get the next value in the generator,
     * return a promise.
     *
     * @param {Object} ret
     * @return {Promise}
     * @api private
     */

    function next(ret) {
      if (ret.done) return resolve(ret.value);
      var value = toPromise.call(ctx, ret.value);
      if (value && isPromise(value)) return value.then(onFulfilled, onRejected);
      return onRejected(new TypeError('You may only yield a function, promise, generator, array, or object, '
        + 'but the following object was passed: "' + String(ret.value) + '"'));
    }
  });
}

/**
 * Convert a `yield`ed value into a promise.
 *
 * @param {Mixed} obj
 * @return {Promise}
 * @api private
 */

function toPromise(obj) {
  if (!obj) return obj;
  if (isPromise(obj)) return obj;
  if (isGeneratorFunction(obj) || isGenerator(obj)) return co.call(this, obj);
  if ('function' == typeof obj) return thunkToPromise.call(this, obj);
  if (Array.isArray(obj)) return arrayToPromise.call(this, obj);
  if (isObject(obj)) return objectToPromise.call(this, obj);
  return obj;
}

/**
 * Convert a thunk to a promise.
 *
 * @param {Function}
 * @return {Promise}
 * @api private
 */

function thunkToPromise(fn) {
  var ctx = this;
  return new Promise(function (resolve, reject) {
    fn.call(ctx, function (err, res) {
      if (err) return reject(err);
      if (arguments.length > 2) res = slice.call(arguments, 1);
      resolve(res);
    });
  });
}

/**
 * Convert an array of "yieldables" to a promise.
 * Uses `Promise.all()` internally.
 *
 * @param {Array} obj
 * @return {Promise}
 * @api private
 */

function arrayToPromise(obj) {
  return Promise.all(obj.map(toPromise, this));
}

/**
 * Convert an object of "yieldables" to a promise.
 * Uses `Promise.all()` internally.
 *
 * @param {Object} obj
 * @return {Promise}
 * @api private
 */

function objectToPromise(obj){
  var results = new obj.constructor();
  var keys = Object.keys(obj);
  var promises = [];
  for (var i = 0; i < keys.length; i++) {
    var key = keys[i];
    var promise = toPromise.call(this, obj[key]);
    if (promise && isPromise(promise)) defer(promise, key);
    else results[key] = obj[key];
  }
  return Promise.all(promises).then(function () {
    return results;
  });

  function defer(promise, key) {
    // predefine the key in the result
    results[key] = undefined;
    promises.push(promise.then(function (res) {
      results[key] = res;
    }));
  }
}

/**
 * Check if `obj` is a promise.
 *
 * @param {Object} obj
 * @return {Boolean}
 * @api private
 */

function isPromise(obj) {
  return 'function' == typeof obj.then;
}

/**
 * Check if `obj` is a generator.
 *
 * @param {Mixed} obj
 * @return {Boolean}
 * @api private
 */

function isGenerator(obj) {
  return 'function' == typeof obj.next && 'function' == typeof obj.throw;
}

/**
 * Check if `obj` is a generator function.
 *
 * @param {Mixed} obj
 * @return {Boolean}
 * @api private
 */
function isGeneratorFunction(obj) {
  var constructor = obj.constructor;
  if (!constructor) return false;
  if ('GeneratorFunction' === constructor.name || 'GeneratorFunction' === constructor.displayName) return true;
  return isGenerator(constructor.prototype);
}

/**
 * Check for plain object.
 *
 * @param {Mixed} val
 * @return {Boolean}
 * @api private
 */

function isObject(val) {
  return Object == val.constructor;
}


/***/ }),
/* 20 */,
/* 21 */,
/* 22 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


var ContextMenu = __webpack_require__(4);

/**
 * Create a select box to be used in the editor menu's, which allows to switch mode
 * @param {HTMLElement} container
 * @param {String[]} modes  Available modes: 'code', 'form', 'text', 'tree', 'view'
 * @param {String} current  Available modes: 'code', 'form', 'text', 'tree', 'view'
 * @param {function(mode: string)} onSwitch  Callback invoked on switch
 * @constructor
 */
function ModeSwitcher(container, modes, current, onSwitch) {
  // available modes
  var availableModes = {
    code: {
      'text': 'Code',
      'title': 'Switch to code highlighter',
      'click': function () {
        onSwitch('code')
      }
    },
    form: {
      'text': 'Form',
      'title': 'Switch to form editor',
      'click': function () {
        onSwitch('form');
      }
    },
    text: {
      'text': 'Text',
      'title': 'Switch to plain text editor',
      'click': function () {
        onSwitch('text');
      }
    },
    tree: {
      'text': 'Tree',
      'title': 'Switch to tree editor',
      'click': function () {
        onSwitch('tree');
      }
    },
    view: {
      'text': 'View',
      'title': 'Switch to tree view',
      'click': function () {
        onSwitch('view');
      }
    }
  };

  // list the selected modes
  var items = [];
  for (var i = 0; i < modes.length; i++) {
    var mode = modes[i];
    var item = availableModes[mode];
    if (!item) {
      throw new Error('Unknown mode "' + mode + '"');
    }

    item.className = 'jsoneditor-type-modes' + ((current == mode) ? ' jsoneditor-selected' : '');
    items.push(item);
  }

  // retrieve the title of current mode
  var currentMode = availableModes[current];
  if (!currentMode) {
    throw new Error('Unknown mode "' + current + '"');
  }
  var currentTitle = currentMode.text;

  // create the html element
  var box = document.createElement('button');
  box.type = 'button';
  box.className = 'jsoneditor-modes jsoneditor-separator';
  box.innerHTML = currentTitle + ' &#x25BE;';
  box.title = 'Switch editor mode';
  box.onclick = function () {
    var menu = new ContextMenu(items);
    menu.show(box);
  };

  var frame = document.createElement('div');
  frame.className = 'jsoneditor-modes';
  frame.style.position = 'relative';
  frame.appendChild(box);

  container.appendChild(frame);

  this.dom = {
    container: container,
    box: box,
    frame: frame
  };
}

/**
 * Set focus to switcher
 */
ModeSwitcher.prototype.focus = function () {
  this.dom.box.focus();
};

/**
 * Destroy the ModeSwitcher, remove from DOM
 */
ModeSwitcher.prototype.destroy = function () {
  if (this.dom && this.dom.frame && this.dom.frame.parentNode) {
    this.dom.frame.parentNode.removeChild(this.dom.frame);
  }
  this.dom = null;
};

module.exports = ModeSwitcher;


/***/ }),
/* 23 */,
/* 24 */,
/* 25 */,
/* 26 */,
/* 27 */
/***/ (function(module, exports, __webpack_require__) {

window.JSONEditor = __webpack_require__(95);

/***/ }),
/* 28 */,
/* 29 */,
/* 30 */,
/* 31 */,
/* 32 */,
/* 33 */,
/* 34 */,
/* 35 */,
/* 36 */,
/* 37 */,
/* 38 */,
/* 39 */,
/* 40 */,
/* 41 */,
/* 42 */,
/* 43 */,
/* 44 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


var compileSchema = __webpack_require__(49)
  , resolve = __webpack_require__(11)
  , Cache = __webpack_require__(45)
  , SchemaObject = __webpack_require__(12)
  , stableStringify = __webpack_require__(5)
  , formats = __webpack_require__(48)
  , rules = __webpack_require__(50)
  , v5 = __webpack_require__(74)
  , util = __webpack_require__(1)
  , async = __webpack_require__(9)
  , co = __webpack_require__(19);

module.exports = Ajv;

Ajv.prototype.compileAsync = async.compile;

var customKeyword = __webpack_require__(71);
Ajv.prototype.addKeyword = customKeyword.add;
Ajv.prototype.getKeyword = customKeyword.get;
Ajv.prototype.removeKeyword = customKeyword.remove;
Ajv.ValidationError = __webpack_require__(13);

var META_SCHEMA_ID = 'http://json-schema.org/draft-04/schema';
var SCHEMA_URI_FORMAT = /^(?:(?:[a-z][a-z0-9+-.]*:)?\/\/)?[^\s]*$/i;
function SCHEMA_URI_FORMAT_FUNC(str) {
  return SCHEMA_URI_FORMAT.test(str);
}

var META_IGNORE_OPTIONS = [ 'removeAdditional', 'useDefaults', 'coerceTypes' ];

/**
 * Creates validator instance.
 * Usage: `Ajv(opts)`
 * @param {Object} opts optional options
 * @return {Object} ajv instance
 */
function Ajv(opts) {
  if (!(this instanceof Ajv)) return new Ajv(opts);
  var self = this;

  opts = this._opts = util.copy(opts) || {};
  this._schemas = {};
  this._refs = {};
  this._fragments = {};
  this._formats = formats(opts.format);
  this._cache = opts.cache || new Cache;
  this._loadingSchemas = {};
  this._compilations = [];
  this.RULES = rules();

  // this is done on purpose, so that methods are bound to the instance
  // (without using bind) so that they can be used without the instance
  this.validate = validate;
  this.compile = compile;
  this.addSchema = addSchema;
  this.addMetaSchema = addMetaSchema;
  this.validateSchema = validateSchema;
  this.getSchema = getSchema;
  this.removeSchema = removeSchema;
  this.addFormat = addFormat;
  this.errorsText = errorsText;

  this._addSchema = _addSchema;
  this._compile = _compile;

  opts.loopRequired = opts.loopRequired || Infinity;
  if (opts.async || opts.transpile) async.setup(opts);
  if (opts.beautify === true) opts.beautify = { indent_size: 2 };
  if (opts.errorDataPath == 'property') opts._errorDataPathProperty = true;
  this._metaOpts = getMetaSchemaOptions();

  if (opts.formats) addInitialFormats();
  addDraft4MetaSchema();
  if (opts.v5) v5.enable(this);
  if (typeof opts.meta == 'object') addMetaSchema(opts.meta);
  addInitialSchemas();


  /**
   * Validate data using schema
   * Schema will be compiled and cached (using serialized JSON as key. [json-stable-stringify](https://github.com/substack/json-stable-stringify) is used to serialize.
   * @param  {String|Object} schemaKeyRef key, ref or schema object
   * @param  {Any} data to be validated
   * @return {Boolean} validation result. Errors from the last validation will be available in `ajv.errors` (and also in compiled schema: `schema.errors`).
   */
  function validate(schemaKeyRef, data) {
    var v;
    if (typeof schemaKeyRef == 'string') {
      v = getSchema(schemaKeyRef);
      if (!v) throw new Error('no schema with key or ref "' + schemaKeyRef + '"');
    } else {
      var schemaObj = _addSchema(schemaKeyRef);
      v = schemaObj.validate || _compile(schemaObj);
    }

    var valid = v(data);
    if (v.$async === true)
      return self._opts.async == '*' ? co(valid) : valid;
    self.errors = v.errors;
    return valid;
  }


  /**
   * Create validating function for passed schema.
   * @param  {Object} schema schema object
   * @param  {Boolean} _meta true if schema is a meta-schema. Used internally to compile meta schemas of custom keywords.
   * @return {Function} validating function
   */
  function compile(schema, _meta) {
    var schemaObj = _addSchema(schema, undefined, _meta);
    return schemaObj.validate || _compile(schemaObj);
  }


  /**
   * Adds schema to the instance.
   * @param {Object|Array} schema schema or array of schemas. If array is passed, `key` and other parameters will be ignored.
   * @param {String} key Optional schema key. Can be passed to `validate` method instead of schema object or id/ref. One schema per instance can have empty `id` and `key`.
   * @param {Boolean} _skipValidation true to skip schema validation. Used internally, option validateSchema should be used instead.
   * @param {Boolean} _meta true if schema is a meta-schema. Used internally, addMetaSchema should be used instead.
   */
  function addSchema(schema, key, _skipValidation, _meta) {
    if (Array.isArray(schema)){
      for (var i=0; i<schema.length; i++) addSchema(schema[i], undefined, _skipValidation, _meta);
      return;
    }
    // can key/id have # inside?
    key = resolve.normalizeId(key || schema.id);
    checkUnique(key);
    self._schemas[key] = _addSchema(schema, _skipValidation, _meta, true);
  }


  /**
   * Add schema that will be used to validate other schemas
   * options in META_IGNORE_OPTIONS are alway set to false
   * @param {Object} schema schema object
   * @param {String} key optional schema key
   * @param {Boolean} skipValidation true to skip schema validation, can be used to override validateSchema option for meta-schema
   */
  function addMetaSchema(schema, key, skipValidation) {
    addSchema(schema, key, skipValidation, true);
  }


  /**
   * Validate schema
   * @param {Object} schema schema to validate
   * @param {Boolean} throwOrLogError pass true to throw (or log) an error if invalid
   * @return {Boolean} true if schema is valid
   */
  function validateSchema(schema, throwOrLogError) {
    var $schema = schema.$schema || self._opts.defaultMeta || defaultMeta();
    var currentUriFormat = self._formats.uri;
    self._formats.uri = typeof currentUriFormat == 'function'
                        ? SCHEMA_URI_FORMAT_FUNC
                        : SCHEMA_URI_FORMAT;
    var valid;
    try { valid = validate($schema, schema); }
    finally { self._formats.uri = currentUriFormat; }
    if (!valid && throwOrLogError) {
      var message = 'schema is invalid: ' + errorsText();
      if (self._opts.validateSchema == 'log') console.error(message);
      else throw new Error(message);
    }
    return valid;
  }


  function defaultMeta() {
    var meta = self._opts.meta;
    self._opts.defaultMeta = typeof meta == 'object'
                              ? meta.id || meta
                              : self._opts.v5
                                ? v5.META_SCHEMA_ID
                                : META_SCHEMA_ID;
    return self._opts.defaultMeta;
  }


  /**
   * Get compiled schema from the instance by `key` or `ref`.
   * @param  {String} keyRef `key` that was passed to `addSchema` or full schema reference (`schema.id` or resolved id).
   * @return {Function} schema validating function (with property `schema`).
   */
  function getSchema(keyRef) {
    var schemaObj = _getSchemaObj(keyRef);
    switch (typeof schemaObj) {
      case 'object': return schemaObj.validate || _compile(schemaObj);
      case 'string': return getSchema(schemaObj);
      case 'undefined': return _getSchemaFragment(keyRef);
    }
  }


  function _getSchemaFragment(ref) {
    var res = resolve.schema.call(self, { schema: {} }, ref);
    if (res) {
      var schema = res.schema
        , root = res.root
        , baseId = res.baseId;
      var v = compileSchema.call(self, schema, root, undefined, baseId);
      self._fragments[ref] = new SchemaObject({
        ref: ref,
        fragment: true,
        schema: schema,
        root: root,
        baseId: baseId,
        validate: v
      });
      return v;
    }
  }


  function _getSchemaObj(keyRef) {
    keyRef = resolve.normalizeId(keyRef);
    return self._schemas[keyRef] || self._refs[keyRef] || self._fragments[keyRef];
  }


  /**
   * Remove cached schema(s).
   * If no parameter is passed all schemas but meta-schemas are removed.
   * If RegExp is passed all schemas with key/id matching pattern but meta-schemas are removed.
   * Even if schema is referenced by other schemas it still can be removed as other schemas have local references.
   * @param  {String|Object|RegExp} schemaKeyRef key, ref, pattern to match key/ref or schema object
   */
  function removeSchema(schemaKeyRef) {
    if (schemaKeyRef instanceof RegExp) {
      _removeAllSchemas(self._schemas, schemaKeyRef);
      _removeAllSchemas(self._refs, schemaKeyRef);
      return;
    }
    switch (typeof schemaKeyRef) {
      case 'undefined':
        _removeAllSchemas(self._schemas);
        _removeAllSchemas(self._refs);
        self._cache.clear();
        return;
      case 'string':
        var schemaObj = _getSchemaObj(schemaKeyRef);
        if (schemaObj) self._cache.del(schemaObj.jsonStr);
        delete self._schemas[schemaKeyRef];
        delete self._refs[schemaKeyRef];
        return;
      case 'object':
        var jsonStr = stableStringify(schemaKeyRef);
        self._cache.del(jsonStr);
        var id = schemaKeyRef.id;
        if (id) {
          id = resolve.normalizeId(id);
          delete self._schemas[id];
          delete self._refs[id];
        }
    }
  }


  function _removeAllSchemas(schemas, regex) {
    for (var keyRef in schemas) {
      var schemaObj = schemas[keyRef];
      if (!schemaObj.meta && (!regex || regex.test(keyRef))) {
        self._cache.del(schemaObj.jsonStr);
        delete schemas[keyRef];
      }
    }
  }


  function _addSchema(schema, skipValidation, meta, shouldAddSchema) {
    if (typeof schema != 'object') throw new Error('schema should be object');
    var jsonStr = stableStringify(schema);
    var cached = self._cache.get(jsonStr);
    if (cached) return cached;

    shouldAddSchema = shouldAddSchema || self._opts.addUsedSchema !== false;

    var id = resolve.normalizeId(schema.id);
    if (id && shouldAddSchema) checkUnique(id);

    var willValidate = self._opts.validateSchema !== false && !skipValidation;
    var recursiveMeta;
    if (willValidate && !(recursiveMeta = schema.id && schema.id == schema.$schema))
      validateSchema(schema, true);

    var localRefs = resolve.ids.call(self, schema);

    var schemaObj = new SchemaObject({
      id: id,
      schema: schema,
      localRefs: localRefs,
      jsonStr: jsonStr,
      meta: meta
    });

    if (id[0] != '#' && shouldAddSchema) self._refs[id] = schemaObj;
    self._cache.put(jsonStr, schemaObj);

    if (willValidate && recursiveMeta) validateSchema(schema, true);

    return schemaObj;
  }


  function _compile(schemaObj, root) {
    if (schemaObj.compiling) {
      schemaObj.validate = callValidate;
      callValidate.schema = schemaObj.schema;
      callValidate.errors = null;
      callValidate.root = root ? root : callValidate;
      if (schemaObj.schema.$async === true)
        callValidate.$async = true;
      return callValidate;
    }
    schemaObj.compiling = true;

    var currentOpts;
    if (schemaObj.meta) {
      currentOpts = self._opts;
      self._opts = self._metaOpts;
    }

    var v;
    try { v = compileSchema.call(self, schemaObj.schema, root, schemaObj.localRefs); }
    finally {
      schemaObj.compiling = false;
      if (schemaObj.meta) self._opts = currentOpts;
    }

    schemaObj.validate = v;
    schemaObj.refs = v.refs;
    schemaObj.refVal = v.refVal;
    schemaObj.root = v.root;
    return v;


    function callValidate() {
      var _validate = schemaObj.validate;
      var result = _validate.apply(null, arguments);
      callValidate.errors = _validate.errors;
      return result;
    }
  }


  /**
   * Convert array of error message objects to string
   * @param  {Array<Object>} errors optional array of validation errors, if not passed errors from the instance are used.
   * @param  {Object} options optional options with properties `separator` and `dataVar`.
   * @return {String} human readable string with all errors descriptions
   */
  function errorsText(errors, options) {
    errors = errors || self.errors;
    if (!errors) return 'No errors';
    options = options || {};
    var separator = options.separator === undefined ? ', ' : options.separator;
    var dataVar = options.dataVar === undefined ? 'data' : options.dataVar;

    var text = '';
    for (var i=0; i<errors.length; i++) {
      var e = errors[i];
      if (e) text += dataVar + e.dataPath + ' ' + e.message + separator;
    }
    return text.slice(0, -separator.length);
  }


  /**
   * Add custom format
   * @param {String} name format name
   * @param {String|RegExp|Function} format string is converted to RegExp; function should return boolean (true when valid)
   */
  function addFormat(name, format) {
    if (typeof format == 'string') format = new RegExp(format);
    self._formats[name] = format;
  }


  function addDraft4MetaSchema() {
    if (self._opts.meta !== false) {
      var metaSchema = __webpack_require__(72);
      addMetaSchema(metaSchema, META_SCHEMA_ID, true);
      self._refs['http://json-schema.org/schema'] = META_SCHEMA_ID;
    }
  }


  function addInitialSchemas() {
    var optsSchemas = self._opts.schemas;
    if (!optsSchemas) return;
    if (Array.isArray(optsSchemas)) addSchema(optsSchemas);
    else for (var key in optsSchemas) addSchema(optsSchemas[key], key);
  }


  function addInitialFormats() {
    for (var name in self._opts.formats) {
      var format = self._opts.formats[name];
      addFormat(name, format);
    }
  }


  function checkUnique(id) {
    if (self._schemas[id] || self._refs[id])
      throw new Error('schema with key or id "' + id + '" already exists');
  }


  function getMetaSchemaOptions() {
    var metaOpts = util.copy(self._opts);
    for (var i=0; i<META_IGNORE_OPTIONS.length; i++)
      delete metaOpts[META_IGNORE_OPTIONS[i]];
    return metaOpts;
  }
}


/***/ }),
/* 45 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";



var Cache = module.exports = function Cache() {
  this._cache = {};
};


Cache.prototype.put = function Cache_put(key, value) {
  this._cache[key] = value;
};


Cache.prototype.get = function Cache_get(key) {
  return this._cache[key];
};


Cache.prototype.del = function Cache_del(key) {
  delete this._cache[key];
};


Cache.prototype.clear = function Cache_clear() {
  this._cache = {};
};


/***/ }),
/* 46 */
/***/ (function(module, exports) {

function webpackEmptyContext(req) {
	throw new Error("Cannot find module '" + req + "'.");
}
webpackEmptyContext.keys = function() { return []; };
webpackEmptyContext.resolve = webpackEmptyContext;
module.exports = webpackEmptyContext;
webpackEmptyContext.id = 46;

/***/ }),
/* 47 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


//all requires must be explicit because browserify won't work with dynamic requires
module.exports = {
  '$ref': __webpack_require__(67),
  allOf: __webpack_require__(53),
  anyOf: __webpack_require__(54),
  dependencies: __webpack_require__(57),
  'enum': __webpack_require__(58),
  format: __webpack_require__(59),
  items: __webpack_require__(60),
  maximum: __webpack_require__(14),
  minimum: __webpack_require__(14),
  maxItems: __webpack_require__(15),
  minItems: __webpack_require__(15),
  maxLength: __webpack_require__(16),
  minLength: __webpack_require__(16),
  maxProperties: __webpack_require__(17),
  minProperties: __webpack_require__(17),
  multipleOf: __webpack_require__(61),
  not: __webpack_require__(62),
  oneOf: __webpack_require__(63),
  pattern: __webpack_require__(64),
  properties: __webpack_require__(66),
  required: __webpack_require__(68),
  uniqueItems: __webpack_require__(70),
  validate: __webpack_require__(18)
};


/***/ }),
/* 48 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


var util = __webpack_require__(1);

var DATE = /^\d\d\d\d-(\d\d)-(\d\d)$/;
var DAYS = [0,31,29,31,30,31,30,31,31,30,31,30,31];
var TIME = /^(\d\d):(\d\d):(\d\d)(\.\d+)?(z|[+-]\d\d:\d\d)?$/i;
var HOSTNAME = /^[a-z0-9](?:[a-z0-9-]{0,61}[a-z0-9])?(?:\.[a-z0-9](?:[-0-9a-z]{0,61}[0-9a-z])?)*$/i;
var URI = /^(?:[a-z][a-z0-9+\-.]*:)?(?:\/?\/(?:(?:[a-z0-9\-._~!$&'()*+,;=:]|%[0-9a-f]{2})*@)?(?:\[(?:(?:(?:(?:[0-9a-f]{1,4}:){6}|::(?:[0-9a-f]{1,4}:){5}|(?:[0-9a-f]{1,4})?::(?:[0-9a-f]{1,4}:){4}|(?:(?:[0-9a-f]{1,4}:){0,1}[0-9a-f]{1,4})?::(?:[0-9a-f]{1,4}:){3}|(?:(?:[0-9a-f]{1,4}:){0,2}[0-9a-f]{1,4})?::(?:[0-9a-f]{1,4}:){2}|(?:(?:[0-9a-f]{1,4}:){0,3}[0-9a-f]{1,4})?::[0-9a-f]{1,4}:|(?:(?:[0-9a-f]{1,4}:){0,4}[0-9a-f]{1,4})?::)(?:[0-9a-f]{1,4}:[0-9a-f]{1,4}|(?:(?:25[0-5]|2[0-4]\d|[01]?\d\d?)\.){3}(?:25[0-5]|2[0-4]\d|[01]?\d\d?))|(?:(?:[0-9a-f]{1,4}:){0,5}[0-9a-f]{1,4})?::[0-9a-f]{1,4}|(?:(?:[0-9a-f]{1,4}:){0,6}[0-9a-f]{1,4})?::)|[Vv][0-9a-f]+\.[a-z0-9\-._~!$&'()*+,;=:]+)\]|(?:(?:25[0-5]|2[0-4]\d|[01]?\d\d?)\.){3}(?:25[0-5]|2[0-4]\d|[01]?\d\d?)|(?:[a-z0-9\-._~!$&'()*+,;=]|%[0-9a-f]{2})*)(?::\d*)?(?:\/(?:[a-z0-9\-._~!$&'()*+,;=:@]|%[0-9a-f]{2})*)*|\/(?:(?:[a-z0-9\-._~!$&'()*+,;=:@]|%[0-9a-f]{2})+(?:\/(?:[a-z0-9\-._~!$&'()*+,;=:@]|%[0-9a-f]{2})*)*)?|(?:[a-z0-9\-._~!$&'()*+,;=:@]|%[0-9a-f]{2})+(?:\/(?:[a-z0-9\-._~!$&'()*+,;=:@]|%[0-9a-f]{2})*)*)(?:\?(?:[a-z0-9\-._~!$&'()*+,;=:@\/?]|%[0-9a-f]{2})*)?(?:\#(?:[a-z0-9\-._~!$&'()*+,;=:@\/?]|%[0-9a-f]{2})*)?$/i;
var UUID = /^(?:urn\:uuid\:)?[0-9a-f]{8}-(?:[0-9a-f]{4}-){3}[0-9a-f]{12}$/i;
var JSON_POINTER = /^(?:\/(?:[^~\/]|~0|~1)*)*$|^\#(?:\/(?:[a-z0-9_\-\.!$&'()*+,;:=@]|%[0-9a-f]{2}|~0|~1)*)*$/i;
var RELATIVE_JSON_POINTER = /^(?:0|[1-9][0-9]*)(?:\#|(?:\/(?:[^~\/]|~0|~1)*)*)$/;


module.exports = formats;

function formats(mode) {
  mode = mode == 'full' ? 'full' : 'fast';
  var formatDefs = util.copy(formats[mode]);
  for (var fName in formats.compare) {
    formatDefs[fName] = {
      validate: formatDefs[fName],
      compare: formats.compare[fName]
    };
  }
  return formatDefs;
}


formats.fast = {
  // date: http://tools.ietf.org/html/rfc3339#section-5.6
  date: /^\d\d\d\d-[0-1]\d-[0-3]\d$/,
  // date-time: http://tools.ietf.org/html/rfc3339#section-5.6
  time: /^[0-2]\d:[0-5]\d:[0-5]\d(?:\.\d+)?(?:z|[+-]\d\d:\d\d)?$/i,
  'date-time': /^\d\d\d\d-[0-1]\d-[0-3]\d[t\s][0-2]\d:[0-5]\d:[0-5]\d(?:\.\d+)?(?:z|[+-]\d\d:\d\d)$/i,
  // uri: https://github.com/mafintosh/is-my-json-valid/blob/master/formats.js
  uri: /^(?:[a-z][a-z0-9+-.]*)?(?:\:|\/)\/?[^\s]*$/i,
  // email (sources from jsen validator):
  // http://stackoverflow.com/questions/201323/using-a-regular-expression-to-validate-an-email-address#answer-8829363
  // http://www.w3.org/TR/html5/forms.html#valid-e-mail-address (search for 'willful violation')
  email: /^[a-z0-9.!#$%&'*+\/=?^_`{|}~-]+@[a-z0-9](?:[a-z0-9-]{0,61}[a-z0-9])?(?:\.[a-z0-9](?:[a-z0-9-]{0,61}[a-z0-9])?)*$/i,
  hostname: HOSTNAME,
  // optimized https://www.safaribooksonline.com/library/view/regular-expressions-cookbook/9780596802837/ch07s16.html
  ipv4: /^(?:(?:25[0-5]|2[0-4]\d|[01]?\d\d?)\.){3}(?:25[0-5]|2[0-4]\d|[01]?\d\d?)$/,
  // optimized http://stackoverflow.com/questions/53497/regular-expression-that-matches-valid-ipv6-addresses
  ipv6: /^\s*(?:(?:(?:[0-9a-f]{1,4}:){7}(?:[0-9a-f]{1,4}|:))|(?:(?:[0-9a-f]{1,4}:){6}(?::[0-9a-f]{1,4}|(?:(?:25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)(?:\.(?:25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)){3})|:))|(?:(?:[0-9a-f]{1,4}:){5}(?:(?:(?::[0-9a-f]{1,4}){1,2})|:(?:(?:25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)(?:\.(?:25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)){3})|:))|(?:(?:[0-9a-f]{1,4}:){4}(?:(?:(?::[0-9a-f]{1,4}){1,3})|(?:(?::[0-9a-f]{1,4})?:(?:(?:25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)(?:\.(?:25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)){3}))|:))|(?:(?:[0-9a-f]{1,4}:){3}(?:(?:(?::[0-9a-f]{1,4}){1,4})|(?:(?::[0-9a-f]{1,4}){0,2}:(?:(?:25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)(?:\.(?:25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)){3}))|:))|(?:(?:[0-9a-f]{1,4}:){2}(?:(?:(?::[0-9a-f]{1,4}){1,5})|(?:(?::[0-9a-f]{1,4}){0,3}:(?:(?:25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)(?:\.(?:25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)){3}))|:))|(?:(?:[0-9a-f]{1,4}:){1}(?:(?:(?::[0-9a-f]{1,4}){1,6})|(?:(?::[0-9a-f]{1,4}){0,4}:(?:(?:25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)(?:\.(?:25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)){3}))|:))|(?::(?:(?:(?::[0-9a-f]{1,4}){1,7})|(?:(?::[0-9a-f]{1,4}){0,5}:(?:(?:25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)(?:\.(?:25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)){3}))|:)))(?:%.+)?\s*$/i,
  regex: regex,
  // uuid: http://tools.ietf.org/html/rfc4122
  uuid: UUID,
  // JSON-pointer: https://tools.ietf.org/html/rfc6901
  // uri fragment: https://tools.ietf.org/html/rfc3986#appendix-A
  'json-pointer': JSON_POINTER,
  // relative JSON-pointer: http://tools.ietf.org/html/draft-luff-relative-json-pointer-00
  'relative-json-pointer': RELATIVE_JSON_POINTER
};


formats.full = {
  date: date,
  time: time,
  'date-time': date_time,
  uri: uri,
  email: /^[a-z0-9!#$%&'*+\/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&''*+\/=?^_`{|}~-]+)*@(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?$/i,
  hostname: hostname,
  ipv4: /^(?:(?:25[0-5]|2[0-4]\d|[01]?\d\d?)\.){3}(?:25[0-5]|2[0-4]\d|[01]?\d\d?)$/,
  ipv6: /^\s*(?:(?:(?:[0-9a-f]{1,4}:){7}(?:[0-9a-f]{1,4}|:))|(?:(?:[0-9a-f]{1,4}:){6}(?::[0-9a-f]{1,4}|(?:(?:25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)(?:\.(?:25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)){3})|:))|(?:(?:[0-9a-f]{1,4}:){5}(?:(?:(?::[0-9a-f]{1,4}){1,2})|:(?:(?:25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)(?:\.(?:25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)){3})|:))|(?:(?:[0-9a-f]{1,4}:){4}(?:(?:(?::[0-9a-f]{1,4}){1,3})|(?:(?::[0-9a-f]{1,4})?:(?:(?:25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)(?:\.(?:25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)){3}))|:))|(?:(?:[0-9a-f]{1,4}:){3}(?:(?:(?::[0-9a-f]{1,4}){1,4})|(?:(?::[0-9a-f]{1,4}){0,2}:(?:(?:25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)(?:\.(?:25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)){3}))|:))|(?:(?:[0-9a-f]{1,4}:){2}(?:(?:(?::[0-9a-f]{1,4}){1,5})|(?:(?::[0-9a-f]{1,4}){0,3}:(?:(?:25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)(?:\.(?:25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)){3}))|:))|(?:(?:[0-9a-f]{1,4}:){1}(?:(?:(?::[0-9a-f]{1,4}){1,6})|(?:(?::[0-9a-f]{1,4}){0,4}:(?:(?:25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)(?:\.(?:25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)){3}))|:))|(?::(?:(?:(?::[0-9a-f]{1,4}){1,7})|(?:(?::[0-9a-f]{1,4}){0,5}:(?:(?:25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)(?:\.(?:25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)){3}))|:)))(?:%.+)?\s*$/i,
  regex: regex,
  uuid: UUID,
  'json-pointer': JSON_POINTER,
  'relative-json-pointer': RELATIVE_JSON_POINTER
};


formats.compare = {
  date: compareDate,
  time: compareTime,
  'date-time': compareDateTime
};


function date(str) {
  // full-date from http://tools.ietf.org/html/rfc3339#section-5.6
  var matches = str.match(DATE);
  if (!matches) return false;

  var month = +matches[1];
  var day = +matches[2];
  return month >= 1 && month <= 12 && day >= 1 && day <= DAYS[month];
}


function time(str, full) {
  var matches = str.match(TIME);
  if (!matches) return false;

  var hour = matches[1];
  var minute = matches[2];
  var second = matches[3];
  var timeZone = matches[5];
  return hour <= 23 && minute <= 59 && second <= 59 && (!full || timeZone);
}


var DATE_TIME_SEPARATOR = /t|\s/i;
function date_time(str) {
  // http://tools.ietf.org/html/rfc3339#section-5.6
  var dateTime = str.split(DATE_TIME_SEPARATOR);
  return dateTime.length == 2 && date(dateTime[0]) && time(dateTime[1], true);
}


function hostname(str) {
  // https://tools.ietf.org/html/rfc1034#section-3.5
  // https://tools.ietf.org/html/rfc1123#section-2
  return str.length <= 255 && HOSTNAME.test(str);
}


var NOT_URI_FRAGMENT = /\/|\:/;
function uri(str) {
  // http://jmrware.com/articles/2009/uri_regexp/URI_regex.html + optional protocol + required "."
  return NOT_URI_FRAGMENT.test(str) && URI.test(str);
}


function regex(str) {
  try {
    new RegExp(str);
    return true;
  } catch(e) {
    return false;
  }
}


function compareDate(d1, d2) {
  if (!(d1 && d2)) return;
  if (d1 > d2) return 1;
  if (d1 < d2) return -1;
  if (d1 === d2) return 0;
}


function compareTime(t1, t2) {
  if (!(t1 && t2)) return;
  t1 = t1.match(TIME);
  t2 = t2.match(TIME);
  if (!(t1 && t2)) return;
  t1 = t1[1] + t1[2] + t1[3] + (t1[4]||'');
  t2 = t2[1] + t2[2] + t2[3] + (t2[4]||'');
  if (t1 > t2) return 1;
  if (t1 < t2) return -1;
  if (t1 === t2) return 0;
}


function compareDateTime(dt1, dt2) {
  if (!(dt1 && dt2)) return;
  dt1 = dt1.split(DATE_TIME_SEPARATOR);
  dt2 = dt2.split(DATE_TIME_SEPARATOR);
  var res = compareDate(dt1[0], dt2[0]);
  if (res === undefined) return;
  return res || compareTime(dt1[1], dt2[1]);
}


/***/ }),
/* 49 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


var resolve = __webpack_require__(11)
  , util = __webpack_require__(1)
  , stableStringify = __webpack_require__(5)
  , async = __webpack_require__(9);

var beautify;

function loadBeautify(){
  if (beautify === undefined) {
    var name = 'js-beautify';
    try { beautify = !(function webpackMissingModule() { var e = new Error("Cannot find module \".\""); e.code = 'MODULE_NOT_FOUND'; throw e; }()).js_beautify; }
    catch(e) { beautify = false; }
  }
}

var validateGenerator = __webpack_require__(18);

/**
 * Functions below are used inside compiled validations function
 */

var co = __webpack_require__(19);
var ucs2length = util.ucs2length;
var equal = __webpack_require__(10);

// this error is thrown by async schemas to return validation errors via exception
var ValidationError = __webpack_require__(13);

module.exports = compile;


/**
 * Compiles schema to validation function
 * @this   Ajv
 * @param  {Object} schema schema object
 * @param  {Object} root object with information about the root schema for this schema
 * @param  {Object} localRefs the hash of local references inside the schema (created by resolve.id), used for inline resolution
 * @param  {String} baseId base ID for IDs in the schema
 * @return {Function} validation function
 */
function compile(schema, root, localRefs, baseId) {
  /* jshint validthis: true, evil: true */
  /* eslint no-shadow: 0 */
  var self = this
    , opts = this._opts
    , refVal = [ undefined ]
    , refs = {}
    , patterns = []
    , patternsHash = {}
    , defaults = []
    , defaultsHash = {}
    , customRules = []
    , keepSourceCode = opts.sourceCode !== false;

  root = root || { schema: schema, refVal: refVal, refs: refs };

  var c = checkCompiling.call(this, schema, root, baseId);
  var compilation = this._compilations[c.index];
  if (c.compiling) return (compilation.callValidate = callValidate);

  var formats = this._formats;
  var RULES = this.RULES;

  try {
    var v = localCompile(schema, root, localRefs, baseId);
    compilation.validate = v;
    var cv = compilation.callValidate;
    if (cv) {
      cv.schema = v.schema;
      cv.errors = null;
      cv.refs = v.refs;
      cv.refVal = v.refVal;
      cv.root = v.root;
      cv.$async = v.$async;
      if (keepSourceCode) cv.sourceCode = v.sourceCode;
    }
    return v;
  } finally {
    endCompiling.call(this, schema, root, baseId);
  }

  function callValidate() {
    var validate = compilation.validate;
    var result = validate.apply(null, arguments);
    callValidate.errors = validate.errors;
    return result;
  }

  function localCompile(_schema, _root, localRefs, baseId) {
    var isRoot = !_root || (_root && _root.schema == _schema);
    if (_root.schema != root.schema)
      return compile.call(self, _schema, _root, localRefs, baseId);

    var $async = _schema.$async === true;
    if ($async && !opts.transpile) async.setup(opts);

    var sourceCode = validateGenerator({
      isTop: true,
      schema: _schema,
      isRoot: isRoot,
      baseId: baseId,
      root: _root,
      schemaPath: '',
      errSchemaPath: '#',
      errorPath: '""',
      RULES: RULES,
      validate: validateGenerator,
      util: util,
      resolve: resolve,
      resolveRef: resolveRef,
      usePattern: usePattern,
      useDefault: useDefault,
      useCustomRule: useCustomRule,
      opts: opts,
      formats: formats,
      self: self
    });

    sourceCode = vars(refVal, refValCode) + vars(patterns, patternCode)
                   + vars(defaults, defaultCode) + vars(customRules, customRuleCode)
                   + sourceCode;

    if (opts.beautify) {
      loadBeautify();
      /* istanbul ignore else */
      if (beautify) sourceCode = beautify(sourceCode, opts.beautify);
      else console.error('"npm install js-beautify" to use beautify option');
    }
    // console.log('\n\n\n *** \n', sourceCode);
    var validate, validateCode
      , transpile = opts._transpileFunc;
    try {
      validateCode = $async && transpile
                      ? transpile(sourceCode)
                      : sourceCode;

      var makeValidate = new Function(
        'self',
        'RULES',
        'formats',
        'root',
        'refVal',
        'defaults',
        'customRules',
        'co',
        'equal',
        'ucs2length',
        'ValidationError',
        validateCode
      );

      validate = makeValidate(
        self,
        RULES,
        formats,
        root,
        refVal,
        defaults,
        customRules,
        co,
        equal,
        ucs2length,
        ValidationError
      );

      refVal[0] = validate;
    } catch(e) {
      console.error('Error compiling schema, function code:', validateCode);
      throw e;
    }

    validate.schema = _schema;
    validate.errors = null;
    validate.refs = refs;
    validate.refVal = refVal;
    validate.root = isRoot ? validate : _root;
    if ($async) validate.$async = true;
    if (keepSourceCode) validate.sourceCode = sourceCode;
    if (opts.sourceCode === true) {
      validate.source = {
        patterns: patterns,
        defaults: defaults
      };
    }

    return validate;
  }

  function resolveRef(baseId, ref, isRoot) {
    ref = resolve.url(baseId, ref);
    var refIndex = refs[ref];
    var _refVal, refCode;
    if (refIndex !== undefined) {
      _refVal = refVal[refIndex];
      refCode = 'refVal[' + refIndex + ']';
      return resolvedRef(_refVal, refCode);
    }
    if (!isRoot && root.refs) {
      var rootRefId = root.refs[ref];
      if (rootRefId !== undefined) {
        _refVal = root.refVal[rootRefId];
        refCode = addLocalRef(ref, _refVal);
        return resolvedRef(_refVal, refCode);
      }
    }

    refCode = addLocalRef(ref);
    var v = resolve.call(self, localCompile, root, ref);
    if (!v) {
      var localSchema = localRefs && localRefs[ref];
      if (localSchema) {
        v = resolve.inlineRef(localSchema, opts.inlineRefs)
            ? localSchema
            : compile.call(self, localSchema, root, localRefs, baseId);
      }
    }

    if (v) {
      replaceLocalRef(ref, v);
      return resolvedRef(v, refCode);
    }
  }

  function addLocalRef(ref, v) {
    var refId = refVal.length;
    refVal[refId] = v;
    refs[ref] = refId;
    return 'refVal' + refId;
  }

  function replaceLocalRef(ref, v) {
    var refId = refs[ref];
    refVal[refId] = v;
  }

  function resolvedRef(refVal, code) {
    return typeof refVal == 'object'
            ? { code: code, schema: refVal, inline: true }
            : { code: code, $async: refVal && refVal.$async };
  }

  function usePattern(regexStr) {
    var index = patternsHash[regexStr];
    if (index === undefined) {
      index = patternsHash[regexStr] = patterns.length;
      patterns[index] = regexStr;
    }
    return 'pattern' + index;
  }

  function useDefault(value) {
    switch (typeof value) {
      case 'boolean':
      case 'number':
        return '' + value;
      case 'string':
        return util.toQuotedString(value);
      case 'object':
        if (value === null) return 'null';
        var valueStr = stableStringify(value);
        var index = defaultsHash[valueStr];
        if (index === undefined) {
          index = defaultsHash[valueStr] = defaults.length;
          defaults[index] = value;
        }
        return 'default' + index;
    }
  }

  function useCustomRule(rule, schema, parentSchema, it) {
    var validateSchema = rule.definition.validateSchema;
    if (validateSchema && self._opts.validateSchema !== false) {
      var valid = validateSchema(schema);
      if (!valid) {
        var message = 'keyword schema is invalid: ' + self.errorsText(validateSchema.errors);
        if (self._opts.validateSchema == 'log') console.error(message);
        else throw new Error(message);
      }
    }

    var compile = rule.definition.compile
      , inline = rule.definition.inline
      , macro = rule.definition.macro;

    var validate;
    if (compile) {
      validate = compile.call(self, schema, parentSchema, it);
    } else if (macro) {
      validate = macro.call(self, schema, parentSchema, it);
      if (opts.validateSchema !== false) self.validateSchema(validate, true);
    } else if (inline) {
      validate = inline.call(self, it, rule.keyword, schema, parentSchema);
    } else {
      validate = rule.definition.validate;
    }

    var index = customRules.length;
    customRules[index] = validate;

    return {
      code: 'customRule' + index,
      validate: validate
    };
  }
}


/**
 * Checks if the schema is currently compiled
 * @this   Ajv
 * @param  {Object} schema schema to compile
 * @param  {Object} root root object
 * @param  {String} baseId base schema ID
 * @return {Object} object with properties "index" (compilation index) and "compiling" (boolean)
 */
function checkCompiling(schema, root, baseId) {
  /* jshint validthis: true */
  var index = compIndex.call(this, schema, root, baseId);
  if (index >= 0) return { index: index, compiling: true };
  index = this._compilations.length;
  this._compilations[index] = {
    schema: schema,
    root: root,
    baseId: baseId
  };
  return { index: index, compiling: false };
}


/**
 * Removes the schema from the currently compiled list
 * @this   Ajv
 * @param  {Object} schema schema to compile
 * @param  {Object} root root object
 * @param  {String} baseId base schema ID
 */
function endCompiling(schema, root, baseId) {
  /* jshint validthis: true */
  var i = compIndex.call(this, schema, root, baseId);
  if (i >= 0) this._compilations.splice(i, 1);
}


/**
 * Index of schema compilation in the currently compiled list
 * @this   Ajv
 * @param  {Object} schema schema to compile
 * @param  {Object} root root object
 * @param  {String} baseId base schema ID
 * @return {Integer} compilation index
 */
function compIndex(schema, root, baseId) {
  /* jshint validthis: true */
  for (var i=0; i<this._compilations.length; i++) {
    var c = this._compilations[i];
    if (c.schema == schema && c.root == root && c.baseId == baseId) return i;
  }
  return -1;
}


function patternCode(i, patterns) {
  return 'var pattern' + i + ' = new RegExp(' + util.toQuotedString(patterns[i]) + ');';
}


function defaultCode(i) {
  return 'var default' + i + ' = defaults[' + i + '];';
}


function refValCode(i, refVal) {
  return refVal[i] ? 'var refVal' + i + ' = refVal[' + i + '];' : '';
}


function customRuleCode(i) {
  return 'var customRule' + i + ' = customRules[' + i + '];';
}


function vars(arr, statement) {
  if (!arr.length) return '';
  var code = '';
  for (var i=0; i<arr.length; i++)
    code += statement(i, arr);
  return code;
}


/***/ }),
/* 50 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


var ruleModules = __webpack_require__(47)
  , toHash = __webpack_require__(1).toHash;

module.exports = function rules() {
  var RULES = [
    { type: 'number',
      rules: [ 'maximum', 'minimum', 'multipleOf'] },
    { type: 'string',
      rules: [ 'maxLength', 'minLength', 'pattern', 'format' ] },
    { type: 'array',
      rules: [ 'maxItems', 'minItems', 'uniqueItems', 'items' ] },
    { type: 'object',
      rules: [ 'maxProperties', 'minProperties', 'required', 'dependencies', 'properties' ] },
    { rules: [ '$ref', 'enum', 'not', 'anyOf', 'oneOf', 'allOf' ] }
  ];

  var ALL = [ 'type', 'additionalProperties', 'patternProperties' ];
  var KEYWORDS = [ 'additionalItems', '$schema', 'id', 'title', 'description', 'default' ];
  var TYPES = [ 'number', 'integer', 'string', 'array', 'object', 'boolean', 'null' ];
  RULES.all = toHash(ALL);

  RULES.forEach(function (group) {
    group.rules = group.rules.map(function (keyword) {
      ALL.push(keyword);
      var rule = RULES.all[keyword] = {
        keyword: keyword,
        code: ruleModules[keyword]
      };
      return rule;
    });
  });

  RULES.keywords = toHash(ALL.concat(KEYWORDS));
  RULES.types = toHash(TYPES);
  RULES.custom = {};

  return RULES;
};


/***/ }),
/* 51 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


// https://mathiasbynens.be/notes/javascript-encoding
// https://github.com/bestiejs/punycode.js - punycode.ucs2.decode
module.exports = function ucs2length(str) {
  var length = 0
    , len = str.length
    , pos = 0
    , value;
  while (pos < len) {
    length++;
    value = str.charCodeAt(pos++);
    if (value >= 0xD800 && value <= 0xDBFF && pos < len) {
      // high surrogate, and there is a next character
      value = str.charCodeAt(pos);
      if ((value & 0xFC00) == 0xDC00) pos++; // low surrogate
    }
  }
  return length;
};


/***/ }),
/* 52 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";

module.exports = function generate__formatLimit(it, $keyword) {
  var out = ' ';
  var $lvl = it.level;
  var $dataLvl = it.dataLevel;
  var $schema = it.schema[$keyword];
  var $schemaPath = it.schemaPath + it.util.getProperty($keyword);
  var $errSchemaPath = it.errSchemaPath + '/' + $keyword;
  var $breakOnError = !it.opts.allErrors;
  var $errorKeyword;
  var $data = 'data' + ($dataLvl || '');
  var $valid = 'valid' + $lvl;
  out += 'var ' + ($valid) + ' = undefined;';
  if (it.opts.format === false) {
    out += ' ' + ($valid) + ' = true; ';
    return out;
  }
  var $schemaFormat = it.schema.format,
    $isDataFormat = it.opts.v5 && $schemaFormat.$data,
    $closingBraces = '';
  if ($isDataFormat) {
    var $schemaValueFormat = it.util.getData($schemaFormat.$data, $dataLvl, it.dataPathArr),
      $format = 'format' + $lvl,
      $compare = 'compare' + $lvl;
    out += ' var ' + ($format) + ' = formats[' + ($schemaValueFormat) + '] , ' + ($compare) + ' = ' + ($format) + ' && ' + ($format) + '.compare;';
  } else {
    var $format = it.formats[$schemaFormat];
    if (!($format && $format.compare)) {
      out += '  ' + ($valid) + ' = true; ';
      return out;
    }
    var $compare = 'formats' + it.util.getProperty($schemaFormat) + '.compare';
  }
  var $isMax = $keyword == 'formatMaximum',
    $exclusiveKeyword = 'formatExclusive' + ($isMax ? 'Maximum' : 'Minimum'),
    $schemaExcl = it.schema[$exclusiveKeyword],
    $isDataExcl = it.opts.v5 && $schemaExcl && $schemaExcl.$data,
    $op = $isMax ? '<' : '>',
    $result = 'result' + $lvl;
  var $isData = it.opts.v5 && $schema && $schema.$data,
    $schemaValue;
  if ($isData) {
    out += ' var schema' + ($lvl) + ' = ' + (it.util.getData($schema.$data, $dataLvl, it.dataPathArr)) + '; ';
    $schemaValue = 'schema' + $lvl;
  } else {
    $schemaValue = $schema;
  }
  if ($isDataExcl) {
    var $schemaValueExcl = it.util.getData($schemaExcl.$data, $dataLvl, it.dataPathArr),
      $exclusive = 'exclusive' + $lvl,
      $opExpr = 'op' + $lvl,
      $opStr = '\' + ' + $opExpr + ' + \'';
    out += ' var schemaExcl' + ($lvl) + ' = ' + ($schemaValueExcl) + '; ';
    $schemaValueExcl = 'schemaExcl' + $lvl;
    out += ' if (typeof ' + ($schemaValueExcl) + ' != \'boolean\' && ' + ($schemaValueExcl) + ' !== undefined) { ' + ($valid) + ' = false; ';
    var $errorKeyword = $exclusiveKeyword;
    var $$outStack = $$outStack || [];
    $$outStack.push(out);
    out = ''; /* istanbul ignore else */
    if (it.createErrors !== false) {
      out += ' { keyword: \'' + ($errorKeyword || '_formatExclusiveLimit') + '\' , dataPath: (dataPath || \'\') + ' + (it.errorPath) + ' , schemaPath: ' + (it.util.toQuotedString($errSchemaPath)) + ' , params: {} ';
      if (it.opts.messages !== false) {
        out += ' , message: \'' + ($exclusiveKeyword) + ' should be boolean\' ';
      }
      if (it.opts.verbose) {
        out += ' , schema: validate.schema' + ($schemaPath) + ' , parentSchema: validate.schema' + (it.schemaPath) + ' , data: ' + ($data) + ' ';
      }
      out += ' } ';
    } else {
      out += ' {} ';
    }
    var __err = out;
    out = $$outStack.pop();
    if (!it.compositeRule && $breakOnError) { /* istanbul ignore if */
      if (it.async) {
        out += ' throw new ValidationError([' + (__err) + ']); ';
      } else {
        out += ' validate.errors = [' + (__err) + ']; return false; ';
      }
    } else {
      out += ' var err = ' + (__err) + ';  if (vErrors === null) vErrors = [err]; else vErrors.push(err); errors++; ';
    }
    out += ' }  ';
    if ($breakOnError) {
      $closingBraces += '}';
      out += ' else { ';
    }
    if ($isData) {
      out += ' if (' + ($schemaValue) + ' === undefined) ' + ($valid) + ' = true; else if (typeof ' + ($schemaValue) + ' != \'string\') ' + ($valid) + ' = false; else { ';
      $closingBraces += '}';
    }
    if ($isDataFormat) {
      out += ' if (!' + ($compare) + ') ' + ($valid) + ' = true; else { ';
      $closingBraces += '}';
    }
    out += ' var ' + ($result) + ' = ' + ($compare) + '(' + ($data) + ',  ';
    if ($isData) {
      out += '' + ($schemaValue);
    } else {
      out += '' + (it.util.toQuotedString($schema));
    }
    out += ' ); if (' + ($result) + ' === undefined) ' + ($valid) + ' = false; var ' + ($exclusive) + ' = ' + ($schemaValueExcl) + ' === true; if (' + ($valid) + ' === undefined) { ' + ($valid) + ' = ' + ($exclusive) + ' ? ' + ($result) + ' ' + ($op) + ' 0 : ' + ($result) + ' ' + ($op) + '= 0; } if (!' + ($valid) + ') var op' + ($lvl) + ' = ' + ($exclusive) + ' ? \'' + ($op) + '\' : \'' + ($op) + '=\';';
  } else {
    var $exclusive = $schemaExcl === true,
      $opStr = $op;
    if (!$exclusive) $opStr += '=';
    var $opExpr = '\'' + $opStr + '\'';
    if ($isData) {
      out += ' if (' + ($schemaValue) + ' === undefined) ' + ($valid) + ' = true; else if (typeof ' + ($schemaValue) + ' != \'string\') ' + ($valid) + ' = false; else { ';
      $closingBraces += '}';
    }
    if ($isDataFormat) {
      out += ' if (!' + ($compare) + ') ' + ($valid) + ' = true; else { ';
      $closingBraces += '}';
    }
    out += ' var ' + ($result) + ' = ' + ($compare) + '(' + ($data) + ',  ';
    if ($isData) {
      out += '' + ($schemaValue);
    } else {
      out += '' + (it.util.toQuotedString($schema));
    }
    out += ' ); if (' + ($result) + ' === undefined) ' + ($valid) + ' = false; if (' + ($valid) + ' === undefined) ' + ($valid) + ' = ' + ($result) + ' ' + ($op);
    if (!$exclusive) {
      out += '=';
    }
    out += ' 0;';
  }
  out += '' + ($closingBraces) + 'if (!' + ($valid) + ') { ';
  var $errorKeyword = $keyword;
  var $$outStack = $$outStack || [];
  $$outStack.push(out);
  out = ''; /* istanbul ignore else */
  if (it.createErrors !== false) {
    out += ' { keyword: \'' + ($errorKeyword || '_formatLimit') + '\' , dataPath: (dataPath || \'\') + ' + (it.errorPath) + ' , schemaPath: ' + (it.util.toQuotedString($errSchemaPath)) + ' , params: { comparison: ' + ($opExpr) + ', limit:  ';
    if ($isData) {
      out += '' + ($schemaValue);
    } else {
      out += '' + (it.util.toQuotedString($schema));
    }
    out += ' , exclusive: ' + ($exclusive) + ' } ';
    if (it.opts.messages !== false) {
      out += ' , message: \'should be ' + ($opStr) + ' "';
      if ($isData) {
        out += '\' + ' + ($schemaValue) + ' + \'';
      } else {
        out += '' + (it.util.escapeQuotes($schema));
      }
      out += '"\' ';
    }
    if (it.opts.verbose) {
      out += ' , schema:  ';
      if ($isData) {
        out += 'validate.schema' + ($schemaPath);
      } else {
        out += '' + (it.util.toQuotedString($schema));
      }
      out += '         , parentSchema: validate.schema' + (it.schemaPath) + ' , data: ' + ($data) + ' ';
    }
    out += ' } ';
  } else {
    out += ' {} ';
  }
  var __err = out;
  out = $$outStack.pop();
  if (!it.compositeRule && $breakOnError) { /* istanbul ignore if */
    if (it.async) {
      out += ' throw new ValidationError([' + (__err) + ']); ';
    } else {
      out += ' validate.errors = [' + (__err) + ']; return false; ';
    }
  } else {
    out += ' var err = ' + (__err) + ';  if (vErrors === null) vErrors = [err]; else vErrors.push(err); errors++; ';
  }
  out += '}';
  return out;
}


/***/ }),
/* 53 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";

module.exports = function generate_allOf(it, $keyword) {
  var out = ' ';
  var $schema = it.schema[$keyword];
  var $schemaPath = it.schemaPath + it.util.getProperty($keyword);
  var $errSchemaPath = it.errSchemaPath + '/' + $keyword;
  var $breakOnError = !it.opts.allErrors;
  var $it = it.util.copy(it);
  var $closingBraces = '';
  $it.level++;
  var $nextValid = 'valid' + $it.level;
  var $currentBaseId = $it.baseId,
    $allSchemasEmpty = true;
  var arr1 = $schema;
  if (arr1) {
    var $sch, $i = -1,
      l1 = arr1.length - 1;
    while ($i < l1) {
      $sch = arr1[$i += 1];
      if (it.util.schemaHasRules($sch, it.RULES.all)) {
        $allSchemasEmpty = false;
        $it.schema = $sch;
        $it.schemaPath = $schemaPath + '[' + $i + ']';
        $it.errSchemaPath = $errSchemaPath + '/' + $i;
        out += '  ' + (it.validate($it)) + ' ';
        $it.baseId = $currentBaseId;
        if ($breakOnError) {
          out += ' if (' + ($nextValid) + ') { ';
          $closingBraces += '}';
        }
      }
    }
  }
  if ($breakOnError) {
    if ($allSchemasEmpty) {
      out += ' if (true) { ';
    } else {
      out += ' ' + ($closingBraces.slice(0, -1)) + ' ';
    }
  }
  out = it.util.cleanUpCode(out);
  return out;
}


/***/ }),
/* 54 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";

module.exports = function generate_anyOf(it, $keyword) {
  var out = ' ';
  var $lvl = it.level;
  var $dataLvl = it.dataLevel;
  var $schema = it.schema[$keyword];
  var $schemaPath = it.schemaPath + it.util.getProperty($keyword);
  var $errSchemaPath = it.errSchemaPath + '/' + $keyword;
  var $breakOnError = !it.opts.allErrors;
  var $data = 'data' + ($dataLvl || '');
  var $valid = 'valid' + $lvl;
  var $errs = 'errs__' + $lvl;
  var $it = it.util.copy(it);
  var $closingBraces = '';
  $it.level++;
  var $nextValid = 'valid' + $it.level;
  var $noEmptySchema = $schema.every(function($sch) {
    return it.util.schemaHasRules($sch, it.RULES.all);
  });
  if ($noEmptySchema) {
    var $currentBaseId = $it.baseId;
    out += ' var ' + ($errs) + ' = errors; var ' + ($valid) + ' = false;  ';
    var $wasComposite = it.compositeRule;
    it.compositeRule = $it.compositeRule = true;
    var arr1 = $schema;
    if (arr1) {
      var $sch, $i = -1,
        l1 = arr1.length - 1;
      while ($i < l1) {
        $sch = arr1[$i += 1];
        $it.schema = $sch;
        $it.schemaPath = $schemaPath + '[' + $i + ']';
        $it.errSchemaPath = $errSchemaPath + '/' + $i;
        out += '  ' + (it.validate($it)) + ' ';
        $it.baseId = $currentBaseId;
        out += ' ' + ($valid) + ' = ' + ($valid) + ' || ' + ($nextValid) + '; if (!' + ($valid) + ') { ';
        $closingBraces += '}';
      }
    }
    it.compositeRule = $it.compositeRule = $wasComposite;
    out += ' ' + ($closingBraces) + ' if (!' + ($valid) + ') {  var err =   '; /* istanbul ignore else */
    if (it.createErrors !== false) {
      out += ' { keyword: \'' + ('anyOf') + '\' , dataPath: (dataPath || \'\') + ' + (it.errorPath) + ' , schemaPath: ' + (it.util.toQuotedString($errSchemaPath)) + ' , params: {} ';
      if (it.opts.messages !== false) {
        out += ' , message: \'should match some schema in anyOf\' ';
      }
      if (it.opts.verbose) {
        out += ' , schema: validate.schema' + ($schemaPath) + ' , parentSchema: validate.schema' + (it.schemaPath) + ' , data: ' + ($data) + ' ';
      }
      out += ' } ';
    } else {
      out += ' {} ';
    }
    out += ';  if (vErrors === null) vErrors = [err]; else vErrors.push(err); errors++; } else {  errors = ' + ($errs) + '; if (vErrors !== null) { if (' + ($errs) + ') vErrors.length = ' + ($errs) + '; else vErrors = null; } ';
    if (it.opts.allErrors) {
      out += ' } ';
    }
    out = it.util.cleanUpCode(out);
  } else {
    if ($breakOnError) {
      out += ' if (true) { ';
    }
  }
  return out;
}


/***/ }),
/* 55 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";

module.exports = function generate_constant(it, $keyword) {
  var out = ' ';
  var $lvl = it.level;
  var $dataLvl = it.dataLevel;
  var $schema = it.schema[$keyword];
  var $schemaPath = it.schemaPath + it.util.getProperty($keyword);
  var $errSchemaPath = it.errSchemaPath + '/' + $keyword;
  var $breakOnError = !it.opts.allErrors;
  var $data = 'data' + ($dataLvl || '');
  var $valid = 'valid' + $lvl;
  var $isData = it.opts.v5 && $schema && $schema.$data,
    $schemaValue;
  if ($isData) {
    out += ' var schema' + ($lvl) + ' = ' + (it.util.getData($schema.$data, $dataLvl, it.dataPathArr)) + '; ';
    $schemaValue = 'schema' + $lvl;
  } else {
    $schemaValue = $schema;
  }
  if (!$isData) {
    out += ' var schema' + ($lvl) + ' = validate.schema' + ($schemaPath) + ';';
  }
  out += 'var ' + ($valid) + ' = equal(' + ($data) + ', schema' + ($lvl) + '); if (!' + ($valid) + ') {   ';
  var $$outStack = $$outStack || [];
  $$outStack.push(out);
  out = ''; /* istanbul ignore else */
  if (it.createErrors !== false) {
    out += ' { keyword: \'' + ('constant') + '\' , dataPath: (dataPath || \'\') + ' + (it.errorPath) + ' , schemaPath: ' + (it.util.toQuotedString($errSchemaPath)) + ' , params: {} ';
    if (it.opts.messages !== false) {
      out += ' , message: \'should be equal to constant\' ';
    }
    if (it.opts.verbose) {
      out += ' , schema: validate.schema' + ($schemaPath) + ' , parentSchema: validate.schema' + (it.schemaPath) + ' , data: ' + ($data) + ' ';
    }
    out += ' } ';
  } else {
    out += ' {} ';
  }
  var __err = out;
  out = $$outStack.pop();
  if (!it.compositeRule && $breakOnError) { /* istanbul ignore if */
    if (it.async) {
      out += ' throw new ValidationError([' + (__err) + ']); ';
    } else {
      out += ' validate.errors = [' + (__err) + ']; return false; ';
    }
  } else {
    out += ' var err = ' + (__err) + ';  if (vErrors === null) vErrors = [err]; else vErrors.push(err); errors++; ';
  }
  out += ' }';
  return out;
}


/***/ }),
/* 56 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";

module.exports = function generate_custom(it, $keyword) {
  var out = ' ';
  var $lvl = it.level;
  var $dataLvl = it.dataLevel;
  var $schema = it.schema[$keyword];
  var $schemaPath = it.schemaPath + it.util.getProperty($keyword);
  var $errSchemaPath = it.errSchemaPath + '/' + $keyword;
  var $breakOnError = !it.opts.allErrors;
  var $errorKeyword;
  var $data = 'data' + ($dataLvl || '');
  var $valid = 'valid' + $lvl;
  var $errs = 'errs__' + $lvl;
  var $isData = it.opts.v5 && $schema && $schema.$data,
    $schemaValue;
  if ($isData) {
    out += ' var schema' + ($lvl) + ' = ' + (it.util.getData($schema.$data, $dataLvl, it.dataPathArr)) + '; ';
    $schemaValue = 'schema' + $lvl;
  } else {
    $schemaValue = $schema;
  }
  var $rule = this,
    $definition = 'definition' + $lvl,
    $rDef = $rule.definition;
  var $compile, $inline, $macro, $ruleValidate, $validateCode;
  if ($isData && $rDef.$data) {
    $validateCode = 'keywordValidate' + $lvl;
    var $validateSchema = $rDef.validateSchema;
    out += ' var ' + ($definition) + ' = RULES.custom[\'' + ($keyword) + '\'].definition; var ' + ($validateCode) + ' = ' + ($definition) + '.validate;';
  } else {
    $ruleValidate = it.useCustomRule($rule, $schema, it.schema, it);
    $schemaValue = 'validate.schema' + $schemaPath;
    $validateCode = $ruleValidate.code;
    $compile = $rDef.compile;
    $inline = $rDef.inline;
    $macro = $rDef.macro;
  }
  var $ruleErrs = $validateCode + '.errors',
    $i = 'i' + $lvl,
    $ruleErr = 'ruleErr' + $lvl,
    $asyncKeyword = $rDef.async;
  if ($asyncKeyword && !it.async) throw new Error('async keyword in sync schema');
  if (!($inline || $macro)) {
    out += '' + ($ruleErrs) + ' = null;';
  }
  out += 'var ' + ($errs) + ' = errors;var ' + ($valid) + ';';
  if ($validateSchema) {
    out += ' ' + ($valid) + ' = ' + ($definition) + '.validateSchema(' + ($schemaValue) + '); if (' + ($valid) + ') {';
  }
  if ($inline) {
    if ($rDef.statements) {
      out += ' ' + ($ruleValidate.validate) + ' ';
    } else {
      out += ' ' + ($valid) + ' = ' + ($ruleValidate.validate) + '; ';
    }
  } else if ($macro) {
    var $it = it.util.copy(it);
    $it.level++;
    var $nextValid = 'valid' + $it.level;
    $it.schema = $ruleValidate.validate;
    $it.schemaPath = '';
    var $wasComposite = it.compositeRule;
    it.compositeRule = $it.compositeRule = true;
    var $code = it.validate($it).replace(/validate\.schema/g, $validateCode);
    it.compositeRule = $it.compositeRule = $wasComposite;
    out += ' ' + ($code);
  } else {
    var $$outStack = $$outStack || [];
    $$outStack.push(out);
    out = '';
    out += '  ' + ($validateCode) + '.call( ';
    if (it.opts.passContext) {
      out += 'this';
    } else {
      out += 'self';
    }
    if ($compile || $rDef.schema === false) {
      out += ' , ' + ($data) + ' ';
    } else {
      out += ' , ' + ($schemaValue) + ' , ' + ($data) + ' , validate.schema' + (it.schemaPath) + ' ';
    }
    out += ' , (dataPath || \'\')';
    if (it.errorPath != '""') {
      out += ' + ' + (it.errorPath);
    }
    var $parentData = $dataLvl ? 'data' + (($dataLvl - 1) || '') : 'parentData',
      $parentDataProperty = $dataLvl ? it.dataPathArr[$dataLvl] : 'parentDataProperty';
    out += ' , ' + ($parentData) + ' , ' + ($parentDataProperty) + ' , rootData )  ';
    var def_callRuleValidate = out;
    out = $$outStack.pop();
    if ($rDef.errors === false) {
      out += ' ' + ($valid) + ' = ';
      if ($asyncKeyword) {
        out += '' + (it.yieldAwait);
      }
      out += '' + (def_callRuleValidate) + '; ';
    } else {
      if ($asyncKeyword) {
        $ruleErrs = 'customErrors' + $lvl;
        out += ' var ' + ($ruleErrs) + ' = null; try { ' + ($valid) + ' = ' + (it.yieldAwait) + (def_callRuleValidate) + '; } catch (e) { ' + ($valid) + ' = false; if (e instanceof ValidationError) ' + ($ruleErrs) + ' = e.errors; else throw e; } ';
      } else {
        out += ' ' + ($ruleErrs) + ' = null; ' + ($valid) + ' = ' + (def_callRuleValidate) + '; ';
      }
    }
  }
  if ($rDef.modifying) {
    out += ' ' + ($data) + ' = ' + ($parentData) + '[' + ($parentDataProperty) + '];';
  }
  if ($validateSchema) {
    out += ' }';
  }
  if ($rDef.valid) {
    if ($breakOnError) {
      out += ' if (true) { ';
    }
  } else {
    out += ' if ( ';
    if ($rDef.valid === undefined) {
      out += ' !';
      if ($macro) {
        out += '' + ($nextValid);
      } else {
        out += '' + ($valid);
      }
    } else {
      out += ' ' + (!$rDef.valid) + ' ';
    }
    out += ') { ';
    $errorKeyword = $rule.keyword;
    var $$outStack = $$outStack || [];
    $$outStack.push(out);
    out = '';
    var $$outStack = $$outStack || [];
    $$outStack.push(out);
    out = ''; /* istanbul ignore else */
    if (it.createErrors !== false) {
      out += ' { keyword: \'' + ($errorKeyword || 'custom') + '\' , dataPath: (dataPath || \'\') + ' + (it.errorPath) + ' , schemaPath: ' + (it.util.toQuotedString($errSchemaPath)) + ' , params: { keyword: \'' + ($rule.keyword) + '\' } ';
      if (it.opts.messages !== false) {
        out += ' , message: \'should pass "' + ($rule.keyword) + '" keyword validation\' ';
      }
      if (it.opts.verbose) {
        out += ' , schema: validate.schema' + ($schemaPath) + ' , parentSchema: validate.schema' + (it.schemaPath) + ' , data: ' + ($data) + ' ';
      }
      out += ' } ';
    } else {
      out += ' {} ';
    }
    var __err = out;
    out = $$outStack.pop();
    if (!it.compositeRule && $breakOnError) { /* istanbul ignore if */
      if (it.async) {
        out += ' throw new ValidationError([' + (__err) + ']); ';
      } else {
        out += ' validate.errors = [' + (__err) + ']; return false; ';
      }
    } else {
      out += ' var err = ' + (__err) + ';  if (vErrors === null) vErrors = [err]; else vErrors.push(err); errors++; ';
    }
    var def_customError = out;
    out = $$outStack.pop();
    if ($inline) {
      if ($rDef.errors) {
        if ($rDef.errors != 'full') {
          out += '  for (var ' + ($i) + '=' + ($errs) + '; ' + ($i) + '<errors; ' + ($i) + '++) { var ' + ($ruleErr) + ' = vErrors[' + ($i) + ']; if (' + ($ruleErr) + '.dataPath === undefined) ' + ($ruleErr) + '.dataPath = (dataPath || \'\') + ' + (it.errorPath) + '; if (' + ($ruleErr) + '.schemaPath === undefined) { ' + ($ruleErr) + '.schemaPath = "' + ($errSchemaPath) + '"; } ';
          if (it.opts.verbose) {
            out += ' ' + ($ruleErr) + '.schema = ' + ($schemaValue) + '; ' + ($ruleErr) + '.data = ' + ($data) + '; ';
          }
          out += ' } ';
        }
      } else {
        if ($rDef.errors === false) {
          out += ' ' + (def_customError) + ' ';
        } else {
          out += ' if (' + ($errs) + ' == errors) { ' + (def_customError) + ' } else {  for (var ' + ($i) + '=' + ($errs) + '; ' + ($i) + '<errors; ' + ($i) + '++) { var ' + ($ruleErr) + ' = vErrors[' + ($i) + ']; if (' + ($ruleErr) + '.dataPath === undefined) ' + ($ruleErr) + '.dataPath = (dataPath || \'\') + ' + (it.errorPath) + '; if (' + ($ruleErr) + '.schemaPath === undefined) { ' + ($ruleErr) + '.schemaPath = "' + ($errSchemaPath) + '"; } ';
          if (it.opts.verbose) {
            out += ' ' + ($ruleErr) + '.schema = ' + ($schemaValue) + '; ' + ($ruleErr) + '.data = ' + ($data) + '; ';
          }
          out += ' } } ';
        }
      }
    } else if ($macro) {
      out += '   var err =   '; /* istanbul ignore else */
      if (it.createErrors !== false) {
        out += ' { keyword: \'' + ($errorKeyword || 'custom') + '\' , dataPath: (dataPath || \'\') + ' + (it.errorPath) + ' , schemaPath: ' + (it.util.toQuotedString($errSchemaPath)) + ' , params: { keyword: \'' + ($rule.keyword) + '\' } ';
        if (it.opts.messages !== false) {
          out += ' , message: \'should pass "' + ($rule.keyword) + '" keyword validation\' ';
        }
        if (it.opts.verbose) {
          out += ' , schema: validate.schema' + ($schemaPath) + ' , parentSchema: validate.schema' + (it.schemaPath) + ' , data: ' + ($data) + ' ';
        }
        out += ' } ';
      } else {
        out += ' {} ';
      }
      out += ';  if (vErrors === null) vErrors = [err]; else vErrors.push(err); errors++; ';
      if (!it.compositeRule && $breakOnError) { /* istanbul ignore if */
        if (it.async) {
          out += ' throw new ValidationError(vErrors); ';
        } else {
          out += ' validate.errors = vErrors; return false; ';
        }
      }
    } else {
      if ($rDef.errors === false) {
        out += ' ' + (def_customError) + ' ';
      } else {
        out += ' if (Array.isArray(' + ($ruleErrs) + ')) { if (vErrors === null) vErrors = ' + ($ruleErrs) + '; else vErrors = vErrors.concat(' + ($ruleErrs) + '); errors = vErrors.length;  for (var ' + ($i) + '=' + ($errs) + '; ' + ($i) + '<errors; ' + ($i) + '++) { var ' + ($ruleErr) + ' = vErrors[' + ($i) + ']; if (' + ($ruleErr) + '.dataPath === undefined) ' + ($ruleErr) + '.dataPath = (dataPath || \'\') + ' + (it.errorPath) + ';  ' + ($ruleErr) + '.schemaPath = "' + ($errSchemaPath) + '";  ';
        if (it.opts.verbose) {
          out += ' ' + ($ruleErr) + '.schema = ' + ($schemaValue) + '; ' + ($ruleErr) + '.data = ' + ($data) + '; ';
        }
        out += ' } } else { ' + (def_customError) + ' } ';
      }
    }
    out += ' } ';
    if ($breakOnError) {
      out += ' else { ';
    }
  }
  return out;
}


/***/ }),
/* 57 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";

module.exports = function generate_dependencies(it, $keyword) {
  var out = ' ';
  var $lvl = it.level;
  var $dataLvl = it.dataLevel;
  var $schema = it.schema[$keyword];
  var $schemaPath = it.schemaPath + it.util.getProperty($keyword);
  var $errSchemaPath = it.errSchemaPath + '/' + $keyword;
  var $breakOnError = !it.opts.allErrors;
  var $data = 'data' + ($dataLvl || '');
  var $errs = 'errs__' + $lvl;
  var $it = it.util.copy(it);
  var $closingBraces = '';
  $it.level++;
  var $nextValid = 'valid' + $it.level;
  var $schemaDeps = {},
    $propertyDeps = {};
  for ($property in $schema) {
    var $sch = $schema[$property];
    var $deps = Array.isArray($sch) ? $propertyDeps : $schemaDeps;
    $deps[$property] = $sch;
  }
  out += 'var ' + ($errs) + ' = errors;';
  var $currentErrorPath = it.errorPath;
  out += 'var missing' + ($lvl) + ';';
  for (var $property in $propertyDeps) {
    $deps = $propertyDeps[$property];
    out += ' if (' + ($data) + (it.util.getProperty($property)) + ' !== undefined ';
    if ($breakOnError) {
      out += ' && ( ';
      var arr1 = $deps;
      if (arr1) {
        var _$property, $i = -1,
          l1 = arr1.length - 1;
        while ($i < l1) {
          _$property = arr1[$i += 1];
          if ($i) {
            out += ' || ';
          }
          var $prop = it.util.getProperty(_$property);
          out += ' ( ' + ($data) + ($prop) + ' === undefined && (missing' + ($lvl) + ' = ' + (it.util.toQuotedString(it.opts.jsonPointers ? _$property : $prop)) + ') ) ';
        }
      }
      out += ')) {  ';
      var $propertyPath = 'missing' + $lvl,
        $missingProperty = '\' + ' + $propertyPath + ' + \'';
      if (it.opts._errorDataPathProperty) {
        it.errorPath = it.opts.jsonPointers ? it.util.getPathExpr($currentErrorPath, $propertyPath, true) : $currentErrorPath + ' + ' + $propertyPath;
      }
      var $$outStack = $$outStack || [];
      $$outStack.push(out);
      out = ''; /* istanbul ignore else */
      if (it.createErrors !== false) {
        out += ' { keyword: \'' + ('dependencies') + '\' , dataPath: (dataPath || \'\') + ' + (it.errorPath) + ' , schemaPath: ' + (it.util.toQuotedString($errSchemaPath)) + ' , params: { property: \'' + (it.util.escapeQuotes($property)) + '\', missingProperty: \'' + ($missingProperty) + '\', depsCount: ' + ($deps.length) + ', deps: \'' + (it.util.escapeQuotes($deps.length == 1 ? $deps[0] : $deps.join(", "))) + '\' } ';
        if (it.opts.messages !== false) {
          out += ' , message: \'should have ';
          if ($deps.length == 1) {
            out += 'property ' + (it.util.escapeQuotes($deps[0]));
          } else {
            out += 'properties ' + (it.util.escapeQuotes($deps.join(", ")));
          }
          out += ' when property ' + (it.util.escapeQuotes($property)) + ' is present\' ';
        }
        if (it.opts.verbose) {
          out += ' , schema: validate.schema' + ($schemaPath) + ' , parentSchema: validate.schema' + (it.schemaPath) + ' , data: ' + ($data) + ' ';
        }
        out += ' } ';
      } else {
        out += ' {} ';
      }
      var __err = out;
      out = $$outStack.pop();
      if (!it.compositeRule && $breakOnError) { /* istanbul ignore if */
        if (it.async) {
          out += ' throw new ValidationError([' + (__err) + ']); ';
        } else {
          out += ' validate.errors = [' + (__err) + ']; return false; ';
        }
      } else {
        out += ' var err = ' + (__err) + ';  if (vErrors === null) vErrors = [err]; else vErrors.push(err); errors++; ';
      }
    } else {
      out += ' ) { ';
      var arr2 = $deps;
      if (arr2) {
        var $reqProperty, i2 = -1,
          l2 = arr2.length - 1;
        while (i2 < l2) {
          $reqProperty = arr2[i2 += 1];
          var $prop = it.util.getProperty($reqProperty),
            $missingProperty = it.util.escapeQuotes($reqProperty);
          if (it.opts._errorDataPathProperty) {
            it.errorPath = it.util.getPath($currentErrorPath, $reqProperty, it.opts.jsonPointers);
          }
          out += ' if (' + ($data) + ($prop) + ' === undefined) {  var err =   '; /* istanbul ignore else */
          if (it.createErrors !== false) {
            out += ' { keyword: \'' + ('dependencies') + '\' , dataPath: (dataPath || \'\') + ' + (it.errorPath) + ' , schemaPath: ' + (it.util.toQuotedString($errSchemaPath)) + ' , params: { property: \'' + (it.util.escapeQuotes($property)) + '\', missingProperty: \'' + ($missingProperty) + '\', depsCount: ' + ($deps.length) + ', deps: \'' + (it.util.escapeQuotes($deps.length == 1 ? $deps[0] : $deps.join(", "))) + '\' } ';
            if (it.opts.messages !== false) {
              out += ' , message: \'should have ';
              if ($deps.length == 1) {
                out += 'property ' + (it.util.escapeQuotes($deps[0]));
              } else {
                out += 'properties ' + (it.util.escapeQuotes($deps.join(", ")));
              }
              out += ' when property ' + (it.util.escapeQuotes($property)) + ' is present\' ';
            }
            if (it.opts.verbose) {
              out += ' , schema: validate.schema' + ($schemaPath) + ' , parentSchema: validate.schema' + (it.schemaPath) + ' , data: ' + ($data) + ' ';
            }
            out += ' } ';
          } else {
            out += ' {} ';
          }
          out += ';  if (vErrors === null) vErrors = [err]; else vErrors.push(err); errors++; } ';
        }
      }
    }
    out += ' }   ';
    if ($breakOnError) {
      $closingBraces += '}';
      out += ' else { ';
    }
  }
  it.errorPath = $currentErrorPath;
  var $currentBaseId = $it.baseId;
  for (var $property in $schemaDeps) {
    var $sch = $schemaDeps[$property];
    if (it.util.schemaHasRules($sch, it.RULES.all)) {
      out += ' ' + ($nextValid) + ' = true; if (' + ($data) + (it.util.getProperty($property)) + ' !== undefined) { ';
      $it.schema = $sch;
      $it.schemaPath = $schemaPath + it.util.getProperty($property);
      $it.errSchemaPath = $errSchemaPath + '/' + it.util.escapeFragment($property);
      out += '  ' + (it.validate($it)) + ' ';
      $it.baseId = $currentBaseId;
      out += ' }  ';
      if ($breakOnError) {
        out += ' if (' + ($nextValid) + ') { ';
        $closingBraces += '}';
      }
    }
  }
  if ($breakOnError) {
    out += '   ' + ($closingBraces) + ' if (' + ($errs) + ' == errors) {';
  }
  out = it.util.cleanUpCode(out);
  return out;
}


/***/ }),
/* 58 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";

module.exports = function generate_enum(it, $keyword) {
  var out = ' ';
  var $lvl = it.level;
  var $dataLvl = it.dataLevel;
  var $schema = it.schema[$keyword];
  var $schemaPath = it.schemaPath + it.util.getProperty($keyword);
  var $errSchemaPath = it.errSchemaPath + '/' + $keyword;
  var $breakOnError = !it.opts.allErrors;
  var $data = 'data' + ($dataLvl || '');
  var $valid = 'valid' + $lvl;
  var $isData = it.opts.v5 && $schema && $schema.$data,
    $schemaValue;
  if ($isData) {
    out += ' var schema' + ($lvl) + ' = ' + (it.util.getData($schema.$data, $dataLvl, it.dataPathArr)) + '; ';
    $schemaValue = 'schema' + $lvl;
  } else {
    $schemaValue = $schema;
  }
  var $i = 'i' + $lvl,
    $vSchema = 'schema' + $lvl;
  if (!$isData) {
    out += ' var ' + ($vSchema) + ' = validate.schema' + ($schemaPath) + ';';
  }
  out += 'var ' + ($valid) + ';';
  if ($isData) {
    out += ' if (schema' + ($lvl) + ' === undefined) ' + ($valid) + ' = true; else if (!Array.isArray(schema' + ($lvl) + ')) ' + ($valid) + ' = false; else {';
  }
  out += '' + ($valid) + ' = false;for (var ' + ($i) + '=0; ' + ($i) + '<' + ($vSchema) + '.length; ' + ($i) + '++) if (equal(' + ($data) + ', ' + ($vSchema) + '[' + ($i) + '])) { ' + ($valid) + ' = true; break; }';
  if ($isData) {
    out += '  }  ';
  }
  out += ' if (!' + ($valid) + ') {   ';
  var $$outStack = $$outStack || [];
  $$outStack.push(out);
  out = ''; /* istanbul ignore else */
  if (it.createErrors !== false) {
    out += ' { keyword: \'' + ('enum') + '\' , dataPath: (dataPath || \'\') + ' + (it.errorPath) + ' , schemaPath: ' + (it.util.toQuotedString($errSchemaPath)) + ' , params: { allowedValues: schema' + ($lvl) + ' } ';
    if (it.opts.messages !== false) {
      out += ' , message: \'should be equal to one of the allowed values\' ';
    }
    if (it.opts.verbose) {
      out += ' , schema: validate.schema' + ($schemaPath) + ' , parentSchema: validate.schema' + (it.schemaPath) + ' , data: ' + ($data) + ' ';
    }
    out += ' } ';
  } else {
    out += ' {} ';
  }
  var __err = out;
  out = $$outStack.pop();
  if (!it.compositeRule && $breakOnError) { /* istanbul ignore if */
    if (it.async) {
      out += ' throw new ValidationError([' + (__err) + ']); ';
    } else {
      out += ' validate.errors = [' + (__err) + ']; return false; ';
    }
  } else {
    out += ' var err = ' + (__err) + ';  if (vErrors === null) vErrors = [err]; else vErrors.push(err); errors++; ';
  }
  out += ' }';
  if ($breakOnError) {
    out += ' else { ';
  }
  return out;
}


/***/ }),
/* 59 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";

module.exports = function generate_format(it, $keyword) {
  var out = ' ';
  var $lvl = it.level;
  var $dataLvl = it.dataLevel;
  var $schema = it.schema[$keyword];
  var $schemaPath = it.schemaPath + it.util.getProperty($keyword);
  var $errSchemaPath = it.errSchemaPath + '/' + $keyword;
  var $breakOnError = !it.opts.allErrors;
  var $data = 'data' + ($dataLvl || '');
  if (it.opts.format === false) {
    if ($breakOnError) {
      out += ' if (true) { ';
    }
    return out;
  }
  var $isData = it.opts.v5 && $schema && $schema.$data,
    $schemaValue;
  if ($isData) {
    out += ' var schema' + ($lvl) + ' = ' + (it.util.getData($schema.$data, $dataLvl, it.dataPathArr)) + '; ';
    $schemaValue = 'schema' + $lvl;
  } else {
    $schemaValue = $schema;
  }
  var $unknownFormats = it.opts.unknownFormats,
    $allowUnknown = Array.isArray($unknownFormats);
  if ($isData) {
    var $format = 'format' + $lvl;
    out += ' var ' + ($format) + ' = formats[' + ($schemaValue) + ']; var isObject' + ($lvl) + ' = typeof ' + ($format) + ' == \'object\' && !(' + ($format) + ' instanceof RegExp) && ' + ($format) + '.validate; if (isObject' + ($lvl) + ') { ';
    if (it.async) {
      out += ' var async' + ($lvl) + ' = ' + ($format) + '.async; ';
    }
    out += ' ' + ($format) + ' = ' + ($format) + '.validate; } if (  ';
    if ($isData) {
      out += ' (' + ($schemaValue) + ' !== undefined && typeof ' + ($schemaValue) + ' != \'string\') || ';
    }
    out += ' (';
    if ($unknownFormats === true || $allowUnknown) {
      out += ' (' + ($schemaValue) + ' && !' + ($format) + ' ';
      if ($allowUnknown) {
        out += ' && self._opts.unknownFormats.indexOf(' + ($schemaValue) + ') == -1 ';
      }
      out += ') || ';
    }
    out += ' (' + ($format) + ' && !(typeof ' + ($format) + ' == \'function\' ? ';
    if (it.async) {
      out += ' (async' + ($lvl) + ' ? ' + (it.yieldAwait) + ' ' + ($format) + '(' + ($data) + ') : ' + ($format) + '(' + ($data) + ')) ';
    } else {
      out += ' ' + ($format) + '(' + ($data) + ') ';
    }
    out += ' : ' + ($format) + '.test(' + ($data) + '))))) {';
  } else {
    var $format = it.formats[$schema];
    if (!$format) {
      if ($unknownFormats === true || ($allowUnknown && $unknownFormats.indexOf($schema) == -1)) {
        throw new Error('unknown format "' + $schema + '" is used in schema at path "' + it.errSchemaPath + '"');
      } else {
        if (!$allowUnknown) {
          console.warn('unknown format "' + $schema + '" ignored in schema at path "' + it.errSchemaPath + '"');
          if ($unknownFormats !== 'ignore') console.warn('In the next major version it will throw exception. See option unknownFormats for more information');
        }
        if ($breakOnError) {
          out += ' if (true) { ';
        }
        return out;
      }
    }
    var $isObject = typeof $format == 'object' && !($format instanceof RegExp) && $format.validate;
    if ($isObject) {
      var $async = $format.async === true;
      $format = $format.validate;
    }
    if ($async) {
      if (!it.async) throw new Error('async format in sync schema');
      var $formatRef = 'formats' + it.util.getProperty($schema) + '.validate';
      out += ' if (!(' + (it.yieldAwait) + ' ' + ($formatRef) + '(' + ($data) + '))) { ';
    } else {
      out += ' if (! ';
      var $formatRef = 'formats' + it.util.getProperty($schema);
      if ($isObject) $formatRef += '.validate';
      if (typeof $format == 'function') {
        out += ' ' + ($formatRef) + '(' + ($data) + ') ';
      } else {
        out += ' ' + ($formatRef) + '.test(' + ($data) + ') ';
      }
      out += ') { ';
    }
  }
  var $$outStack = $$outStack || [];
  $$outStack.push(out);
  out = ''; /* istanbul ignore else */
  if (it.createErrors !== false) {
    out += ' { keyword: \'' + ('format') + '\' , dataPath: (dataPath || \'\') + ' + (it.errorPath) + ' , schemaPath: ' + (it.util.toQuotedString($errSchemaPath)) + ' , params: { format:  ';
    if ($isData) {
      out += '' + ($schemaValue);
    } else {
      out += '' + (it.util.toQuotedString($schema));
    }
    out += '  } ';
    if (it.opts.messages !== false) {
      out += ' , message: \'should match format "';
      if ($isData) {
        out += '\' + ' + ($schemaValue) + ' + \'';
      } else {
        out += '' + (it.util.escapeQuotes($schema));
      }
      out += '"\' ';
    }
    if (it.opts.verbose) {
      out += ' , schema:  ';
      if ($isData) {
        out += 'validate.schema' + ($schemaPath);
      } else {
        out += '' + (it.util.toQuotedString($schema));
      }
      out += '         , parentSchema: validate.schema' + (it.schemaPath) + ' , data: ' + ($data) + ' ';
    }
    out += ' } ';
  } else {
    out += ' {} ';
  }
  var __err = out;
  out = $$outStack.pop();
  if (!it.compositeRule && $breakOnError) { /* istanbul ignore if */
    if (it.async) {
      out += ' throw new ValidationError([' + (__err) + ']); ';
    } else {
      out += ' validate.errors = [' + (__err) + ']; return false; ';
    }
  } else {
    out += ' var err = ' + (__err) + ';  if (vErrors === null) vErrors = [err]; else vErrors.push(err); errors++; ';
  }
  out += ' } ';
  if ($breakOnError) {
    out += ' else { ';
  }
  return out;
}


/***/ }),
/* 60 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";

module.exports = function generate_items(it, $keyword) {
  var out = ' ';
  var $lvl = it.level;
  var $dataLvl = it.dataLevel;
  var $schema = it.schema[$keyword];
  var $schemaPath = it.schemaPath + it.util.getProperty($keyword);
  var $errSchemaPath = it.errSchemaPath + '/' + $keyword;
  var $breakOnError = !it.opts.allErrors;
  var $data = 'data' + ($dataLvl || '');
  var $valid = 'valid' + $lvl;
  var $errs = 'errs__' + $lvl;
  var $it = it.util.copy(it);
  var $closingBraces = '';
  $it.level++;
  var $nextValid = 'valid' + $it.level;
  var $idx = 'i' + $lvl,
    $dataNxt = $it.dataLevel = it.dataLevel + 1,
    $nextData = 'data' + $dataNxt,
    $currentBaseId = it.baseId;
  out += 'var ' + ($errs) + ' = errors;var ' + ($valid) + ';';
  if (Array.isArray($schema)) {
    var $additionalItems = it.schema.additionalItems;
    if ($additionalItems === false) {
      out += ' ' + ($valid) + ' = ' + ($data) + '.length <= ' + ($schema.length) + '; ';
      var $currErrSchemaPath = $errSchemaPath;
      $errSchemaPath = it.errSchemaPath + '/additionalItems';
      out += '  if (!' + ($valid) + ') {   ';
      var $$outStack = $$outStack || [];
      $$outStack.push(out);
      out = ''; /* istanbul ignore else */
      if (it.createErrors !== false) {
        out += ' { keyword: \'' + ('additionalItems') + '\' , dataPath: (dataPath || \'\') + ' + (it.errorPath) + ' , schemaPath: ' + (it.util.toQuotedString($errSchemaPath)) + ' , params: { limit: ' + ($schema.length) + ' } ';
        if (it.opts.messages !== false) {
          out += ' , message: \'should NOT have more than ' + ($schema.length) + ' items\' ';
        }
        if (it.opts.verbose) {
          out += ' , schema: false , parentSchema: validate.schema' + (it.schemaPath) + ' , data: ' + ($data) + ' ';
        }
        out += ' } ';
      } else {
        out += ' {} ';
      }
      var __err = out;
      out = $$outStack.pop();
      if (!it.compositeRule && $breakOnError) { /* istanbul ignore if */
        if (it.async) {
          out += ' throw new ValidationError([' + (__err) + ']); ';
        } else {
          out += ' validate.errors = [' + (__err) + ']; return false; ';
        }
      } else {
        out += ' var err = ' + (__err) + ';  if (vErrors === null) vErrors = [err]; else vErrors.push(err); errors++; ';
      }
      out += ' } ';
      $errSchemaPath = $currErrSchemaPath;
      if ($breakOnError) {
        $closingBraces += '}';
        out += ' else { ';
      }
    }
    var arr1 = $schema;
    if (arr1) {
      var $sch, $i = -1,
        l1 = arr1.length - 1;
      while ($i < l1) {
        $sch = arr1[$i += 1];
        if (it.util.schemaHasRules($sch, it.RULES.all)) {
          out += ' ' + ($nextValid) + ' = true; if (' + ($data) + '.length > ' + ($i) + ') { ';
          var $passData = $data + '[' + $i + ']';
          $it.schema = $sch;
          $it.schemaPath = $schemaPath + '[' + $i + ']';
          $it.errSchemaPath = $errSchemaPath + '/' + $i;
          $it.errorPath = it.util.getPathExpr(it.errorPath, $i, it.opts.jsonPointers, true);
          $it.dataPathArr[$dataNxt] = $i;
          var $code = it.validate($it);
          $it.baseId = $currentBaseId;
          if (it.util.varOccurences($code, $nextData) < 2) {
            out += ' ' + (it.util.varReplace($code, $nextData, $passData)) + ' ';
          } else {
            out += ' var ' + ($nextData) + ' = ' + ($passData) + '; ' + ($code) + ' ';
          }
          out += ' }  ';
          if ($breakOnError) {
            out += ' if (' + ($nextValid) + ') { ';
            $closingBraces += '}';
          }
        }
      }
    }
    if (typeof $additionalItems == 'object' && it.util.schemaHasRules($additionalItems, it.RULES.all)) {
      $it.schema = $additionalItems;
      $it.schemaPath = it.schemaPath + '.additionalItems';
      $it.errSchemaPath = it.errSchemaPath + '/additionalItems';
      out += ' ' + ($nextValid) + ' = true; if (' + ($data) + '.length > ' + ($schema.length) + ') {  for (var ' + ($idx) + ' = ' + ($schema.length) + '; ' + ($idx) + ' < ' + ($data) + '.length; ' + ($idx) + '++) { ';
      $it.errorPath = it.util.getPathExpr(it.errorPath, $idx, it.opts.jsonPointers, true);
      var $passData = $data + '[' + $idx + ']';
      $it.dataPathArr[$dataNxt] = $idx;
      var $code = it.validate($it);
      $it.baseId = $currentBaseId;
      if (it.util.varOccurences($code, $nextData) < 2) {
        out += ' ' + (it.util.varReplace($code, $nextData, $passData)) + ' ';
      } else {
        out += ' var ' + ($nextData) + ' = ' + ($passData) + '; ' + ($code) + ' ';
      }
      if ($breakOnError) {
        out += ' if (!' + ($nextValid) + ') break; ';
      }
      out += ' } }  ';
      if ($breakOnError) {
        out += ' if (' + ($nextValid) + ') { ';
        $closingBraces += '}';
      }
    }
  } else if (it.util.schemaHasRules($schema, it.RULES.all)) {
    $it.schema = $schema;
    $it.schemaPath = $schemaPath;
    $it.errSchemaPath = $errSchemaPath;
    out += '  for (var ' + ($idx) + ' = ' + (0) + '; ' + ($idx) + ' < ' + ($data) + '.length; ' + ($idx) + '++) { ';
    $it.errorPath = it.util.getPathExpr(it.errorPath, $idx, it.opts.jsonPointers, true);
    var $passData = $data + '[' + $idx + ']';
    $it.dataPathArr[$dataNxt] = $idx;
    var $code = it.validate($it);
    $it.baseId = $currentBaseId;
    if (it.util.varOccurences($code, $nextData) < 2) {
      out += ' ' + (it.util.varReplace($code, $nextData, $passData)) + ' ';
    } else {
      out += ' var ' + ($nextData) + ' = ' + ($passData) + '; ' + ($code) + ' ';
    }
    if ($breakOnError) {
      out += ' if (!' + ($nextValid) + ') break; ';
    }
    out += ' }  ';
    if ($breakOnError) {
      out += ' if (' + ($nextValid) + ') { ';
      $closingBraces += '}';
    }
  }
  if ($breakOnError) {
    out += ' ' + ($closingBraces) + ' if (' + ($errs) + ' == errors) {';
  }
  out = it.util.cleanUpCode(out);
  return out;
}


/***/ }),
/* 61 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";

module.exports = function generate_multipleOf(it, $keyword) {
  var out = ' ';
  var $lvl = it.level;
  var $dataLvl = it.dataLevel;
  var $schema = it.schema[$keyword];
  var $schemaPath = it.schemaPath + it.util.getProperty($keyword);
  var $errSchemaPath = it.errSchemaPath + '/' + $keyword;
  var $breakOnError = !it.opts.allErrors;
  var $data = 'data' + ($dataLvl || '');
  var $isData = it.opts.v5 && $schema && $schema.$data,
    $schemaValue;
  if ($isData) {
    out += ' var schema' + ($lvl) + ' = ' + (it.util.getData($schema.$data, $dataLvl, it.dataPathArr)) + '; ';
    $schemaValue = 'schema' + $lvl;
  } else {
    $schemaValue = $schema;
  }
  out += 'var division' + ($lvl) + ';if (';
  if ($isData) {
    out += ' ' + ($schemaValue) + ' !== undefined && ( typeof ' + ($schemaValue) + ' != \'number\' || ';
  }
  out += ' (division' + ($lvl) + ' = ' + ($data) + ' / ' + ($schemaValue) + ', ';
  if (it.opts.multipleOfPrecision) {
    out += ' Math.abs(Math.round(division' + ($lvl) + ') - division' + ($lvl) + ') > 1e-' + (it.opts.multipleOfPrecision) + ' ';
  } else {
    out += ' division' + ($lvl) + ' !== parseInt(division' + ($lvl) + ') ';
  }
  out += ' ) ';
  if ($isData) {
    out += '  )  ';
  }
  out += ' ) {   ';
  var $$outStack = $$outStack || [];
  $$outStack.push(out);
  out = ''; /* istanbul ignore else */
  if (it.createErrors !== false) {
    out += ' { keyword: \'' + ('multipleOf') + '\' , dataPath: (dataPath || \'\') + ' + (it.errorPath) + ' , schemaPath: ' + (it.util.toQuotedString($errSchemaPath)) + ' , params: { multipleOf: ' + ($schemaValue) + ' } ';
    if (it.opts.messages !== false) {
      out += ' , message: \'should be multiple of ';
      if ($isData) {
        out += '\' + ' + ($schemaValue);
      } else {
        out += '' + ($schema) + '\'';
      }
    }
    if (it.opts.verbose) {
      out += ' , schema:  ';
      if ($isData) {
        out += 'validate.schema' + ($schemaPath);
      } else {
        out += '' + ($schema);
      }
      out += '         , parentSchema: validate.schema' + (it.schemaPath) + ' , data: ' + ($data) + ' ';
    }
    out += ' } ';
  } else {
    out += ' {} ';
  }
  var __err = out;
  out = $$outStack.pop();
  if (!it.compositeRule && $breakOnError) { /* istanbul ignore if */
    if (it.async) {
      out += ' throw new ValidationError([' + (__err) + ']); ';
    } else {
      out += ' validate.errors = [' + (__err) + ']; return false; ';
    }
  } else {
    out += ' var err = ' + (__err) + ';  if (vErrors === null) vErrors = [err]; else vErrors.push(err); errors++; ';
  }
  out += '} ';
  if ($breakOnError) {
    out += ' else { ';
  }
  return out;
}


/***/ }),
/* 62 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";

module.exports = function generate_not(it, $keyword) {
  var out = ' ';
  var $lvl = it.level;
  var $dataLvl = it.dataLevel;
  var $schema = it.schema[$keyword];
  var $schemaPath = it.schemaPath + it.util.getProperty($keyword);
  var $errSchemaPath = it.errSchemaPath + '/' + $keyword;
  var $breakOnError = !it.opts.allErrors;
  var $data = 'data' + ($dataLvl || '');
  var $errs = 'errs__' + $lvl;
  var $it = it.util.copy(it);
  $it.level++;
  var $nextValid = 'valid' + $it.level;
  if (it.util.schemaHasRules($schema, it.RULES.all)) {
    $it.schema = $schema;
    $it.schemaPath = $schemaPath;
    $it.errSchemaPath = $errSchemaPath;
    out += ' var ' + ($errs) + ' = errors;  ';
    var $wasComposite = it.compositeRule;
    it.compositeRule = $it.compositeRule = true;
    $it.createErrors = false;
    var $allErrorsOption;
    if ($it.opts.allErrors) {
      $allErrorsOption = $it.opts.allErrors;
      $it.opts.allErrors = false;
    }
    out += ' ' + (it.validate($it)) + ' ';
    $it.createErrors = true;
    if ($allErrorsOption) $it.opts.allErrors = $allErrorsOption;
    it.compositeRule = $it.compositeRule = $wasComposite;
    out += ' if (' + ($nextValid) + ') {   ';
    var $$outStack = $$outStack || [];
    $$outStack.push(out);
    out = ''; /* istanbul ignore else */
    if (it.createErrors !== false) {
      out += ' { keyword: \'' + ('not') + '\' , dataPath: (dataPath || \'\') + ' + (it.errorPath) + ' , schemaPath: ' + (it.util.toQuotedString($errSchemaPath)) + ' , params: {} ';
      if (it.opts.messages !== false) {
        out += ' , message: \'should NOT be valid\' ';
      }
      if (it.opts.verbose) {
        out += ' , schema: validate.schema' + ($schemaPath) + ' , parentSchema: validate.schema' + (it.schemaPath) + ' , data: ' + ($data) + ' ';
      }
      out += ' } ';
    } else {
      out += ' {} ';
    }
    var __err = out;
    out = $$outStack.pop();
    if (!it.compositeRule && $breakOnError) { /* istanbul ignore if */
      if (it.async) {
        out += ' throw new ValidationError([' + (__err) + ']); ';
      } else {
        out += ' validate.errors = [' + (__err) + ']; return false; ';
      }
    } else {
      out += ' var err = ' + (__err) + ';  if (vErrors === null) vErrors = [err]; else vErrors.push(err); errors++; ';
    }
    out += ' } else {  errors = ' + ($errs) + '; if (vErrors !== null) { if (' + ($errs) + ') vErrors.length = ' + ($errs) + '; else vErrors = null; } ';
    if (it.opts.allErrors) {
      out += ' } ';
    }
  } else {
    out += '  var err =   '; /* istanbul ignore else */
    if (it.createErrors !== false) {
      out += ' { keyword: \'' + ('not') + '\' , dataPath: (dataPath || \'\') + ' + (it.errorPath) + ' , schemaPath: ' + (it.util.toQuotedString($errSchemaPath)) + ' , params: {} ';
      if (it.opts.messages !== false) {
        out += ' , message: \'should NOT be valid\' ';
      }
      if (it.opts.verbose) {
        out += ' , schema: validate.schema' + ($schemaPath) + ' , parentSchema: validate.schema' + (it.schemaPath) + ' , data: ' + ($data) + ' ';
      }
      out += ' } ';
    } else {
      out += ' {} ';
    }
    out += ';  if (vErrors === null) vErrors = [err]; else vErrors.push(err); errors++; ';
    if ($breakOnError) {
      out += ' if (false) { ';
    }
  }
  return out;
}


/***/ }),
/* 63 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";

module.exports = function generate_oneOf(it, $keyword) {
  var out = ' ';
  var $lvl = it.level;
  var $dataLvl = it.dataLevel;
  var $schema = it.schema[$keyword];
  var $schemaPath = it.schemaPath + it.util.getProperty($keyword);
  var $errSchemaPath = it.errSchemaPath + '/' + $keyword;
  var $breakOnError = !it.opts.allErrors;
  var $data = 'data' + ($dataLvl || '');
  var $valid = 'valid' + $lvl;
  var $errs = 'errs__' + $lvl;
  var $it = it.util.copy(it);
  var $closingBraces = '';
  $it.level++;
  var $nextValid = 'valid' + $it.level;
  out += 'var ' + ($errs) + ' = errors;var prevValid' + ($lvl) + ' = false;var ' + ($valid) + ' = false;';
  var $currentBaseId = $it.baseId;
  var $wasComposite = it.compositeRule;
  it.compositeRule = $it.compositeRule = true;
  var arr1 = $schema;
  if (arr1) {
    var $sch, $i = -1,
      l1 = arr1.length - 1;
    while ($i < l1) {
      $sch = arr1[$i += 1];
      if (it.util.schemaHasRules($sch, it.RULES.all)) {
        $it.schema = $sch;
        $it.schemaPath = $schemaPath + '[' + $i + ']';
        $it.errSchemaPath = $errSchemaPath + '/' + $i;
        out += '  ' + (it.validate($it)) + ' ';
        $it.baseId = $currentBaseId;
      } else {
        out += ' var ' + ($nextValid) + ' = true; ';
      }
      if ($i) {
        out += ' if (' + ($nextValid) + ' && prevValid' + ($lvl) + ') ' + ($valid) + ' = false; else { ';
        $closingBraces += '}';
      }
      out += ' if (' + ($nextValid) + ') ' + ($valid) + ' = prevValid' + ($lvl) + ' = true;';
    }
  }
  it.compositeRule = $it.compositeRule = $wasComposite;
  out += '' + ($closingBraces) + 'if (!' + ($valid) + ') {   ';
  var $$outStack = $$outStack || [];
  $$outStack.push(out);
  out = ''; /* istanbul ignore else */
  if (it.createErrors !== false) {
    out += ' { keyword: \'' + ('oneOf') + '\' , dataPath: (dataPath || \'\') + ' + (it.errorPath) + ' , schemaPath: ' + (it.util.toQuotedString($errSchemaPath)) + ' , params: {} ';
    if (it.opts.messages !== false) {
      out += ' , message: \'should match exactly one schema in oneOf\' ';
    }
    if (it.opts.verbose) {
      out += ' , schema: validate.schema' + ($schemaPath) + ' , parentSchema: validate.schema' + (it.schemaPath) + ' , data: ' + ($data) + ' ';
    }
    out += ' } ';
  } else {
    out += ' {} ';
  }
  var __err = out;
  out = $$outStack.pop();
  if (!it.compositeRule && $breakOnError) { /* istanbul ignore if */
    if (it.async) {
      out += ' throw new ValidationError([' + (__err) + ']); ';
    } else {
      out += ' validate.errors = [' + (__err) + ']; return false; ';
    }
  } else {
    out += ' var err = ' + (__err) + ';  if (vErrors === null) vErrors = [err]; else vErrors.push(err); errors++; ';
  }
  out += '} else {  errors = ' + ($errs) + '; if (vErrors !== null) { if (' + ($errs) + ') vErrors.length = ' + ($errs) + '; else vErrors = null; }';
  if (it.opts.allErrors) {
    out += ' } ';
  }
  return out;
}


/***/ }),
/* 64 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";

module.exports = function generate_pattern(it, $keyword) {
  var out = ' ';
  var $lvl = it.level;
  var $dataLvl = it.dataLevel;
  var $schema = it.schema[$keyword];
  var $schemaPath = it.schemaPath + it.util.getProperty($keyword);
  var $errSchemaPath = it.errSchemaPath + '/' + $keyword;
  var $breakOnError = !it.opts.allErrors;
  var $data = 'data' + ($dataLvl || '');
  var $isData = it.opts.v5 && $schema && $schema.$data,
    $schemaValue;
  if ($isData) {
    out += ' var schema' + ($lvl) + ' = ' + (it.util.getData($schema.$data, $dataLvl, it.dataPathArr)) + '; ';
    $schemaValue = 'schema' + $lvl;
  } else {
    $schemaValue = $schema;
  }
  var $regexp = $isData ? '(new RegExp(' + $schemaValue + '))' : it.usePattern($schema);
  out += 'if ( ';
  if ($isData) {
    out += ' (' + ($schemaValue) + ' !== undefined && typeof ' + ($schemaValue) + ' != \'string\') || ';
  }
  out += ' !' + ($regexp) + '.test(' + ($data) + ') ) {   ';
  var $$outStack = $$outStack || [];
  $$outStack.push(out);
  out = ''; /* istanbul ignore else */
  if (it.createErrors !== false) {
    out += ' { keyword: \'' + ('pattern') + '\' , dataPath: (dataPath || \'\') + ' + (it.errorPath) + ' , schemaPath: ' + (it.util.toQuotedString($errSchemaPath)) + ' , params: { pattern:  ';
    if ($isData) {
      out += '' + ($schemaValue);
    } else {
      out += '' + (it.util.toQuotedString($schema));
    }
    out += '  } ';
    if (it.opts.messages !== false) {
      out += ' , message: \'should match pattern "';
      if ($isData) {
        out += '\' + ' + ($schemaValue) + ' + \'';
      } else {
        out += '' + (it.util.escapeQuotes($schema));
      }
      out += '"\' ';
    }
    if (it.opts.verbose) {
      out += ' , schema:  ';
      if ($isData) {
        out += 'validate.schema' + ($schemaPath);
      } else {
        out += '' + (it.util.toQuotedString($schema));
      }
      out += '         , parentSchema: validate.schema' + (it.schemaPath) + ' , data: ' + ($data) + ' ';
    }
    out += ' } ';
  } else {
    out += ' {} ';
  }
  var __err = out;
  out = $$outStack.pop();
  if (!it.compositeRule && $breakOnError) { /* istanbul ignore if */
    if (it.async) {
      out += ' throw new ValidationError([' + (__err) + ']); ';
    } else {
      out += ' validate.errors = [' + (__err) + ']; return false; ';
    }
  } else {
    out += ' var err = ' + (__err) + ';  if (vErrors === null) vErrors = [err]; else vErrors.push(err); errors++; ';
  }
  out += '} ';
  if ($breakOnError) {
    out += ' else { ';
  }
  return out;
}


/***/ }),
/* 65 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";

module.exports = function generate_patternRequired(it, $keyword) {
  var out = ' ';
  var $lvl = it.level;
  var $dataLvl = it.dataLevel;
  var $schema = it.schema[$keyword];
  var $schemaPath = it.schemaPath + it.util.getProperty($keyword);
  var $errSchemaPath = it.errSchemaPath + '/' + $keyword;
  var $breakOnError = !it.opts.allErrors;
  var $data = 'data' + ($dataLvl || '');
  var $valid = 'valid' + $lvl;
  var $key = 'key' + $lvl,
    $matched = 'patternMatched' + $lvl,
    $closingBraces = '',
    $ownProperties = it.opts.ownProperties;
  out += 'var ' + ($valid) + ' = true;';
  var arr1 = $schema;
  if (arr1) {
    var $pProperty, i1 = -1,
      l1 = arr1.length - 1;
    while (i1 < l1) {
      $pProperty = arr1[i1 += 1];
      out += ' var ' + ($matched) + ' = false; for (var ' + ($key) + ' in ' + ($data) + ') {  ';
      if ($ownProperties) {
        out += ' if (!Object.prototype.hasOwnProperty.call(' + ($data) + ', ' + ($key) + ')) continue; ';
      }
      out += ' ' + ($matched) + ' = ' + (it.usePattern($pProperty)) + '.test(' + ($key) + '); if (' + ($matched) + ') break; } ';
      var $missingPattern = it.util.escapeQuotes($pProperty);
      out += ' if (!' + ($matched) + ') { ' + ($valid) + ' = false;  var err =   '; /* istanbul ignore else */
      if (it.createErrors !== false) {
        out += ' { keyword: \'' + ('patternRequired') + '\' , dataPath: (dataPath || \'\') + ' + (it.errorPath) + ' , schemaPath: ' + (it.util.toQuotedString($errSchemaPath)) + ' , params: { missingPattern: \'' + ($missingPattern) + '\' } ';
        if (it.opts.messages !== false) {
          out += ' , message: \'should have property matching pattern \\\'' + ($missingPattern) + '\\\'\' ';
        }
        if (it.opts.verbose) {
          out += ' , schema: validate.schema' + ($schemaPath) + ' , parentSchema: validate.schema' + (it.schemaPath) + ' , data: ' + ($data) + ' ';
        }
        out += ' } ';
      } else {
        out += ' {} ';
      }
      out += ';  if (vErrors === null) vErrors = [err]; else vErrors.push(err); errors++; }   ';
      if ($breakOnError) {
        $closingBraces += '}';
        out += ' else { ';
      }
    }
  }
  out += '' + ($closingBraces);
  return out;
}


/***/ }),
/* 66 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";

module.exports = function generate_properties(it, $keyword) {
  var out = ' ';
  var $lvl = it.level;
  var $dataLvl = it.dataLevel;
  var $schema = it.schema[$keyword];
  var $schemaPath = it.schemaPath + it.util.getProperty($keyword);
  var $errSchemaPath = it.errSchemaPath + '/' + $keyword;
  var $breakOnError = !it.opts.allErrors;
  var $data = 'data' + ($dataLvl || '');
  var $valid = 'valid' + $lvl;
  var $errs = 'errs__' + $lvl;
  var $it = it.util.copy(it);
  var $closingBraces = '';
  $it.level++;
  var $nextValid = 'valid' + $it.level;
  var $key = 'key' + $lvl,
    $dataNxt = $it.dataLevel = it.dataLevel + 1,
    $nextData = 'data' + $dataNxt;
  var $schemaKeys = Object.keys($schema || {}),
    $pProperties = it.schema.patternProperties || {},
    $pPropertyKeys = Object.keys($pProperties),
    $aProperties = it.schema.additionalProperties,
    $someProperties = $schemaKeys.length || $pPropertyKeys.length,
    $noAdditional = $aProperties === false,
    $additionalIsSchema = typeof $aProperties == 'object' && Object.keys($aProperties).length,
    $removeAdditional = it.opts.removeAdditional,
    $checkAdditional = $noAdditional || $additionalIsSchema || $removeAdditional,
    $ownProperties = it.opts.ownProperties,
    $currentBaseId = it.baseId;
  var $required = it.schema.required;
  if ($required && !(it.opts.v5 && $required.$data) && $required.length < it.opts.loopRequired) var $requiredHash = it.util.toHash($required);
  if (it.opts.v5) {
    var $pgProperties = it.schema.patternGroups || {},
      $pgPropertyKeys = Object.keys($pgProperties);
  }
  out += 'var ' + ($errs) + ' = errors;var ' + ($nextValid) + ' = true;';
  if ($checkAdditional) {
    out += ' for (var ' + ($key) + ' in ' + ($data) + ') {  ';
    if ($ownProperties) {
      out += ' if (!Object.prototype.hasOwnProperty.call(' + ($data) + ', ' + ($key) + ')) continue; ';
    }
    if ($someProperties) {
      out += ' var isAdditional' + ($lvl) + ' = !(false ';
      if ($schemaKeys.length) {
        if ($schemaKeys.length > 5) {
          out += ' || validate.schema' + ($schemaPath) + '[' + ($key) + '] ';
        } else {
          var arr1 = $schemaKeys;
          if (arr1) {
            var $propertyKey, i1 = -1,
              l1 = arr1.length - 1;
            while (i1 < l1) {
              $propertyKey = arr1[i1 += 1];
              out += ' || ' + ($key) + ' == ' + (it.util.toQuotedString($propertyKey)) + ' ';
            }
          }
        }
      }
      if ($pPropertyKeys.length) {
        var arr2 = $pPropertyKeys;
        if (arr2) {
          var $pProperty, $i = -1,
            l2 = arr2.length - 1;
          while ($i < l2) {
            $pProperty = arr2[$i += 1];
            out += ' || ' + (it.usePattern($pProperty)) + '.test(' + ($key) + ') ';
          }
        }
      }
      if (it.opts.v5 && $pgPropertyKeys && $pgPropertyKeys.length) {
        var arr3 = $pgPropertyKeys;
        if (arr3) {
          var $pgProperty, $i = -1,
            l3 = arr3.length - 1;
          while ($i < l3) {
            $pgProperty = arr3[$i += 1];
            out += ' || ' + (it.usePattern($pgProperty)) + '.test(' + ($key) + ') ';
          }
        }
      }
      out += ' ); if (isAdditional' + ($lvl) + ') { ';
    }
    if ($removeAdditional == 'all') {
      out += ' delete ' + ($data) + '[' + ($key) + ']; ';
    } else {
      var $currentErrorPath = it.errorPath;
      var $additionalProperty = '\' + ' + $key + ' + \'';
      if (it.opts._errorDataPathProperty) {
        it.errorPath = it.util.getPathExpr(it.errorPath, $key, it.opts.jsonPointers);
      }
      if ($noAdditional) {
        if ($removeAdditional) {
          out += ' delete ' + ($data) + '[' + ($key) + ']; ';
        } else {
          out += ' ' + ($nextValid) + ' = false; ';
          var $currErrSchemaPath = $errSchemaPath;
          $errSchemaPath = it.errSchemaPath + '/additionalProperties';
          var $$outStack = $$outStack || [];
          $$outStack.push(out);
          out = ''; /* istanbul ignore else */
          if (it.createErrors !== false) {
            out += ' { keyword: \'' + ('additionalProperties') + '\' , dataPath: (dataPath || \'\') + ' + (it.errorPath) + ' , schemaPath: ' + (it.util.toQuotedString($errSchemaPath)) + ' , params: { additionalProperty: \'' + ($additionalProperty) + '\' } ';
            if (it.opts.messages !== false) {
              out += ' , message: \'should NOT have additional properties\' ';
            }
            if (it.opts.verbose) {
              out += ' , schema: false , parentSchema: validate.schema' + (it.schemaPath) + ' , data: ' + ($data) + ' ';
            }
            out += ' } ';
          } else {
            out += ' {} ';
          }
          var __err = out;
          out = $$outStack.pop();
          if (!it.compositeRule && $breakOnError) { /* istanbul ignore if */
            if (it.async) {
              out += ' throw new ValidationError([' + (__err) + ']); ';
            } else {
              out += ' validate.errors = [' + (__err) + ']; return false; ';
            }
          } else {
            out += ' var err = ' + (__err) + ';  if (vErrors === null) vErrors = [err]; else vErrors.push(err); errors++; ';
          }
          $errSchemaPath = $currErrSchemaPath;
          if ($breakOnError) {
            out += ' break; ';
          }
        }
      } else if ($additionalIsSchema) {
        if ($removeAdditional == 'failing') {
          out += ' var ' + ($errs) + ' = errors;  ';
          var $wasComposite = it.compositeRule;
          it.compositeRule = $it.compositeRule = true;
          $it.schema = $aProperties;
          $it.schemaPath = it.schemaPath + '.additionalProperties';
          $it.errSchemaPath = it.errSchemaPath + '/additionalProperties';
          $it.errorPath = it.opts._errorDataPathProperty ? it.errorPath : it.util.getPathExpr(it.errorPath, $key, it.opts.jsonPointers);
          var $passData = $data + '[' + $key + ']';
          $it.dataPathArr[$dataNxt] = $key;
          var $code = it.validate($it);
          $it.baseId = $currentBaseId;
          if (it.util.varOccurences($code, $nextData) < 2) {
            out += ' ' + (it.util.varReplace($code, $nextData, $passData)) + ' ';
          } else {
            out += ' var ' + ($nextData) + ' = ' + ($passData) + '; ' + ($code) + ' ';
          }
          out += ' if (!' + ($nextValid) + ') { errors = ' + ($errs) + '; if (validate.errors !== null) { if (errors) validate.errors.length = errors; else validate.errors = null; } delete ' + ($data) + '[' + ($key) + ']; }  ';
          it.compositeRule = $it.compositeRule = $wasComposite;
        } else {
          $it.schema = $aProperties;
          $it.schemaPath = it.schemaPath + '.additionalProperties';
          $it.errSchemaPath = it.errSchemaPath + '/additionalProperties';
          $it.errorPath = it.opts._errorDataPathProperty ? it.errorPath : it.util.getPathExpr(it.errorPath, $key, it.opts.jsonPointers);
          var $passData = $data + '[' + $key + ']';
          $it.dataPathArr[$dataNxt] = $key;
          var $code = it.validate($it);
          $it.baseId = $currentBaseId;
          if (it.util.varOccurences($code, $nextData) < 2) {
            out += ' ' + (it.util.varReplace($code, $nextData, $passData)) + ' ';
          } else {
            out += ' var ' + ($nextData) + ' = ' + ($passData) + '; ' + ($code) + ' ';
          }
          if ($breakOnError) {
            out += ' if (!' + ($nextValid) + ') break; ';
          }
        }
      }
      it.errorPath = $currentErrorPath;
    }
    if ($someProperties) {
      out += ' } ';
    }
    out += ' }  ';
    if ($breakOnError) {
      out += ' if (' + ($nextValid) + ') { ';
      $closingBraces += '}';
    }
  }
  var $useDefaults = it.opts.useDefaults && !it.compositeRule;
  if ($schemaKeys.length) {
    var arr4 = $schemaKeys;
    if (arr4) {
      var $propertyKey, i4 = -1,
        l4 = arr4.length - 1;
      while (i4 < l4) {
        $propertyKey = arr4[i4 += 1];
        var $sch = $schema[$propertyKey];
        if (it.util.schemaHasRules($sch, it.RULES.all)) {
          var $prop = it.util.getProperty($propertyKey),
            $passData = $data + $prop,
            $hasDefault = $useDefaults && $sch.default !== undefined;
          $it.schema = $sch;
          $it.schemaPath = $schemaPath + $prop;
          $it.errSchemaPath = $errSchemaPath + '/' + it.util.escapeFragment($propertyKey);
          $it.errorPath = it.util.getPath(it.errorPath, $propertyKey, it.opts.jsonPointers);
          $it.dataPathArr[$dataNxt] = it.util.toQuotedString($propertyKey);
          var $code = it.validate($it);
          $it.baseId = $currentBaseId;
          if (it.util.varOccurences($code, $nextData) < 2) {
            $code = it.util.varReplace($code, $nextData, $passData);
            var $useData = $passData;
          } else {
            var $useData = $nextData;
            out += ' var ' + ($nextData) + ' = ' + ($passData) + '; ';
          }
          if ($hasDefault) {
            out += ' ' + ($code) + ' ';
          } else {
            if ($requiredHash && $requiredHash[$propertyKey]) {
              out += ' if (' + ($useData) + ' === undefined) { ' + ($nextValid) + ' = false; ';
              var $currentErrorPath = it.errorPath,
                $currErrSchemaPath = $errSchemaPath,
                $missingProperty = it.util.escapeQuotes($propertyKey);
              if (it.opts._errorDataPathProperty) {
                it.errorPath = it.util.getPath($currentErrorPath, $propertyKey, it.opts.jsonPointers);
              }
              $errSchemaPath = it.errSchemaPath + '/required';
              var $$outStack = $$outStack || [];
              $$outStack.push(out);
              out = ''; /* istanbul ignore else */
              if (it.createErrors !== false) {
                out += ' { keyword: \'' + ('required') + '\' , dataPath: (dataPath || \'\') + ' + (it.errorPath) + ' , schemaPath: ' + (it.util.toQuotedString($errSchemaPath)) + ' , params: { missingProperty: \'' + ($missingProperty) + '\' } ';
                if (it.opts.messages !== false) {
                  out += ' , message: \'';
                  if (it.opts._errorDataPathProperty) {
                    out += 'is a required property';
                  } else {
                    out += 'should have required property \\\'' + ($missingProperty) + '\\\'';
                  }
                  out += '\' ';
                }
                if (it.opts.verbose) {
                  out += ' , schema: validate.schema' + ($schemaPath) + ' , parentSchema: validate.schema' + (it.schemaPath) + ' , data: ' + ($data) + ' ';
                }
                out += ' } ';
              } else {
                out += ' {} ';
              }
              var __err = out;
              out = $$outStack.pop();
              if (!it.compositeRule && $breakOnError) { /* istanbul ignore if */
                if (it.async) {
                  out += ' throw new ValidationError([' + (__err) + ']); ';
                } else {
                  out += ' validate.errors = [' + (__err) + ']; return false; ';
                }
              } else {
                out += ' var err = ' + (__err) + ';  if (vErrors === null) vErrors = [err]; else vErrors.push(err); errors++; ';
              }
              $errSchemaPath = $currErrSchemaPath;
              it.errorPath = $currentErrorPath;
              out += ' } else { ';
            } else {
              if ($breakOnError) {
                out += ' if (' + ($useData) + ' === undefined) { ' + ($nextValid) + ' = true; } else { ';
              } else {
                out += ' if (' + ($useData) + ' !== undefined) { ';
              }
            }
            out += ' ' + ($code) + ' } ';
          }
        }
        if ($breakOnError) {
          out += ' if (' + ($nextValid) + ') { ';
          $closingBraces += '}';
        }
      }
    }
  }
  var arr5 = $pPropertyKeys;
  if (arr5) {
    var $pProperty, i5 = -1,
      l5 = arr5.length - 1;
    while (i5 < l5) {
      $pProperty = arr5[i5 += 1];
      var $sch = $pProperties[$pProperty];
      if (it.util.schemaHasRules($sch, it.RULES.all)) {
        $it.schema = $sch;
        $it.schemaPath = it.schemaPath + '.patternProperties' + it.util.getProperty($pProperty);
        $it.errSchemaPath = it.errSchemaPath + '/patternProperties/' + it.util.escapeFragment($pProperty);
        out += ' for (var ' + ($key) + ' in ' + ($data) + ') {  ';
        if ($ownProperties) {
          out += ' if (!Object.prototype.hasOwnProperty.call(' + ($data) + ', ' + ($key) + ')) continue; ';
        }
        out += ' if (' + (it.usePattern($pProperty)) + '.test(' + ($key) + ')) { ';
        $it.errorPath = it.util.getPathExpr(it.errorPath, $key, it.opts.jsonPointers);
        var $passData = $data + '[' + $key + ']';
        $it.dataPathArr[$dataNxt] = $key;
        var $code = it.validate($it);
        $it.baseId = $currentBaseId;
        if (it.util.varOccurences($code, $nextData) < 2) {
          out += ' ' + (it.util.varReplace($code, $nextData, $passData)) + ' ';
        } else {
          out += ' var ' + ($nextData) + ' = ' + ($passData) + '; ' + ($code) + ' ';
        }
        if ($breakOnError) {
          out += ' if (!' + ($nextValid) + ') break; ';
        }
        out += ' } ';
        if ($breakOnError) {
          out += ' else ' + ($nextValid) + ' = true; ';
        }
        out += ' }  ';
        if ($breakOnError) {
          out += ' if (' + ($nextValid) + ') { ';
          $closingBraces += '}';
        }
      }
    }
  }
  if (it.opts.v5) {
    var arr6 = $pgPropertyKeys;
    if (arr6) {
      var $pgProperty, i6 = -1,
        l6 = arr6.length - 1;
      while (i6 < l6) {
        $pgProperty = arr6[i6 += 1];
        var $pgSchema = $pgProperties[$pgProperty],
          $sch = $pgSchema.schema;
        if (it.util.schemaHasRules($sch, it.RULES.all)) {
          $it.schema = $sch;
          $it.schemaPath = it.schemaPath + '.patternGroups' + it.util.getProperty($pgProperty) + '.schema';
          $it.errSchemaPath = it.errSchemaPath + '/patternGroups/' + it.util.escapeFragment($pgProperty) + '/schema';
          out += ' var pgPropCount' + ($lvl) + ' = 0; for (var ' + ($key) + ' in ' + ($data) + ') {  ';
          if ($ownProperties) {
            out += ' if (!Object.prototype.hasOwnProperty.call(' + ($data) + ', ' + ($key) + ')) continue; ';
          }
          out += ' if (' + (it.usePattern($pgProperty)) + '.test(' + ($key) + ')) { pgPropCount' + ($lvl) + '++; ';
          $it.errorPath = it.util.getPathExpr(it.errorPath, $key, it.opts.jsonPointers);
          var $passData = $data + '[' + $key + ']';
          $it.dataPathArr[$dataNxt] = $key;
          var $code = it.validate($it);
          $it.baseId = $currentBaseId;
          if (it.util.varOccurences($code, $nextData) < 2) {
            out += ' ' + (it.util.varReplace($code, $nextData, $passData)) + ' ';
          } else {
            out += ' var ' + ($nextData) + ' = ' + ($passData) + '; ' + ($code) + ' ';
          }
          if ($breakOnError) {
            out += ' if (!' + ($nextValid) + ') break; ';
          }
          out += ' } ';
          if ($breakOnError) {
            out += ' else ' + ($nextValid) + ' = true; ';
          }
          out += ' }  ';
          if ($breakOnError) {
            out += ' if (' + ($nextValid) + ') { ';
            $closingBraces += '}';
          }
          var $pgMin = $pgSchema.minimum,
            $pgMax = $pgSchema.maximum;
          if ($pgMin !== undefined || $pgMax !== undefined) {
            out += ' var ' + ($valid) + ' = true; ';
            var $currErrSchemaPath = $errSchemaPath;
            if ($pgMin !== undefined) {
              var $limit = $pgMin,
                $reason = 'minimum',
                $moreOrLess = 'less';
              out += ' ' + ($valid) + ' = pgPropCount' + ($lvl) + ' >= ' + ($pgMin) + '; ';
              $errSchemaPath = it.errSchemaPath + '/patternGroups/minimum';
              out += '  if (!' + ($valid) + ') {   ';
              var $$outStack = $$outStack || [];
              $$outStack.push(out);
              out = ''; /* istanbul ignore else */
              if (it.createErrors !== false) {
                out += ' { keyword: \'' + ('patternGroups') + '\' , dataPath: (dataPath || \'\') + ' + (it.errorPath) + ' , schemaPath: ' + (it.util.toQuotedString($errSchemaPath)) + ' , params: { reason: \'' + ($reason) + '\', limit: ' + ($limit) + ', pattern: \'' + (it.util.escapeQuotes($pgProperty)) + '\' } ';
                if (it.opts.messages !== false) {
                  out += ' , message: \'should NOT have ' + ($moreOrLess) + ' than ' + ($limit) + ' properties matching pattern "' + (it.util.escapeQuotes($pgProperty)) + '"\' ';
                }
                if (it.opts.verbose) {
                  out += ' , schema: validate.schema' + ($schemaPath) + ' , parentSchema: validate.schema' + (it.schemaPath) + ' , data: ' + ($data) + ' ';
                }
                out += ' } ';
              } else {
                out += ' {} ';
              }
              var __err = out;
              out = $$outStack.pop();
              if (!it.compositeRule && $breakOnError) { /* istanbul ignore if */
                if (it.async) {
                  out += ' throw new ValidationError([' + (__err) + ']); ';
                } else {
                  out += ' validate.errors = [' + (__err) + ']; return false; ';
                }
              } else {
                out += ' var err = ' + (__err) + ';  if (vErrors === null) vErrors = [err]; else vErrors.push(err); errors++; ';
              }
              out += ' } ';
              if ($pgMax !== undefined) {
                out += ' else ';
              }
            }
            if ($pgMax !== undefined) {
              var $limit = $pgMax,
                $reason = 'maximum',
                $moreOrLess = 'more';
              out += ' ' + ($valid) + ' = pgPropCount' + ($lvl) + ' <= ' + ($pgMax) + '; ';
              $errSchemaPath = it.errSchemaPath + '/patternGroups/maximum';
              out += '  if (!' + ($valid) + ') {   ';
              var $$outStack = $$outStack || [];
              $$outStack.push(out);
              out = ''; /* istanbul ignore else */
              if (it.createErrors !== false) {
                out += ' { keyword: \'' + ('patternGroups') + '\' , dataPath: (dataPath || \'\') + ' + (it.errorPath) + ' , schemaPath: ' + (it.util.toQuotedString($errSchemaPath)) + ' , params: { reason: \'' + ($reason) + '\', limit: ' + ($limit) + ', pattern: \'' + (it.util.escapeQuotes($pgProperty)) + '\' } ';
                if (it.opts.messages !== false) {
                  out += ' , message: \'should NOT have ' + ($moreOrLess) + ' than ' + ($limit) + ' properties matching pattern "' + (it.util.escapeQuotes($pgProperty)) + '"\' ';
                }
                if (it.opts.verbose) {
                  out += ' , schema: validate.schema' + ($schemaPath) + ' , parentSchema: validate.schema' + (it.schemaPath) + ' , data: ' + ($data) + ' ';
                }
                out += ' } ';
              } else {
                out += ' {} ';
              }
              var __err = out;
              out = $$outStack.pop();
              if (!it.compositeRule && $breakOnError) { /* istanbul ignore if */
                if (it.async) {
                  out += ' throw new ValidationError([' + (__err) + ']); ';
                } else {
                  out += ' validate.errors = [' + (__err) + ']; return false; ';
                }
              } else {
                out += ' var err = ' + (__err) + ';  if (vErrors === null) vErrors = [err]; else vErrors.push(err); errors++; ';
              }
              out += ' } ';
            }
            $errSchemaPath = $currErrSchemaPath;
            if ($breakOnError) {
              out += ' if (' + ($valid) + ') { ';
              $closingBraces += '}';
            }
          }
        }
      }
    }
  }
  if ($breakOnError) {
    out += ' ' + ($closingBraces) + ' if (' + ($errs) + ' == errors) {';
  }
  out = it.util.cleanUpCode(out);
  return out;
}


/***/ }),
/* 67 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";

module.exports = function generate_ref(it, $keyword) {
  var out = ' ';
  var $lvl = it.level;
  var $dataLvl = it.dataLevel;
  var $schema = it.schema[$keyword];
  var $errSchemaPath = it.errSchemaPath + '/' + $keyword;
  var $breakOnError = !it.opts.allErrors;
  var $data = 'data' + ($dataLvl || '');
  var $valid = 'valid' + $lvl;
  var $async, $refCode;
  if ($schema == '#' || $schema == '#/') {
    if (it.isRoot) {
      $async = it.async;
      $refCode = 'validate';
    } else {
      $async = it.root.schema.$async === true;
      $refCode = 'root.refVal[0]';
    }
  } else {
    var $refVal = it.resolveRef(it.baseId, $schema, it.isRoot);
    if ($refVal === undefined) {
      var $message = 'can\'t resolve reference ' + $schema + ' from id ' + it.baseId;
      if (it.opts.missingRefs == 'fail') {
        console.log($message);
        var $$outStack = $$outStack || [];
        $$outStack.push(out);
        out = ''; /* istanbul ignore else */
        if (it.createErrors !== false) {
          out += ' { keyword: \'' + ('$ref') + '\' , dataPath: (dataPath || \'\') + ' + (it.errorPath) + ' , schemaPath: ' + (it.util.toQuotedString($errSchemaPath)) + ' , params: { ref: \'' + (it.util.escapeQuotes($schema)) + '\' } ';
          if (it.opts.messages !== false) {
            out += ' , message: \'can\\\'t resolve reference ' + (it.util.escapeQuotes($schema)) + '\' ';
          }
          if (it.opts.verbose) {
            out += ' , schema: ' + (it.util.toQuotedString($schema)) + ' , parentSchema: validate.schema' + (it.schemaPath) + ' , data: ' + ($data) + ' ';
          }
          out += ' } ';
        } else {
          out += ' {} ';
        }
        var __err = out;
        out = $$outStack.pop();
        if (!it.compositeRule && $breakOnError) { /* istanbul ignore if */
          if (it.async) {
            out += ' throw new ValidationError([' + (__err) + ']); ';
          } else {
            out += ' validate.errors = [' + (__err) + ']; return false; ';
          }
        } else {
          out += ' var err = ' + (__err) + ';  if (vErrors === null) vErrors = [err]; else vErrors.push(err); errors++; ';
        }
        if ($breakOnError) {
          out += ' if (false) { ';
        }
      } else if (it.opts.missingRefs == 'ignore') {
        console.log($message);
        if ($breakOnError) {
          out += ' if (true) { ';
        }
      } else {
        var $error = new Error($message);
        $error.missingRef = it.resolve.url(it.baseId, $schema);
        $error.missingSchema = it.resolve.normalizeId(it.resolve.fullPath($error.missingRef));
        throw $error;
      }
    } else if ($refVal.inline) {
      var $it = it.util.copy(it);
      $it.level++;
      var $nextValid = 'valid' + $it.level;
      $it.schema = $refVal.schema;
      $it.schemaPath = '';
      $it.errSchemaPath = $schema;
      var $code = it.validate($it).replace(/validate\.schema/g, $refVal.code);
      out += ' ' + ($code) + ' ';
      if ($breakOnError) {
        out += ' if (' + ($nextValid) + ') { ';
      }
    } else {
      $async = $refVal.$async === true;
      $refCode = $refVal.code;
    }
  }
  if ($refCode) {
    var $$outStack = $$outStack || [];
    $$outStack.push(out);
    out = '';
    if (it.opts.passContext) {
      out += ' ' + ($refCode) + '.call(this, ';
    } else {
      out += ' ' + ($refCode) + '( ';
    }
    out += ' ' + ($data) + ', (dataPath || \'\')';
    if (it.errorPath != '""') {
      out += ' + ' + (it.errorPath);
    }
    var $parentData = $dataLvl ? 'data' + (($dataLvl - 1) || '') : 'parentData',
      $parentDataProperty = $dataLvl ? it.dataPathArr[$dataLvl] : 'parentDataProperty';
    out += ' , ' + ($parentData) + ' , ' + ($parentDataProperty) + ', rootData)  ';
    var __callValidate = out;
    out = $$outStack.pop();
    if ($async) {
      if (!it.async) throw new Error('async schema referenced by sync schema');
      out += ' try { ';
      if ($breakOnError) {
        out += 'var ' + ($valid) + ' =';
      }
      out += ' ' + (it.yieldAwait) + ' ' + (__callValidate) + '; } catch (e) { if (!(e instanceof ValidationError)) throw e; if (vErrors === null) vErrors = e.errors; else vErrors = vErrors.concat(e.errors); errors = vErrors.length; } ';
      if ($breakOnError) {
        out += ' if (' + ($valid) + ') { ';
      }
    } else {
      out += ' if (!' + (__callValidate) + ') { if (vErrors === null) vErrors = ' + ($refCode) + '.errors; else vErrors = vErrors.concat(' + ($refCode) + '.errors); errors = vErrors.length; } ';
      if ($breakOnError) {
        out += ' else { ';
      }
    }
  }
  return out;
}


/***/ }),
/* 68 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";

module.exports = function generate_required(it, $keyword) {
  var out = ' ';
  var $lvl = it.level;
  var $dataLvl = it.dataLevel;
  var $schema = it.schema[$keyword];
  var $schemaPath = it.schemaPath + it.util.getProperty($keyword);
  var $errSchemaPath = it.errSchemaPath + '/' + $keyword;
  var $breakOnError = !it.opts.allErrors;
  var $data = 'data' + ($dataLvl || '');
  var $valid = 'valid' + $lvl;
  var $isData = it.opts.v5 && $schema && $schema.$data,
    $schemaValue;
  if ($isData) {
    out += ' var schema' + ($lvl) + ' = ' + (it.util.getData($schema.$data, $dataLvl, it.dataPathArr)) + '; ';
    $schemaValue = 'schema' + $lvl;
  } else {
    $schemaValue = $schema;
  }
  var $vSchema = 'schema' + $lvl;
  if (!$isData) {
    if ($schema.length < it.opts.loopRequired && it.schema.properties && Object.keys(it.schema.properties).length) {
      var $required = [];
      var arr1 = $schema;
      if (arr1) {
        var $property, i1 = -1,
          l1 = arr1.length - 1;
        while (i1 < l1) {
          $property = arr1[i1 += 1];
          var $propertySch = it.schema.properties[$property];
          if (!($propertySch && it.util.schemaHasRules($propertySch, it.RULES.all))) {
            $required[$required.length] = $property;
          }
        }
      }
    } else {
      var $required = $schema;
    }
  }
  if ($isData || $required.length) {
    var $currentErrorPath = it.errorPath,
      $loopRequired = $isData || $required.length >= it.opts.loopRequired;
    if ($breakOnError) {
      out += ' var missing' + ($lvl) + '; ';
      if ($loopRequired) {
        if (!$isData) {
          out += ' var ' + ($vSchema) + ' = validate.schema' + ($schemaPath) + '; ';
        }
        var $i = 'i' + $lvl,
          $propertyPath = 'schema' + $lvl + '[' + $i + ']',
          $missingProperty = '\' + ' + $propertyPath + ' + \'';
        if (it.opts._errorDataPathProperty) {
          it.errorPath = it.util.getPathExpr($currentErrorPath, $propertyPath, it.opts.jsonPointers);
        }
        out += ' var ' + ($valid) + ' = true; ';
        if ($isData) {
          out += ' if (schema' + ($lvl) + ' === undefined) ' + ($valid) + ' = true; else if (!Array.isArray(schema' + ($lvl) + ')) ' + ($valid) + ' = false; else {';
        }
        out += ' for (var ' + ($i) + ' = 0; ' + ($i) + ' < ' + ($vSchema) + '.length; ' + ($i) + '++) { ' + ($valid) + ' = ' + ($data) + '[' + ($vSchema) + '[' + ($i) + ']] !== undefined; if (!' + ($valid) + ') break; } ';
        if ($isData) {
          out += '  }  ';
        }
        out += '  if (!' + ($valid) + ') {   ';
        var $$outStack = $$outStack || [];
        $$outStack.push(out);
        out = ''; /* istanbul ignore else */
        if (it.createErrors !== false) {
          out += ' { keyword: \'' + ('required') + '\' , dataPath: (dataPath || \'\') + ' + (it.errorPath) + ' , schemaPath: ' + (it.util.toQuotedString($errSchemaPath)) + ' , params: { missingProperty: \'' + ($missingProperty) + '\' } ';
          if (it.opts.messages !== false) {
            out += ' , message: \'';
            if (it.opts._errorDataPathProperty) {
              out += 'is a required property';
            } else {
              out += 'should have required property \\\'' + ($missingProperty) + '\\\'';
            }
            out += '\' ';
          }
          if (it.opts.verbose) {
            out += ' , schema: validate.schema' + ($schemaPath) + ' , parentSchema: validate.schema' + (it.schemaPath) + ' , data: ' + ($data) + ' ';
          }
          out += ' } ';
        } else {
          out += ' {} ';
        }
        var __err = out;
        out = $$outStack.pop();
        if (!it.compositeRule && $breakOnError) { /* istanbul ignore if */
          if (it.async) {
            out += ' throw new ValidationError([' + (__err) + ']); ';
          } else {
            out += ' validate.errors = [' + (__err) + ']; return false; ';
          }
        } else {
          out += ' var err = ' + (__err) + ';  if (vErrors === null) vErrors = [err]; else vErrors.push(err); errors++; ';
        }
        out += ' } else { ';
      } else {
        out += ' if ( ';
        var arr2 = $required;
        if (arr2) {
          var _$property, $i = -1,
            l2 = arr2.length - 1;
          while ($i < l2) {
            _$property = arr2[$i += 1];
            if ($i) {
              out += ' || ';
            }
            var $prop = it.util.getProperty(_$property);
            out += ' ( ' + ($data) + ($prop) + ' === undefined && (missing' + ($lvl) + ' = ' + (it.util.toQuotedString(it.opts.jsonPointers ? _$property : $prop)) + ') ) ';
          }
        }
        out += ') {  ';
        var $propertyPath = 'missing' + $lvl,
          $missingProperty = '\' + ' + $propertyPath + ' + \'';
        if (it.opts._errorDataPathProperty) {
          it.errorPath = it.opts.jsonPointers ? it.util.getPathExpr($currentErrorPath, $propertyPath, true) : $currentErrorPath + ' + ' + $propertyPath;
        }
        var $$outStack = $$outStack || [];
        $$outStack.push(out);
        out = ''; /* istanbul ignore else */
        if (it.createErrors !== false) {
          out += ' { keyword: \'' + ('required') + '\' , dataPath: (dataPath || \'\') + ' + (it.errorPath) + ' , schemaPath: ' + (it.util.toQuotedString($errSchemaPath)) + ' , params: { missingProperty: \'' + ($missingProperty) + '\' } ';
          if (it.opts.messages !== false) {
            out += ' , message: \'';
            if (it.opts._errorDataPathProperty) {
              out += 'is a required property';
            } else {
              out += 'should have required property \\\'' + ($missingProperty) + '\\\'';
            }
            out += '\' ';
          }
          if (it.opts.verbose) {
            out += ' , schema: validate.schema' + ($schemaPath) + ' , parentSchema: validate.schema' + (it.schemaPath) + ' , data: ' + ($data) + ' ';
          }
          out += ' } ';
        } else {
          out += ' {} ';
        }
        var __err = out;
        out = $$outStack.pop();
        if (!it.compositeRule && $breakOnError) { /* istanbul ignore if */
          if (it.async) {
            out += ' throw new ValidationError([' + (__err) + ']); ';
          } else {
            out += ' validate.errors = [' + (__err) + ']; return false; ';
          }
        } else {
          out += ' var err = ' + (__err) + ';  if (vErrors === null) vErrors = [err]; else vErrors.push(err); errors++; ';
        }
        out += ' } else { ';
      }
    } else {
      if ($loopRequired) {
        if (!$isData) {
          out += ' var ' + ($vSchema) + ' = validate.schema' + ($schemaPath) + '; ';
        }
        var $i = 'i' + $lvl,
          $propertyPath = 'schema' + $lvl + '[' + $i + ']',
          $missingProperty = '\' + ' + $propertyPath + ' + \'';
        if (it.opts._errorDataPathProperty) {
          it.errorPath = it.util.getPathExpr($currentErrorPath, $propertyPath, it.opts.jsonPointers);
        }
        if ($isData) {
          out += ' if (' + ($vSchema) + ' && !Array.isArray(' + ($vSchema) + ')) {  var err =   '; /* istanbul ignore else */
          if (it.createErrors !== false) {
            out += ' { keyword: \'' + ('required') + '\' , dataPath: (dataPath || \'\') + ' + (it.errorPath) + ' , schemaPath: ' + (it.util.toQuotedString($errSchemaPath)) + ' , params: { missingProperty: \'' + ($missingProperty) + '\' } ';
            if (it.opts.messages !== false) {
              out += ' , message: \'';
              if (it.opts._errorDataPathProperty) {
                out += 'is a required property';
              } else {
                out += 'should have required property \\\'' + ($missingProperty) + '\\\'';
              }
              out += '\' ';
            }
            if (it.opts.verbose) {
              out += ' , schema: validate.schema' + ($schemaPath) + ' , parentSchema: validate.schema' + (it.schemaPath) + ' , data: ' + ($data) + ' ';
            }
            out += ' } ';
          } else {
            out += ' {} ';
          }
          out += ';  if (vErrors === null) vErrors = [err]; else vErrors.push(err); errors++; } else if (' + ($vSchema) + ' !== undefined) { ';
        }
        out += ' for (var ' + ($i) + ' = 0; ' + ($i) + ' < ' + ($vSchema) + '.length; ' + ($i) + '++) { if (' + ($data) + '[' + ($vSchema) + '[' + ($i) + ']] === undefined) {  var err =   '; /* istanbul ignore else */
        if (it.createErrors !== false) {
          out += ' { keyword: \'' + ('required') + '\' , dataPath: (dataPath || \'\') + ' + (it.errorPath) + ' , schemaPath: ' + (it.util.toQuotedString($errSchemaPath)) + ' , params: { missingProperty: \'' + ($missingProperty) + '\' } ';
          if (it.opts.messages !== false) {
            out += ' , message: \'';
            if (it.opts._errorDataPathProperty) {
              out += 'is a required property';
            } else {
              out += 'should have required property \\\'' + ($missingProperty) + '\\\'';
            }
            out += '\' ';
          }
          if (it.opts.verbose) {
            out += ' , schema: validate.schema' + ($schemaPath) + ' , parentSchema: validate.schema' + (it.schemaPath) + ' , data: ' + ($data) + ' ';
          }
          out += ' } ';
        } else {
          out += ' {} ';
        }
        out += ';  if (vErrors === null) vErrors = [err]; else vErrors.push(err); errors++; } } ';
        if ($isData) {
          out += '  }  ';
        }
      } else {
        var arr3 = $required;
        if (arr3) {
          var $reqProperty, i3 = -1,
            l3 = arr3.length - 1;
          while (i3 < l3) {
            $reqProperty = arr3[i3 += 1];
            var $prop = it.util.getProperty($reqProperty),
              $missingProperty = it.util.escapeQuotes($reqProperty);
            if (it.opts._errorDataPathProperty) {
              it.errorPath = it.util.getPath($currentErrorPath, $reqProperty, it.opts.jsonPointers);
            }
            out += ' if (' + ($data) + ($prop) + ' === undefined) {  var err =   '; /* istanbul ignore else */
            if (it.createErrors !== false) {
              out += ' { keyword: \'' + ('required') + '\' , dataPath: (dataPath || \'\') + ' + (it.errorPath) + ' , schemaPath: ' + (it.util.toQuotedString($errSchemaPath)) + ' , params: { missingProperty: \'' + ($missingProperty) + '\' } ';
              if (it.opts.messages !== false) {
                out += ' , message: \'';
                if (it.opts._errorDataPathProperty) {
                  out += 'is a required property';
                } else {
                  out += 'should have required property \\\'' + ($missingProperty) + '\\\'';
                }
                out += '\' ';
              }
              if (it.opts.verbose) {
                out += ' , schema: validate.schema' + ($schemaPath) + ' , parentSchema: validate.schema' + (it.schemaPath) + ' , data: ' + ($data) + ' ';
              }
              out += ' } ';
            } else {
              out += ' {} ';
            }
            out += ';  if (vErrors === null) vErrors = [err]; else vErrors.push(err); errors++; } ';
          }
        }
      }
    }
    it.errorPath = $currentErrorPath;
  } else if ($breakOnError) {
    out += ' if (true) {';
  }
  return out;
}


/***/ }),
/* 69 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";

module.exports = function generate_switch(it, $keyword) {
  var out = ' ';
  var $lvl = it.level;
  var $dataLvl = it.dataLevel;
  var $schema = it.schema[$keyword];
  var $schemaPath = it.schemaPath + it.util.getProperty($keyword);
  var $errSchemaPath = it.errSchemaPath + '/' + $keyword;
  var $breakOnError = !it.opts.allErrors;
  var $data = 'data' + ($dataLvl || '');
  var $valid = 'valid' + $lvl;
  var $errs = 'errs__' + $lvl;
  var $it = it.util.copy(it);
  var $closingBraces = '';
  $it.level++;
  var $nextValid = 'valid' + $it.level;
  var $ifPassed = 'ifPassed' + it.level,
    $currentBaseId = $it.baseId,
    $shouldContinue;
  out += 'var ' + ($ifPassed) + ';';
  var arr1 = $schema;
  if (arr1) {
    var $sch, $caseIndex = -1,
      l1 = arr1.length - 1;
    while ($caseIndex < l1) {
      $sch = arr1[$caseIndex += 1];
      if ($caseIndex && !$shouldContinue) {
        out += ' if (!' + ($ifPassed) + ') { ';
        $closingBraces += '}';
      }
      if ($sch.if && it.util.schemaHasRules($sch.if, it.RULES.all)) {
        out += ' var ' + ($errs) + ' = errors;   ';
        var $wasComposite = it.compositeRule;
        it.compositeRule = $it.compositeRule = true;
        $it.createErrors = false;
        $it.schema = $sch.if;
        $it.schemaPath = $schemaPath + '[' + $caseIndex + '].if';
        $it.errSchemaPath = $errSchemaPath + '/' + $caseIndex + '/if';
        out += '  ' + (it.validate($it)) + ' ';
        $it.baseId = $currentBaseId;
        $it.createErrors = true;
        it.compositeRule = $it.compositeRule = $wasComposite;
        out += ' ' + ($ifPassed) + ' = ' + ($nextValid) + '; if (' + ($ifPassed) + ') {  ';
        if (typeof $sch.then == 'boolean') {
          if ($sch.then === false) {
            var $$outStack = $$outStack || [];
            $$outStack.push(out);
            out = ''; /* istanbul ignore else */
            if (it.createErrors !== false) {
              out += ' { keyword: \'' + ('switch') + '\' , dataPath: (dataPath || \'\') + ' + (it.errorPath) + ' , schemaPath: ' + (it.util.toQuotedString($errSchemaPath)) + ' , params: { caseIndex: ' + ($caseIndex) + ' } ';
              if (it.opts.messages !== false) {
                out += ' , message: \'should pass "switch" keyword validation\' ';
              }
              if (it.opts.verbose) {
                out += ' , schema: validate.schema' + ($schemaPath) + ' , parentSchema: validate.schema' + (it.schemaPath) + ' , data: ' + ($data) + ' ';
              }
              out += ' } ';
            } else {
              out += ' {} ';
            }
            var __err = out;
            out = $$outStack.pop();
            if (!it.compositeRule && $breakOnError) { /* istanbul ignore if */
              if (it.async) {
                out += ' throw new ValidationError([' + (__err) + ']); ';
              } else {
                out += ' validate.errors = [' + (__err) + ']; return false; ';
              }
            } else {
              out += ' var err = ' + (__err) + ';  if (vErrors === null) vErrors = [err]; else vErrors.push(err); errors++; ';
            }
          }
          out += ' var ' + ($nextValid) + ' = ' + ($sch.then) + '; ';
        } else {
          $it.schema = $sch.then;
          $it.schemaPath = $schemaPath + '[' + $caseIndex + '].then';
          $it.errSchemaPath = $errSchemaPath + '/' + $caseIndex + '/then';
          out += '  ' + (it.validate($it)) + ' ';
          $it.baseId = $currentBaseId;
        }
        out += '  } else {  errors = ' + ($errs) + '; if (vErrors !== null) { if (' + ($errs) + ') vErrors.length = ' + ($errs) + '; else vErrors = null; } } ';
      } else {
        out += ' ' + ($ifPassed) + ' = true;  ';
        if (typeof $sch.then == 'boolean') {
          if ($sch.then === false) {
            var $$outStack = $$outStack || [];
            $$outStack.push(out);
            out = ''; /* istanbul ignore else */
            if (it.createErrors !== false) {
              out += ' { keyword: \'' + ('switch') + '\' , dataPath: (dataPath || \'\') + ' + (it.errorPath) + ' , schemaPath: ' + (it.util.toQuotedString($errSchemaPath)) + ' , params: { caseIndex: ' + ($caseIndex) + ' } ';
              if (it.opts.messages !== false) {
                out += ' , message: \'should pass "switch" keyword validation\' ';
              }
              if (it.opts.verbose) {
                out += ' , schema: validate.schema' + ($schemaPath) + ' , parentSchema: validate.schema' + (it.schemaPath) + ' , data: ' + ($data) + ' ';
              }
              out += ' } ';
            } else {
              out += ' {} ';
            }
            var __err = out;
            out = $$outStack.pop();
            if (!it.compositeRule && $breakOnError) { /* istanbul ignore if */
              if (it.async) {
                out += ' throw new ValidationError([' + (__err) + ']); ';
              } else {
                out += ' validate.errors = [' + (__err) + ']; return false; ';
              }
            } else {
              out += ' var err = ' + (__err) + ';  if (vErrors === null) vErrors = [err]; else vErrors.push(err); errors++; ';
            }
          }
          out += ' var ' + ($nextValid) + ' = ' + ($sch.then) + '; ';
        } else {
          $it.schema = $sch.then;
          $it.schemaPath = $schemaPath + '[' + $caseIndex + '].then';
          $it.errSchemaPath = $errSchemaPath + '/' + $caseIndex + '/then';
          out += '  ' + (it.validate($it)) + ' ';
          $it.baseId = $currentBaseId;
        }
      }
      $shouldContinue = $sch.continue
    }
  }
  out += '' + ($closingBraces) + 'var ' + ($valid) + ' = ' + ($nextValid) + '; ';
  out = it.util.cleanUpCode(out);
  return out;
}


/***/ }),
/* 70 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";

module.exports = function generate_uniqueItems(it, $keyword) {
  var out = ' ';
  var $lvl = it.level;
  var $dataLvl = it.dataLevel;
  var $schema = it.schema[$keyword];
  var $schemaPath = it.schemaPath + it.util.getProperty($keyword);
  var $errSchemaPath = it.errSchemaPath + '/' + $keyword;
  var $breakOnError = !it.opts.allErrors;
  var $data = 'data' + ($dataLvl || '');
  var $valid = 'valid' + $lvl;
  var $isData = it.opts.v5 && $schema && $schema.$data,
    $schemaValue;
  if ($isData) {
    out += ' var schema' + ($lvl) + ' = ' + (it.util.getData($schema.$data, $dataLvl, it.dataPathArr)) + '; ';
    $schemaValue = 'schema' + $lvl;
  } else {
    $schemaValue = $schema;
  }
  if (($schema || $isData) && it.opts.uniqueItems !== false) {
    if ($isData) {
      out += ' var ' + ($valid) + '; if (' + ($schemaValue) + ' === false || ' + ($schemaValue) + ' === undefined) ' + ($valid) + ' = true; else if (typeof ' + ($schemaValue) + ' != \'boolean\') ' + ($valid) + ' = false; else { ';
    }
    out += ' var ' + ($valid) + ' = true; if (' + ($data) + '.length > 1) { var i = ' + ($data) + '.length, j; outer: for (;i--;) { for (j = i; j--;) { if (equal(' + ($data) + '[i], ' + ($data) + '[j])) { ' + ($valid) + ' = false; break outer; } } } } ';
    if ($isData) {
      out += '  }  ';
    }
    out += ' if (!' + ($valid) + ') {   ';
    var $$outStack = $$outStack || [];
    $$outStack.push(out);
    out = ''; /* istanbul ignore else */
    if (it.createErrors !== false) {
      out += ' { keyword: \'' + ('uniqueItems') + '\' , dataPath: (dataPath || \'\') + ' + (it.errorPath) + ' , schemaPath: ' + (it.util.toQuotedString($errSchemaPath)) + ' , params: { i: i, j: j } ';
      if (it.opts.messages !== false) {
        out += ' , message: \'should NOT have duplicate items (items ## \' + j + \' and \' + i + \' are identical)\' ';
      }
      if (it.opts.verbose) {
        out += ' , schema:  ';
        if ($isData) {
          out += 'validate.schema' + ($schemaPath);
        } else {
          out += '' + ($schema);
        }
        out += '         , parentSchema: validate.schema' + (it.schemaPath) + ' , data: ' + ($data) + ' ';
      }
      out += ' } ';
    } else {
      out += ' {} ';
    }
    var __err = out;
    out = $$outStack.pop();
    if (!it.compositeRule && $breakOnError) { /* istanbul ignore if */
      if (it.async) {
        out += ' throw new ValidationError([' + (__err) + ']); ';
      } else {
        out += ' validate.errors = [' + (__err) + ']; return false; ';
      }
    } else {
      out += ' var err = ' + (__err) + ';  if (vErrors === null) vErrors = [err]; else vErrors.push(err); errors++; ';
    }
    out += ' } ';
    if ($breakOnError) {
      out += ' else { ';
    }
  } else {
    if ($breakOnError) {
      out += ' if (true) { ';
    }
  }
  return out;
}


/***/ }),
/* 71 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


var IDENTIFIER = /^[a-z_$][a-z0-9_$\-]*$/i;
var customRuleCode = __webpack_require__(56);

module.exports = {
  add: addKeyword,
  get: getKeyword,
  remove: removeKeyword
};

/**
 * Define custom keyword
 * @this  Ajv
 * @param {String} keyword custom keyword, should be unique (including different from all standard, custom and macro keywords).
 * @param {Object} definition keyword definition object with properties `type` (type(s) which the keyword applies to), `validate` or `compile`.
 */
function addKeyword(keyword, definition) {
  /* jshint validthis: true */
  /* eslint no-shadow: 0 */
  var RULES = this.RULES;

  if (RULES.keywords[keyword])
    throw new Error('Keyword ' + keyword + ' is already defined');

  if (!IDENTIFIER.test(keyword))
    throw new Error('Keyword ' + keyword + ' is not a valid identifier');

  if (definition) {
    if (definition.macro && definition.valid !== undefined)
      throw new Error('"valid" option cannot be used with macro keywords');

    var dataType = definition.type;
    if (Array.isArray(dataType)) {
      var i, len = dataType.length;
      for (i=0; i<len; i++) checkDataType(dataType[i]);
      for (i=0; i<len; i++) _addRule(keyword, dataType[i], definition);
    } else {
      if (dataType) checkDataType(dataType);
      _addRule(keyword, dataType, definition);
    }

    var $data = definition.$data === true && this._opts.v5;
    if ($data && !definition.validate)
      throw new Error('$data support: "validate" function is not defined');

    var metaSchema = definition.metaSchema;
    if (metaSchema) {
      if ($data) {
        metaSchema = {
          anyOf: [
            metaSchema,
            { '$ref': 'https://raw.githubusercontent.com/epoberezkin/ajv/master/lib/refs/json-schema-v5.json#/definitions/$data' }
          ]
        };
      }
      definition.validateSchema = this.compile(metaSchema, true);
    }
  }

  RULES.keywords[keyword] = RULES.all[keyword] = true;


  function _addRule(keyword, dataType, definition) {
    var ruleGroup;
    for (var i=0; i<RULES.length; i++) {
      var rg = RULES[i];
      if (rg.type == dataType) {
        ruleGroup = rg;
        break;
      }
    }

    if (!ruleGroup) {
      ruleGroup = { type: dataType, rules: [] };
      RULES.push(ruleGroup);
    }

    var rule = {
      keyword: keyword,
      definition: definition,
      custom: true,
      code: customRuleCode
    };
    ruleGroup.rules.push(rule);
    RULES.custom[keyword] = rule;
  }


  function checkDataType(dataType) {
    if (!RULES.types[dataType]) throw new Error('Unknown type ' + dataType);
  }
}


/**
 * Get keyword
 * @this  Ajv
 * @param {String} keyword pre-defined or custom keyword.
 * @return {Object|Boolean} custom keyword definition, `true` if it is a predefined keyword, `false` otherwise.
 */
function getKeyword(keyword) {
  /* jshint validthis: true */
  var rule = this.RULES.custom[keyword];
  return rule ? rule.definition : this.RULES.keywords[keyword] || false;
}


/**
 * Remove keyword
 * @this  Ajv
 * @param {String} keyword pre-defined or custom keyword.
 */
function removeKeyword(keyword) {
  /* jshint validthis: true */
  var RULES = this.RULES;
  delete RULES.keywords[keyword];
  delete RULES.all[keyword];
  delete RULES.custom[keyword];
  for (var i=0; i<RULES.length; i++) {
    var rules = RULES[i].rules;
    for (var j=0; j<rules.length; j++) {
      if (rules[j].keyword == keyword) {
        rules.splice(j, 1);
        break;
      }
    }
  }
}


/***/ }),
/* 72 */
/***/ (function(module, exports) {

module.exports = {
	"id": "http://json-schema.org/draft-04/schema#",
	"$schema": "http://json-schema.org/draft-04/schema#",
	"description": "Core schema meta-schema",
	"definitions": {
		"schemaArray": {
			"type": "array",
			"minItems": 1,
			"items": {
				"$ref": "#"
			}
		},
		"positiveInteger": {
			"type": "integer",
			"minimum": 0
		},
		"positiveIntegerDefault0": {
			"allOf": [
				{
					"$ref": "#/definitions/positiveInteger"
				},
				{
					"default": 0
				}
			]
		},
		"simpleTypes": {
			"enum": [
				"array",
				"boolean",
				"integer",
				"null",
				"number",
				"object",
				"string"
			]
		},
		"stringArray": {
			"type": "array",
			"items": {
				"type": "string"
			},
			"minItems": 1,
			"uniqueItems": true
		}
	},
	"type": "object",
	"properties": {
		"id": {
			"type": "string",
			"format": "uri"
		},
		"$schema": {
			"type": "string",
			"format": "uri"
		},
		"title": {
			"type": "string"
		},
		"description": {
			"type": "string"
		},
		"default": {},
		"multipleOf": {
			"type": "number",
			"minimum": 0,
			"exclusiveMinimum": true
		},
		"maximum": {
			"type": "number"
		},
		"exclusiveMaximum": {
			"type": "boolean",
			"default": false
		},
		"minimum": {
			"type": "number"
		},
		"exclusiveMinimum": {
			"type": "boolean",
			"default": false
		},
		"maxLength": {
			"$ref": "#/definitions/positiveInteger"
		},
		"minLength": {
			"$ref": "#/definitions/positiveIntegerDefault0"
		},
		"pattern": {
			"type": "string",
			"format": "regex"
		},
		"additionalItems": {
			"anyOf": [
				{
					"type": "boolean"
				},
				{
					"$ref": "#"
				}
			],
			"default": {}
		},
		"items": {
			"anyOf": [
				{
					"$ref": "#"
				},
				{
					"$ref": "#/definitions/schemaArray"
				}
			],
			"default": {}
		},
		"maxItems": {
			"$ref": "#/definitions/positiveInteger"
		},
		"minItems": {
			"$ref": "#/definitions/positiveIntegerDefault0"
		},
		"uniqueItems": {
			"type": "boolean",
			"default": false
		},
		"maxProperties": {
			"$ref": "#/definitions/positiveInteger"
		},
		"minProperties": {
			"$ref": "#/definitions/positiveIntegerDefault0"
		},
		"required": {
			"$ref": "#/definitions/stringArray"
		},
		"additionalProperties": {
			"anyOf": [
				{
					"type": "boolean"
				},
				{
					"$ref": "#"
				}
			],
			"default": {}
		},
		"definitions": {
			"type": "object",
			"additionalProperties": {
				"$ref": "#"
			},
			"default": {}
		},
		"properties": {
			"type": "object",
			"additionalProperties": {
				"$ref": "#"
			},
			"default": {}
		},
		"patternProperties": {
			"type": "object",
			"additionalProperties": {
				"$ref": "#"
			},
			"default": {}
		},
		"dependencies": {
			"type": "object",
			"additionalProperties": {
				"anyOf": [
					{
						"$ref": "#"
					},
					{
						"$ref": "#/definitions/stringArray"
					}
				]
			}
		},
		"enum": {
			"type": "array",
			"minItems": 1,
			"uniqueItems": true
		},
		"type": {
			"anyOf": [
				{
					"$ref": "#/definitions/simpleTypes"
				},
				{
					"type": "array",
					"items": {
						"$ref": "#/definitions/simpleTypes"
					},
					"minItems": 1,
					"uniqueItems": true
				}
			]
		},
		"allOf": {
			"$ref": "#/definitions/schemaArray"
		},
		"anyOf": {
			"$ref": "#/definitions/schemaArray"
		},
		"oneOf": {
			"$ref": "#/definitions/schemaArray"
		},
		"not": {
			"$ref": "#"
		}
	},
	"dependencies": {
		"exclusiveMaximum": [
			"maximum"
		],
		"exclusiveMinimum": [
			"minimum"
		]
	},
	"default": {}
};

/***/ }),
/* 73 */
/***/ (function(module, exports) {

module.exports = {
	"id": "https://raw.githubusercontent.com/epoberezkin/ajv/master/lib/refs/json-schema-v5.json#",
	"$schema": "http://json-schema.org/draft-04/schema#",
	"description": "Core schema meta-schema (v5 proposals)",
	"definitions": {
		"schemaArray": {
			"type": "array",
			"minItems": 1,
			"items": {
				"$ref": "#"
			}
		},
		"positiveInteger": {
			"type": "integer",
			"minimum": 0
		},
		"positiveIntegerDefault0": {
			"allOf": [
				{
					"$ref": "#/definitions/positiveInteger"
				},
				{
					"default": 0
				}
			]
		},
		"simpleTypes": {
			"enum": [
				"array",
				"boolean",
				"integer",
				"null",
				"number",
				"object",
				"string"
			]
		},
		"stringArray": {
			"type": "array",
			"items": {
				"type": "string"
			},
			"minItems": 1,
			"uniqueItems": true
		},
		"$data": {
			"type": "object",
			"required": [
				"$data"
			],
			"properties": {
				"$data": {
					"type": "string",
					"anyOf": [
						{
							"format": "relative-json-pointer"
						},
						{
							"format": "json-pointer"
						}
					]
				}
			},
			"additionalProperties": false
		}
	},
	"type": "object",
	"properties": {
		"id": {
			"type": "string",
			"format": "uri"
		},
		"$schema": {
			"type": "string",
			"format": "uri"
		},
		"title": {
			"type": "string"
		},
		"description": {
			"type": "string"
		},
		"default": {},
		"multipleOf": {
			"anyOf": [
				{
					"type": "number",
					"minimum": 0,
					"exclusiveMinimum": true
				},
				{
					"$ref": "#/definitions/$data"
				}
			]
		},
		"maximum": {
			"anyOf": [
				{
					"type": "number"
				},
				{
					"$ref": "#/definitions/$data"
				}
			]
		},
		"exclusiveMaximum": {
			"anyOf": [
				{
					"type": "boolean",
					"default": false
				},
				{
					"$ref": "#/definitions/$data"
				}
			]
		},
		"minimum": {
			"anyOf": [
				{
					"type": "number"
				},
				{
					"$ref": "#/definitions/$data"
				}
			]
		},
		"exclusiveMinimum": {
			"anyOf": [
				{
					"type": "boolean",
					"default": false
				},
				{
					"$ref": "#/definitions/$data"
				}
			]
		},
		"maxLength": {
			"anyOf": [
				{
					"$ref": "#/definitions/positiveInteger"
				},
				{
					"$ref": "#/definitions/$data"
				}
			]
		},
		"minLength": {
			"anyOf": [
				{
					"$ref": "#/definitions/positiveIntegerDefault0"
				},
				{
					"$ref": "#/definitions/$data"
				}
			]
		},
		"pattern": {
			"anyOf": [
				{
					"type": "string",
					"format": "regex"
				},
				{
					"$ref": "#/definitions/$data"
				}
			]
		},
		"additionalItems": {
			"anyOf": [
				{
					"type": "boolean"
				},
				{
					"$ref": "#"
				},
				{
					"$ref": "#/definitions/$data"
				}
			],
			"default": {}
		},
		"items": {
			"anyOf": [
				{
					"$ref": "#"
				},
				{
					"$ref": "#/definitions/schemaArray"
				}
			],
			"default": {}
		},
		"maxItems": {
			"anyOf": [
				{
					"$ref": "#/definitions/positiveInteger"
				},
				{
					"$ref": "#/definitions/$data"
				}
			]
		},
		"minItems": {
			"anyOf": [
				{
					"$ref": "#/definitions/positiveIntegerDefault0"
				},
				{
					"$ref": "#/definitions/$data"
				}
			]
		},
		"uniqueItems": {
			"anyOf": [
				{
					"type": "boolean",
					"default": false
				},
				{
					"$ref": "#/definitions/$data"
				}
			]
		},
		"maxProperties": {
			"anyOf": [
				{
					"$ref": "#/definitions/positiveInteger"
				},
				{
					"$ref": "#/definitions/$data"
				}
			]
		},
		"minProperties": {
			"anyOf": [
				{
					"$ref": "#/definitions/positiveIntegerDefault0"
				},
				{
					"$ref": "#/definitions/$data"
				}
			]
		},
		"required": {
			"anyOf": [
				{
					"$ref": "#/definitions/stringArray"
				},
				{
					"$ref": "#/definitions/$data"
				}
			]
		},
		"additionalProperties": {
			"anyOf": [
				{
					"type": "boolean"
				},
				{
					"$ref": "#"
				},
				{
					"$ref": "#/definitions/$data"
				}
			],
			"default": {}
		},
		"definitions": {
			"type": "object",
			"additionalProperties": {
				"$ref": "#"
			},
			"default": {}
		},
		"properties": {
			"type": "object",
			"additionalProperties": {
				"$ref": "#"
			},
			"default": {}
		},
		"patternProperties": {
			"type": "object",
			"additionalProperties": {
				"$ref": "#"
			},
			"default": {}
		},
		"dependencies": {
			"type": "object",
			"additionalProperties": {
				"anyOf": [
					{
						"$ref": "#"
					},
					{
						"$ref": "#/definitions/stringArray"
					}
				]
			}
		},
		"enum": {
			"anyOf": [
				{
					"type": "array",
					"minItems": 1,
					"uniqueItems": true
				},
				{
					"$ref": "#/definitions/$data"
				}
			]
		},
		"type": {
			"anyOf": [
				{
					"$ref": "#/definitions/simpleTypes"
				},
				{
					"type": "array",
					"items": {
						"$ref": "#/definitions/simpleTypes"
					},
					"minItems": 1,
					"uniqueItems": true
				}
			]
		},
		"allOf": {
			"$ref": "#/definitions/schemaArray"
		},
		"anyOf": {
			"$ref": "#/definitions/schemaArray"
		},
		"oneOf": {
			"$ref": "#/definitions/schemaArray"
		},
		"not": {
			"$ref": "#"
		},
		"format": {
			"anyOf": [
				{
					"type": "string"
				},
				{
					"$ref": "#/definitions/$data"
				}
			]
		},
		"formatMaximum": {
			"anyOf": [
				{
					"type": "string"
				},
				{
					"$ref": "#/definitions/$data"
				}
			]
		},
		"formatMinimum": {
			"anyOf": [
				{
					"type": "string"
				},
				{
					"$ref": "#/definitions/$data"
				}
			]
		},
		"formatExclusiveMaximum": {
			"anyOf": [
				{
					"type": "boolean",
					"default": false
				},
				{
					"$ref": "#/definitions/$data"
				}
			]
		},
		"formatExclusiveMinimum": {
			"anyOf": [
				{
					"type": "boolean",
					"default": false
				},
				{
					"$ref": "#/definitions/$data"
				}
			]
		},
		"constant": {
			"anyOf": [
				{},
				{
					"$ref": "#/definitions/$data"
				}
			]
		},
		"contains": {
			"$ref": "#"
		},
		"patternGroups": {
			"type": "object",
			"additionalProperties": {
				"type": "object",
				"required": [
					"schema"
				],
				"properties": {
					"maximum": {
						"anyOf": [
							{
								"$ref": "#/definitions/positiveInteger"
							},
							{
								"$ref": "#/definitions/$data"
							}
						]
					},
					"minimum": {
						"anyOf": [
							{
								"$ref": "#/definitions/positiveIntegerDefault0"
							},
							{
								"$ref": "#/definitions/$data"
							}
						]
					},
					"schema": {
						"$ref": "#"
					}
				},
				"additionalProperties": false
			},
			"default": {}
		},
		"switch": {
			"type": "array",
			"items": {
				"required": [
					"then"
				],
				"properties": {
					"if": {
						"$ref": "#"
					},
					"then": {
						"anyOf": [
							{
								"type": "boolean"
							},
							{
								"$ref": "#"
							}
						]
					},
					"continue": {
						"type": "boolean"
					}
				},
				"additionalProperties": false,
				"dependencies": {
					"continue": [
						"if"
					]
				}
			}
		}
	},
	"dependencies": {
		"exclusiveMaximum": [
			"maximum"
		],
		"exclusiveMinimum": [
			"minimum"
		],
		"formatMaximum": [
			"format"
		],
		"formatMinimum": [
			"format"
		],
		"formatExclusiveMaximum": [
			"formatMaximum"
		],
		"formatExclusiveMinimum": [
			"formatMinimum"
		]
	},
	"default": {}
};

/***/ }),
/* 74 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


var META_SCHEMA_ID = 'https://raw.githubusercontent.com/epoberezkin/ajv/master/lib/refs/json-schema-v5.json';

module.exports = {
  enable: enableV5,
  META_SCHEMA_ID: META_SCHEMA_ID
};


function enableV5(ajv) {
  var inlineFunctions = {
    'switch': __webpack_require__(69),
    'constant': __webpack_require__(55),
    '_formatLimit': __webpack_require__(52),
    'patternRequired': __webpack_require__(65)
  };

  if (ajv._opts.meta !== false) {
    var metaSchema = __webpack_require__(73);
    ajv.addMetaSchema(metaSchema, META_SCHEMA_ID);
  }
  _addKeyword('constant');
  ajv.addKeyword('contains', { type: 'array', macro: containsMacro });

  _addKeyword('formatMaximum', 'string', inlineFunctions._formatLimit);
  _addKeyword('formatMinimum', 'string', inlineFunctions._formatLimit);
  ajv.addKeyword('formatExclusiveMaximum');
  ajv.addKeyword('formatExclusiveMinimum');

  ajv.addKeyword('patternGroups'); // implemented in properties.jst
  _addKeyword('patternRequired', 'object');
  _addKeyword('switch');


  function _addKeyword(keyword, types, inlineFunc) {
    var definition = {
      inline: inlineFunc || inlineFunctions[keyword],
      statements: true,
      errors: 'full'
    };
    if (types) definition.type = types;
    ajv.addKeyword(keyword, definition);
  }
}


function containsMacro(schema) {
  return {
    not: { items: { not: schema } }
  };
}


/***/ }),
/* 75 */,
/* 76 */,
/* 77 */,
/* 78 */,
/* 79 */,
/* 80 */,
/* 81 */,
/* 82 */,
/* 83 */,
/* 84 */,
/* 85 */
/***/ (function(module, exports) {

ace.define("ace/ext/searchbox",["require","exports","module","ace/lib/dom","ace/lib/lang","ace/lib/event","ace/keyboard/hash_handler","ace/lib/keys"], function(acequire, exports, module) {
"use strict";

var dom = acequire("../lib/dom");
var lang = acequire("../lib/lang");
var event = acequire("../lib/event");
var searchboxCss = "\
.ace_search {\
background-color: #ddd;\
border: 1px solid #cbcbcb;\
border-top: 0 none;\
max-width: 325px;\
overflow: hidden;\
margin: 0;\
padding: 4px;\
padding-right: 6px;\
padding-bottom: 0;\
position: absolute;\
top: 0px;\
z-index: 99;\
white-space: normal;\
}\
.ace_search.left {\
border-left: 0 none;\
border-radius: 0px 0px 5px 0px;\
left: 0;\
}\
.ace_search.right {\
border-radius: 0px 0px 0px 5px;\
border-right: 0 none;\
right: 0;\
}\
.ace_search_form, .ace_replace_form {\
border-radius: 3px;\
border: 1px solid #cbcbcb;\
float: left;\
margin-bottom: 4px;\
overflow: hidden;\
}\
.ace_search_form.ace_nomatch {\
outline: 1px solid red;\
}\
.ace_search_field {\
background-color: white;\
color: black;\
border-right: 1px solid #cbcbcb;\
border: 0 none;\
-webkit-box-sizing: border-box;\
-moz-box-sizing: border-box;\
box-sizing: border-box;\
float: left;\
height: 22px;\
outline: 0;\
padding: 0 7px;\
width: 214px;\
margin: 0;\
}\
.ace_searchbtn,\
.ace_replacebtn {\
background: #fff;\
border: 0 none;\
border-left: 1px solid #dcdcdc;\
cursor: pointer;\
float: left;\
height: 22px;\
margin: 0;\
position: relative;\
}\
.ace_searchbtn:last-child,\
.ace_replacebtn:last-child {\
border-top-right-radius: 3px;\
border-bottom-right-radius: 3px;\
}\
.ace_searchbtn:disabled {\
background: none;\
cursor: default;\
}\
.ace_searchbtn {\
background-position: 50% 50%;\
background-repeat: no-repeat;\
width: 27px;\
}\
.ace_searchbtn.prev {\
background-image: url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAgAAAAFCAYAAAB4ka1VAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAADFJREFUeNpiSU1NZUAC/6E0I0yACYskCpsJiySKIiY0SUZk40FyTEgCjGgKwTRAgAEAQJUIPCE+qfkAAAAASUVORK5CYII=);    \
}\
.ace_searchbtn.next {\
background-image: url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAgAAAAFCAYAAAB4ka1VAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAADRJREFUeNpiTE1NZQCC/0DMyIAKwGJMUAYDEo3M/s+EpvM/mkKwCQxYjIeLMaELoLMBAgwAU7UJObTKsvAAAAAASUVORK5CYII=);    \
}\
.ace_searchbtn_close {\
background: url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAA4AAAAcCAYAAABRVo5BAAAAZ0lEQVR42u2SUQrAMAhDvazn8OjZBilCkYVVxiis8H4CT0VrAJb4WHT3C5xU2a2IQZXJjiQIRMdkEoJ5Q2yMqpfDIo+XY4k6h+YXOyKqTIj5REaxloNAd0xiKmAtsTHqW8sR2W5f7gCu5nWFUpVjZwAAAABJRU5ErkJggg==) no-repeat 50% 0;\
border-radius: 50%;\
border: 0 none;\
color: #656565;\
cursor: pointer;\
float: right;\
font: 16px/16px Arial;\
height: 14px;\
margin: 5px 1px 9px 5px;\
padding: 0;\
text-align: center;\
width: 14px;\
}\
.ace_searchbtn_close:hover {\
background-color: #656565;\
background-position: 50% 100%;\
color: white;\
}\
.ace_replacebtn.prev {\
width: 54px\
}\
.ace_replacebtn.next {\
width: 27px\
}\
.ace_button {\
margin-left: 2px;\
cursor: pointer;\
-webkit-user-select: none;\
-moz-user-select: none;\
-o-user-select: none;\
-ms-user-select: none;\
user-select: none;\
overflow: hidden;\
opacity: 0.7;\
border: 1px solid rgba(100,100,100,0.23);\
padding: 1px;\
-moz-box-sizing: border-box;\
box-sizing:    border-box;\
color: black;\
}\
.ace_button:hover {\
background-color: #eee;\
opacity:1;\
}\
.ace_button:active {\
background-color: #ddd;\
}\
.ace_button.checked {\
border-color: #3399ff;\
opacity:1;\
}\
.ace_search_options{\
margin-bottom: 3px;\
text-align: right;\
-webkit-user-select: none;\
-moz-user-select: none;\
-o-user-select: none;\
-ms-user-select: none;\
user-select: none;\
}";
var HashHandler = acequire("../keyboard/hash_handler").HashHandler;
var keyUtil = acequire("../lib/keys");

dom.importCssString(searchboxCss, "ace_searchbox");

var html = '<div class="ace_search right">\
    <button type="button" action="hide" class="ace_searchbtn_close"></button>\
    <div class="ace_search_form">\
        <input class="ace_search_field" placeholder="Search for" spellcheck="false"></input>\
        <button type="button" action="findNext" class="ace_searchbtn next"></button>\
        <button type="button" action="findPrev" class="ace_searchbtn prev"></button>\
        <button type="button" action="findAll" class="ace_searchbtn" title="Alt-Enter">All</button>\
    </div>\
    <div class="ace_replace_form">\
        <input class="ace_search_field" placeholder="Replace with" spellcheck="false"></input>\
        <button type="button" action="replaceAndFindNext" class="ace_replacebtn">Replace</button>\
        <button type="button" action="replaceAll" class="ace_replacebtn">All</button>\
    </div>\
    <div class="ace_search_options">\
        <span action="toggleRegexpMode" class="ace_button" title="RegExp Search">.*</span>\
        <span action="toggleCaseSensitive" class="ace_button" title="CaseSensitive Search">Aa</span>\
        <span action="toggleWholeWords" class="ace_button" title="Whole Word Search">\\b</span>\
    </div>\
</div>'.replace(/>\s+/g, ">");

var SearchBox = function(editor, range, showReplaceForm) {
    var div = dom.createElement("div");
    div.innerHTML = html;
    this.element = div.firstChild;

    this.$init();
    this.setEditor(editor);
};

(function() {
    this.setEditor = function(editor) {
        editor.searchBox = this;
        editor.container.appendChild(this.element);
        this.editor = editor;
    };

    this.$initElements = function(sb) {
        this.searchBox = sb.querySelector(".ace_search_form");
        this.replaceBox = sb.querySelector(".ace_replace_form");
        this.searchOptions = sb.querySelector(".ace_search_options");
        this.regExpOption = sb.querySelector("[action=toggleRegexpMode]");
        this.caseSensitiveOption = sb.querySelector("[action=toggleCaseSensitive]");
        this.wholeWordOption = sb.querySelector("[action=toggleWholeWords]");
        this.searchInput = this.searchBox.querySelector(".ace_search_field");
        this.replaceInput = this.replaceBox.querySelector(".ace_search_field");
    };
    
    this.$init = function() {
        var sb = this.element;
        
        this.$initElements(sb);
        
        var _this = this;
        event.addListener(sb, "mousedown", function(e) {
            setTimeout(function(){
                _this.activeInput.focus();
            }, 0);
            event.stopPropagation(e);
        });
        event.addListener(sb, "click", function(e) {
            var t = e.target || e.srcElement;
            var action = t.getAttribute("action");
            if (action && _this[action])
                _this[action]();
            else if (_this.$searchBarKb.commands[action])
                _this.$searchBarKb.commands[action].exec(_this);
            event.stopPropagation(e);
        });

        event.addCommandKeyListener(sb, function(e, hashId, keyCode) {
            var keyString = keyUtil.keyCodeToString(keyCode);
            var command = _this.$searchBarKb.findKeyCommand(hashId, keyString);
            if (command && command.exec) {
                command.exec(_this);
                event.stopEvent(e);
            }
        });

        this.$onChange = lang.delayedCall(function() {
            _this.find(false, false);
        });

        event.addListener(this.searchInput, "input", function() {
            _this.$onChange.schedule(20);
        });
        event.addListener(this.searchInput, "focus", function() {
            _this.activeInput = _this.searchInput;
            _this.searchInput.value && _this.highlight();
        });
        event.addListener(this.replaceInput, "focus", function() {
            _this.activeInput = _this.replaceInput;
            _this.searchInput.value && _this.highlight();
        });
    };
    this.$closeSearchBarKb = new HashHandler([{
        bindKey: "Esc",
        name: "closeSearchBar",
        exec: function(editor) {
            editor.searchBox.hide();
        }
    }]);
    this.$searchBarKb = new HashHandler();
    this.$searchBarKb.bindKeys({
        "Ctrl-f|Command-f": function(sb) {
            var isReplace = sb.isReplace = !sb.isReplace;
            sb.replaceBox.style.display = isReplace ? "" : "none";
            sb.searchInput.focus();
        },
        "Ctrl-H|Command-Option-F": function(sb) {
            sb.replaceBox.style.display = "";
            sb.replaceInput.focus();
        },
        "Ctrl-G|Command-G": function(sb) {
            sb.findNext();
        },
        "Ctrl-Shift-G|Command-Shift-G": function(sb) {
            sb.findPrev();
        },
        "esc": function(sb) {
            setTimeout(function() { sb.hide();});
        },
        "Return": function(sb) {
            if (sb.activeInput == sb.replaceInput)
                sb.replace();
            sb.findNext();
        },
        "Shift-Return": function(sb) {
            if (sb.activeInput == sb.replaceInput)
                sb.replace();
            sb.findPrev();
        },
        "Alt-Return": function(sb) {
            if (sb.activeInput == sb.replaceInput)
                sb.replaceAll();
            sb.findAll();
        },
        "Tab": function(sb) {
            (sb.activeInput == sb.replaceInput ? sb.searchInput : sb.replaceInput).focus();
        }
    });

    this.$searchBarKb.addCommands([{
        name: "toggleRegexpMode",
        bindKey: {win: "Alt-R|Alt-/", mac: "Ctrl-Alt-R|Ctrl-Alt-/"},
        exec: function(sb) {
            sb.regExpOption.checked = !sb.regExpOption.checked;
            sb.$syncOptions();
        }
    }, {
        name: "toggleCaseSensitive",
        bindKey: {win: "Alt-C|Alt-I", mac: "Ctrl-Alt-R|Ctrl-Alt-I"},
        exec: function(sb) {
            sb.caseSensitiveOption.checked = !sb.caseSensitiveOption.checked;
            sb.$syncOptions();
        }
    }, {
        name: "toggleWholeWords",
        bindKey: {win: "Alt-B|Alt-W", mac: "Ctrl-Alt-B|Ctrl-Alt-W"},
        exec: function(sb) {
            sb.wholeWordOption.checked = !sb.wholeWordOption.checked;
            sb.$syncOptions();
        }
    }]);

    this.$syncOptions = function() {
        dom.setCssClass(this.regExpOption, "checked", this.regExpOption.checked);
        dom.setCssClass(this.wholeWordOption, "checked", this.wholeWordOption.checked);
        dom.setCssClass(this.caseSensitiveOption, "checked", this.caseSensitiveOption.checked);
        this.find(false, false);
    };

    this.highlight = function(re) {
        this.editor.session.highlight(re || this.editor.$search.$options.re);
        this.editor.renderer.updateBackMarkers()
    };
    this.find = function(skipCurrent, backwards, preventScroll) {
        var range = this.editor.find(this.searchInput.value, {
            skipCurrent: skipCurrent,
            backwards: backwards,
            wrap: true,
            regExp: this.regExpOption.checked,
            caseSensitive: this.caseSensitiveOption.checked,
            wholeWord: this.wholeWordOption.checked,
            preventScroll: preventScroll
        });
        var noMatch = !range && this.searchInput.value;
        dom.setCssClass(this.searchBox, "ace_nomatch", noMatch);
        this.editor._emit("findSearchBox", { match: !noMatch });
        this.highlight();
    };
    this.findNext = function() {
        this.find(true, false);
    };
    this.findPrev = function() {
        this.find(true, true);
    };
    this.findAll = function(){
        var range = this.editor.findAll(this.searchInput.value, {            
            regExp: this.regExpOption.checked,
            caseSensitive: this.caseSensitiveOption.checked,
            wholeWord: this.wholeWordOption.checked
        });
        var noMatch = !range && this.searchInput.value;
        dom.setCssClass(this.searchBox, "ace_nomatch", noMatch);
        this.editor._emit("findSearchBox", { match: !noMatch });
        this.highlight();
        this.hide();
    };
    this.replace = function() {
        if (!this.editor.getReadOnly())
            this.editor.replace(this.replaceInput.value);
    };    
    this.replaceAndFindNext = function() {
        if (!this.editor.getReadOnly()) {
            this.editor.replace(this.replaceInput.value);
            this.findNext()
        }
    };
    this.replaceAll = function() {
        if (!this.editor.getReadOnly())
            this.editor.replaceAll(this.replaceInput.value);
    };

    this.hide = function() {
        this.element.style.display = "none";
        this.editor.keyBinding.removeKeyboardHandler(this.$closeSearchBarKb);
        this.editor.focus();
    };
    this.show = function(value, isReplace) {
        this.element.style.display = "";
        this.replaceBox.style.display = isReplace ? "" : "none";

        this.isReplace = isReplace;

        if (value)
            this.searchInput.value = value;
        
        this.find(false, false, true);
        
        this.searchInput.focus();
        this.searchInput.select();

        this.editor.keyBinding.addKeyboardHandler(this.$closeSearchBarKb);
    };

    this.isFocused = function() {
        var el = document.activeElement;
        return el == this.searchInput || el == this.replaceInput;
    }
}).call(SearchBox.prototype);

exports.SearchBox = SearchBox;

exports.Search = function(editor, isReplace) {
    var sb = editor.searchBox || new SearchBox(editor);
    sb.show(editor.session.getTextRange(), isReplace);
};

});
                (function() {
                    ace.acequire(["ace/ext/searchbox"], function() {});
                })();
            

/***/ }),
/* 86 */
/***/ (function(module, exports, __webpack_require__) {

ace.define("ace/mode/json_highlight_rules",["require","exports","module","ace/lib/oop","ace/mode/text_highlight_rules"], function(acequire, exports, module) {
"use strict";

var oop = acequire("../lib/oop");
var TextHighlightRules = acequire("./text_highlight_rules").TextHighlightRules;

var JsonHighlightRules = function() {
    this.$rules = {
        "start" : [
            {
                token : "variable", // single line
                regex : '["](?:(?:\\\\.)|(?:[^"\\\\]))*?["]\\s*(?=:)'
            }, {
                token : "string", // single line
                regex : '"',
                next  : "string"
            }, {
                token : "constant.numeric", // hex
                regex : "0[xX][0-9a-fA-F]+\\b"
            }, {
                token : "constant.numeric", // float
                regex : "[+-]?\\d+(?:(?:\\.\\d*)?(?:[eE][+-]?\\d+)?)?\\b"
            }, {
                token : "constant.language.boolean",
                regex : "(?:true|false)\\b"
            }, {
                token : "invalid.illegal", // single quoted strings are not allowed
                regex : "['](?:(?:\\\\.)|(?:[^'\\\\]))*?[']"
            }, {
                token : "invalid.illegal", // comments are not allowed
                regex : "\\/\\/.*$"
            }, {
                token : "paren.lparen",
                regex : "[[({]"
            }, {
                token : "paren.rparen",
                regex : "[\\])}]"
            }, {
                token : "text",
                regex : "\\s+"
            }
        ],
        "string" : [
            {
                token : "constant.language.escape",
                regex : /\\(?:x[0-9a-fA-F]{2}|u[0-9a-fA-F]{4}|["\\\/bfnrt])/
            }, {
                token : "string",
                regex : '[^"\\\\]+'
            }, {
                token : "string",
                regex : '"',
                next  : "start"
            }, {
                token : "string",
                regex : "",
                next  : "start"
            }
        ]
    };
    
};

oop.inherits(JsonHighlightRules, TextHighlightRules);

exports.JsonHighlightRules = JsonHighlightRules;
});

ace.define("ace/mode/matching_brace_outdent",["require","exports","module","ace/range"], function(acequire, exports, module) {
"use strict";

var Range = acequire("../range").Range;

var MatchingBraceOutdent = function() {};

(function() {

    this.checkOutdent = function(line, input) {
        if (! /^\s+$/.test(line))
            return false;

        return /^\s*\}/.test(input);
    };

    this.autoOutdent = function(doc, row) {
        var line = doc.getLine(row);
        var match = line.match(/^(\s*\})/);

        if (!match) return 0;

        var column = match[1].length;
        var openBracePos = doc.findMatchingBracket({row: row, column: column});

        if (!openBracePos || openBracePos.row == row) return 0;

        var indent = this.$getIndent(doc.getLine(openBracePos.row));
        doc.replace(new Range(row, 0, row, column-1), indent);
    };

    this.$getIndent = function(line) {
        return line.match(/^\s*/)[0];
    };

}).call(MatchingBraceOutdent.prototype);

exports.MatchingBraceOutdent = MatchingBraceOutdent;
});

ace.define("ace/mode/folding/cstyle",["require","exports","module","ace/lib/oop","ace/range","ace/mode/folding/fold_mode"], function(acequire, exports, module) {
"use strict";

var oop = acequire("../../lib/oop");
var Range = acequire("../../range").Range;
var BaseFoldMode = acequire("./fold_mode").FoldMode;

var FoldMode = exports.FoldMode = function(commentRegex) {
    if (commentRegex) {
        this.foldingStartMarker = new RegExp(
            this.foldingStartMarker.source.replace(/\|[^|]*?$/, "|" + commentRegex.start)
        );
        this.foldingStopMarker = new RegExp(
            this.foldingStopMarker.source.replace(/\|[^|]*?$/, "|" + commentRegex.end)
        );
    }
};
oop.inherits(FoldMode, BaseFoldMode);

(function() {
    
    this.foldingStartMarker = /(\{|\[)[^\}\]]*$|^\s*(\/\*)/;
    this.foldingStopMarker = /^[^\[\{]*(\}|\])|^[\s\*]*(\*\/)/;
    this.singleLineBlockCommentRe= /^\s*(\/\*).*\*\/\s*$/;
    this.tripleStarBlockCommentRe = /^\s*(\/\*\*\*).*\*\/\s*$/;
    this.startRegionRe = /^\s*(\/\*|\/\/)#?region\b/;
    this._getFoldWidgetBase = this.getFoldWidget;
    this.getFoldWidget = function(session, foldStyle, row) {
        var line = session.getLine(row);
    
        if (this.singleLineBlockCommentRe.test(line)) {
            if (!this.startRegionRe.test(line) && !this.tripleStarBlockCommentRe.test(line))
                return "";
        }
    
        var fw = this._getFoldWidgetBase(session, foldStyle, row);
    
        if (!fw && this.startRegionRe.test(line))
            return "start"; // lineCommentRegionStart
    
        return fw;
    };

    this.getFoldWidgetRange = function(session, foldStyle, row, forceMultiline) {
        var line = session.getLine(row);
        
        if (this.startRegionRe.test(line))
            return this.getCommentRegionBlock(session, line, row);
        
        var match = line.match(this.foldingStartMarker);
        if (match) {
            var i = match.index;

            if (match[1])
                return this.openingBracketBlock(session, match[1], row, i);
                
            var range = session.getCommentFoldRange(row, i + match[0].length, 1);
            
            if (range && !range.isMultiLine()) {
                if (forceMultiline) {
                    range = this.getSectionRange(session, row);
                } else if (foldStyle != "all")
                    range = null;
            }
            
            return range;
        }

        if (foldStyle === "markbegin")
            return;

        var match = line.match(this.foldingStopMarker);
        if (match) {
            var i = match.index + match[0].length;

            if (match[1])
                return this.closingBracketBlock(session, match[1], row, i);

            return session.getCommentFoldRange(row, i, -1);
        }
    };
    
    this.getSectionRange = function(session, row) {
        var line = session.getLine(row);
        var startIndent = line.search(/\S/);
        var startRow = row;
        var startColumn = line.length;
        row = row + 1;
        var endRow = row;
        var maxRow = session.getLength();
        while (++row < maxRow) {
            line = session.getLine(row);
            var indent = line.search(/\S/);
            if (indent === -1)
                continue;
            if  (startIndent > indent)
                break;
            var subRange = this.getFoldWidgetRange(session, "all", row);
            
            if (subRange) {
                if (subRange.start.row <= startRow) {
                    break;
                } else if (subRange.isMultiLine()) {
                    row = subRange.end.row;
                } else if (startIndent == indent) {
                    break;
                }
            }
            endRow = row;
        }
        
        return new Range(startRow, startColumn, endRow, session.getLine(endRow).length);
    };
    this.getCommentRegionBlock = function(session, line, row) {
        var startColumn = line.search(/\s*$/);
        var maxRow = session.getLength();
        var startRow = row;
        
        var re = /^\s*(?:\/\*|\/\/|--)#?(end)?region\b/;
        var depth = 1;
        while (++row < maxRow) {
            line = session.getLine(row);
            var m = re.exec(line);
            if (!m) continue;
            if (m[1]) depth--;
            else depth++;

            if (!depth) break;
        }

        var endRow = row;
        if (endRow > startRow) {
            return new Range(startRow, startColumn, endRow, line.length);
        }
    };

}).call(FoldMode.prototype);

});

ace.define("ace/mode/json",["require","exports","module","ace/lib/oop","ace/mode/text","ace/mode/json_highlight_rules","ace/mode/matching_brace_outdent","ace/mode/behaviour/cstyle","ace/mode/folding/cstyle","ace/worker/worker_client"], function(acequire, exports, module) {
"use strict";

var oop = acequire("../lib/oop");
var TextMode = acequire("./text").Mode;
var HighlightRules = acequire("./json_highlight_rules").JsonHighlightRules;
var MatchingBraceOutdent = acequire("./matching_brace_outdent").MatchingBraceOutdent;
var CstyleBehaviour = acequire("./behaviour/cstyle").CstyleBehaviour;
var CStyleFoldMode = acequire("./folding/cstyle").FoldMode;
var WorkerClient = acequire("../worker/worker_client").WorkerClient;

var Mode = function() {
    this.HighlightRules = HighlightRules;
    this.$outdent = new MatchingBraceOutdent();
    this.$behaviour = new CstyleBehaviour();
    this.foldingRules = new CStyleFoldMode();
};
oop.inherits(Mode, TextMode);

(function() {

    this.getNextLineIndent = function(state, line, tab) {
        var indent = this.$getIndent(line);

        if (state == "start") {
            var match = line.match(/^.*[\{\(\[]\s*$/);
            if (match) {
                indent += tab;
            }
        }

        return indent;
    };

    this.checkOutdent = function(state, line, input) {
        return this.$outdent.checkOutdent(line, input);
    };

    this.autoOutdent = function(state, doc, row) {
        this.$outdent.autoOutdent(doc, row);
    };

    this.createWorker = function(session) {
        var worker = new WorkerClient(["ace"], __webpack_require__(87), "JsonWorker");
        worker.attachToDocument(session.getDocument());

        worker.on("annotate", function(e) {
            session.setAnnotations(e.data);
        });

        worker.on("terminate", function() {
            session.clearAnnotations();
        });

        return worker;
    };


    this.$id = "ace/mode/json";
}).call(Mode.prototype);

exports.Mode = Mode;
});


/***/ }),
/* 87 */
/***/ (function(module, exports) {

module.exports.id = 'ace/mode/json_worker';
module.exports.src = "\"no use strict\";(function(window){function resolveModuleId(id,paths){for(var testPath=id,tail=\"\";testPath;){var alias=paths[testPath];if(\"string\"==typeof alias)return alias+tail;if(alias)return alias.location.replace(/\\/*$/,\"/\")+(tail||alias.main||alias.name);if(alias===!1)return\"\";var i=testPath.lastIndexOf(\"/\");if(-1===i)break;tail=testPath.substr(i)+tail,testPath=testPath.slice(0,i)}return id}if(!(void 0!==window.window&&window.document||window.acequire&&window.define)){window.console||(window.console=function(){var msgs=Array.prototype.slice.call(arguments,0);postMessage({type:\"log\",data:msgs})},window.console.error=window.console.warn=window.console.log=window.console.trace=window.console),window.window=window,window.ace=window,window.onerror=function(message,file,line,col,err){postMessage({type:\"error\",data:{message:message,data:err.data,file:file,line:line,col:col,stack:err.stack}})},window.normalizeModule=function(parentId,moduleName){if(-1!==moduleName.indexOf(\"!\")){var chunks=moduleName.split(\"!\");return window.normalizeModule(parentId,chunks[0])+\"!\"+window.normalizeModule(parentId,chunks[1])}if(\".\"==moduleName.charAt(0)){var base=parentId.split(\"/\").slice(0,-1).join(\"/\");for(moduleName=(base?base+\"/\":\"\")+moduleName;-1!==moduleName.indexOf(\".\")&&previous!=moduleName;){var previous=moduleName;moduleName=moduleName.replace(/^\\.\\//,\"\").replace(/\\/\\.\\//,\"/\").replace(/[^\\/]+\\/\\.\\.\\//,\"\")}}return moduleName},window.acequire=function acequire(parentId,id){if(id||(id=parentId,parentId=null),!id.charAt)throw Error(\"worker.js acequire() accepts only (parentId, id) as arguments\");id=window.normalizeModule(parentId,id);var module=window.acequire.modules[id];if(module)return module.initialized||(module.initialized=!0,module.exports=module.factory().exports),module.exports;if(!window.acequire.tlns)return console.log(\"unable to load \"+id);var path=resolveModuleId(id,window.acequire.tlns);return\".js\"!=path.slice(-3)&&(path+=\".js\"),window.acequire.id=id,window.acequire.modules[id]={},importScripts(path),window.acequire(parentId,id)},window.acequire.modules={},window.acequire.tlns={},window.define=function(id,deps,factory){if(2==arguments.length?(factory=deps,\"string\"!=typeof id&&(deps=id,id=window.acequire.id)):1==arguments.length&&(factory=id,deps=[],id=window.acequire.id),\"function\"!=typeof factory)return window.acequire.modules[id]={exports:factory,initialized:!0},void 0;deps.length||(deps=[\"require\",\"exports\",\"module\"]);var req=function(childId){return window.acequire(id,childId)};window.acequire.modules[id]={exports:{},factory:function(){var module=this,returnExports=factory.apply(this,deps.map(function(dep){switch(dep){case\"require\":return req;case\"exports\":return module.exports;case\"module\":return module;default:return req(dep)}}));return returnExports&&(module.exports=returnExports),module}}},window.define.amd={},acequire.tlns={},window.initBaseUrls=function(topLevelNamespaces){for(var i in topLevelNamespaces)acequire.tlns[i]=topLevelNamespaces[i]},window.initSender=function(){var EventEmitter=window.acequire(\"ace/lib/event_emitter\").EventEmitter,oop=window.acequire(\"ace/lib/oop\"),Sender=function(){};return function(){oop.implement(this,EventEmitter),this.callback=function(data,callbackId){postMessage({type:\"call\",id:callbackId,data:data})},this.emit=function(name,data){postMessage({type:\"event\",name:name,data:data})}}.call(Sender.prototype),new Sender};var main=window.main=null,sender=window.sender=null;window.onmessage=function(e){var msg=e.data;if(msg.event&&sender)sender._signal(msg.event,msg.data);else if(msg.command)if(main[msg.command])main[msg.command].apply(main,msg.args);else{if(!window[msg.command])throw Error(\"Unknown command:\"+msg.command);window[msg.command].apply(window,msg.args)}else if(msg.init){window.initBaseUrls(msg.tlns),acequire(\"ace/lib/es5-shim\"),sender=window.sender=window.initSender();var clazz=acequire(msg.module)[msg.classname];main=window.main=new clazz(sender)}}}})(this),ace.define(\"ace/lib/oop\",[\"require\",\"exports\",\"module\"],function(acequire,exports){\"use strict\";exports.inherits=function(ctor,superCtor){ctor.super_=superCtor,ctor.prototype=Object.create(superCtor.prototype,{constructor:{value:ctor,enumerable:!1,writable:!0,configurable:!0}})},exports.mixin=function(obj,mixin){for(var key in mixin)obj[key]=mixin[key];return obj},exports.implement=function(proto,mixin){exports.mixin(proto,mixin)}}),ace.define(\"ace/range\",[\"require\",\"exports\",\"module\"],function(acequire,exports){\"use strict\";var comparePoints=function(p1,p2){return p1.row-p2.row||p1.column-p2.column},Range=function(startRow,startColumn,endRow,endColumn){this.start={row:startRow,column:startColumn},this.end={row:endRow,column:endColumn}};(function(){this.isEqual=function(range){return this.start.row===range.start.row&&this.end.row===range.end.row&&this.start.column===range.start.column&&this.end.column===range.end.column},this.toString=function(){return\"Range: [\"+this.start.row+\"/\"+this.start.column+\"] -> [\"+this.end.row+\"/\"+this.end.column+\"]\"},this.contains=function(row,column){return 0==this.compare(row,column)},this.compareRange=function(range){var cmp,end=range.end,start=range.start;return cmp=this.compare(end.row,end.column),1==cmp?(cmp=this.compare(start.row,start.column),1==cmp?2:0==cmp?1:0):-1==cmp?-2:(cmp=this.compare(start.row,start.column),-1==cmp?-1:1==cmp?42:0)},this.comparePoint=function(p){return this.compare(p.row,p.column)},this.containsRange=function(range){return 0==this.comparePoint(range.start)&&0==this.comparePoint(range.end)},this.intersects=function(range){var cmp=this.compareRange(range);return-1==cmp||0==cmp||1==cmp},this.isEnd=function(row,column){return this.end.row==row&&this.end.column==column},this.isStart=function(row,column){return this.start.row==row&&this.start.column==column},this.setStart=function(row,column){\"object\"==typeof row?(this.start.column=row.column,this.start.row=row.row):(this.start.row=row,this.start.column=column)},this.setEnd=function(row,column){\"object\"==typeof row?(this.end.column=row.column,this.end.row=row.row):(this.end.row=row,this.end.column=column)},this.inside=function(row,column){return 0==this.compare(row,column)?this.isEnd(row,column)||this.isStart(row,column)?!1:!0:!1},this.insideStart=function(row,column){return 0==this.compare(row,column)?this.isEnd(row,column)?!1:!0:!1},this.insideEnd=function(row,column){return 0==this.compare(row,column)?this.isStart(row,column)?!1:!0:!1},this.compare=function(row,column){return this.isMultiLine()||row!==this.start.row?this.start.row>row?-1:row>this.end.row?1:this.start.row===row?column>=this.start.column?0:-1:this.end.row===row?this.end.column>=column?0:1:0:this.start.column>column?-1:column>this.end.column?1:0},this.compareStart=function(row,column){return this.start.row==row&&this.start.column==column?-1:this.compare(row,column)},this.compareEnd=function(row,column){return this.end.row==row&&this.end.column==column?1:this.compare(row,column)},this.compareInside=function(row,column){return this.end.row==row&&this.end.column==column?1:this.start.row==row&&this.start.column==column?-1:this.compare(row,column)},this.clipRows=function(firstRow,lastRow){if(this.end.row>lastRow)var end={row:lastRow+1,column:0};else if(firstRow>this.end.row)var end={row:firstRow,column:0};if(this.start.row>lastRow)var start={row:lastRow+1,column:0};else if(firstRow>this.start.row)var start={row:firstRow,column:0};return Range.fromPoints(start||this.start,end||this.end)},this.extend=function(row,column){var cmp=this.compare(row,column);if(0==cmp)return this;if(-1==cmp)var start={row:row,column:column};else var end={row:row,column:column};return Range.fromPoints(start||this.start,end||this.end)},this.isEmpty=function(){return this.start.row===this.end.row&&this.start.column===this.end.column},this.isMultiLine=function(){return this.start.row!==this.end.row},this.clone=function(){return Range.fromPoints(this.start,this.end)},this.collapseRows=function(){return 0==this.end.column?new Range(this.start.row,0,Math.max(this.start.row,this.end.row-1),0):new Range(this.start.row,0,this.end.row,0)},this.toScreenRange=function(session){var screenPosStart=session.documentToScreenPosition(this.start),screenPosEnd=session.documentToScreenPosition(this.end);return new Range(screenPosStart.row,screenPosStart.column,screenPosEnd.row,screenPosEnd.column)},this.moveBy=function(row,column){this.start.row+=row,this.start.column+=column,this.end.row+=row,this.end.column+=column}}).call(Range.prototype),Range.fromPoints=function(start,end){return new Range(start.row,start.column,end.row,end.column)},Range.comparePoints=comparePoints,Range.comparePoints=function(p1,p2){return p1.row-p2.row||p1.column-p2.column},exports.Range=Range}),ace.define(\"ace/apply_delta\",[\"require\",\"exports\",\"module\"],function(acequire,exports){\"use strict\";exports.applyDelta=function(docLines,delta){var row=delta.start.row,startColumn=delta.start.column,line=docLines[row]||\"\";switch(delta.action){case\"insert\":var lines=delta.lines;if(1===lines.length)docLines[row]=line.substring(0,startColumn)+delta.lines[0]+line.substring(startColumn);else{var args=[row,1].concat(delta.lines);docLines.splice.apply(docLines,args),docLines[row]=line.substring(0,startColumn)+docLines[row],docLines[row+delta.lines.length-1]+=line.substring(startColumn)}break;case\"remove\":var endColumn=delta.end.column,endRow=delta.end.row;row===endRow?docLines[row]=line.substring(0,startColumn)+line.substring(endColumn):docLines.splice(row,endRow-row+1,line.substring(0,startColumn)+docLines[endRow].substring(endColumn))}}}),ace.define(\"ace/lib/event_emitter\",[\"require\",\"exports\",\"module\"],function(acequire,exports){\"use strict\";var EventEmitter={},stopPropagation=function(){this.propagationStopped=!0},preventDefault=function(){this.defaultPrevented=!0};EventEmitter._emit=EventEmitter._dispatchEvent=function(eventName,e){this._eventRegistry||(this._eventRegistry={}),this._defaultHandlers||(this._defaultHandlers={});var listeners=this._eventRegistry[eventName]||[],defaultHandler=this._defaultHandlers[eventName];if(listeners.length||defaultHandler){\"object\"==typeof e&&e||(e={}),e.type||(e.type=eventName),e.stopPropagation||(e.stopPropagation=stopPropagation),e.preventDefault||(e.preventDefault=preventDefault),listeners=listeners.slice();for(var i=0;listeners.length>i&&(listeners[i](e,this),!e.propagationStopped);i++);return defaultHandler&&!e.defaultPrevented?defaultHandler(e,this):void 0}},EventEmitter._signal=function(eventName,e){var listeners=(this._eventRegistry||{})[eventName];if(listeners){listeners=listeners.slice();for(var i=0;listeners.length>i;i++)listeners[i](e,this)}},EventEmitter.once=function(eventName,callback){var _self=this;callback&&this.addEventListener(eventName,function newCallback(){_self.removeEventListener(eventName,newCallback),callback.apply(null,arguments)})},EventEmitter.setDefaultHandler=function(eventName,callback){var handlers=this._defaultHandlers;if(handlers||(handlers=this._defaultHandlers={_disabled_:{}}),handlers[eventName]){var old=handlers[eventName],disabled=handlers._disabled_[eventName];disabled||(handlers._disabled_[eventName]=disabled=[]),disabled.push(old);var i=disabled.indexOf(callback);-1!=i&&disabled.splice(i,1)}handlers[eventName]=callback},EventEmitter.removeDefaultHandler=function(eventName,callback){var handlers=this._defaultHandlers;if(handlers){var disabled=handlers._disabled_[eventName];if(handlers[eventName]==callback)handlers[eventName],disabled&&this.setDefaultHandler(eventName,disabled.pop());else if(disabled){var i=disabled.indexOf(callback);-1!=i&&disabled.splice(i,1)}}},EventEmitter.on=EventEmitter.addEventListener=function(eventName,callback,capturing){this._eventRegistry=this._eventRegistry||{};var listeners=this._eventRegistry[eventName];return listeners||(listeners=this._eventRegistry[eventName]=[]),-1==listeners.indexOf(callback)&&listeners[capturing?\"unshift\":\"push\"](callback),callback},EventEmitter.off=EventEmitter.removeListener=EventEmitter.removeEventListener=function(eventName,callback){this._eventRegistry=this._eventRegistry||{};var listeners=this._eventRegistry[eventName];if(listeners){var index=listeners.indexOf(callback);-1!==index&&listeners.splice(index,1)}},EventEmitter.removeAllListeners=function(eventName){this._eventRegistry&&(this._eventRegistry[eventName]=[])},exports.EventEmitter=EventEmitter}),ace.define(\"ace/anchor\",[\"require\",\"exports\",\"module\",\"ace/lib/oop\",\"ace/lib/event_emitter\"],function(acequire,exports){\"use strict\";var oop=acequire(\"./lib/oop\"),EventEmitter=acequire(\"./lib/event_emitter\").EventEmitter,Anchor=exports.Anchor=function(doc,row,column){this.$onChange=this.onChange.bind(this),this.attach(doc),column===void 0?this.setPosition(row.row,row.column):this.setPosition(row,column)};(function(){function $pointsInOrder(point1,point2,equalPointsInOrder){var bColIsAfter=equalPointsInOrder?point1.column<=point2.column:point1.column<point2.column;return point1.row<point2.row||point1.row==point2.row&&bColIsAfter}function $getTransformedPoint(delta,point,moveIfEqual){var deltaIsInsert=\"insert\"==delta.action,deltaRowShift=(deltaIsInsert?1:-1)*(delta.end.row-delta.start.row),deltaColShift=(deltaIsInsert?1:-1)*(delta.end.column-delta.start.column),deltaStart=delta.start,deltaEnd=deltaIsInsert?deltaStart:delta.end;return $pointsInOrder(point,deltaStart,moveIfEqual)?{row:point.row,column:point.column}:$pointsInOrder(deltaEnd,point,!moveIfEqual)?{row:point.row+deltaRowShift,column:point.column+(point.row==deltaEnd.row?deltaColShift:0)}:{row:deltaStart.row,column:deltaStart.column}}oop.implement(this,EventEmitter),this.getPosition=function(){return this.$clipPositionToDocument(this.row,this.column)},this.getDocument=function(){return this.document},this.$insertRight=!1,this.onChange=function(delta){if(!(delta.start.row==delta.end.row&&delta.start.row!=this.row||delta.start.row>this.row)){var point=$getTransformedPoint(delta,{row:this.row,column:this.column},this.$insertRight);this.setPosition(point.row,point.column,!0)}},this.setPosition=function(row,column,noClip){var pos;if(pos=noClip?{row:row,column:column}:this.$clipPositionToDocument(row,column),this.row!=pos.row||this.column!=pos.column){var old={row:this.row,column:this.column};this.row=pos.row,this.column=pos.column,this._signal(\"change\",{old:old,value:pos})}},this.detach=function(){this.document.removeEventListener(\"change\",this.$onChange)},this.attach=function(doc){this.document=doc||this.document,this.document.on(\"change\",this.$onChange)},this.$clipPositionToDocument=function(row,column){var pos={};return row>=this.document.getLength()?(pos.row=Math.max(0,this.document.getLength()-1),pos.column=this.document.getLine(pos.row).length):0>row?(pos.row=0,pos.column=0):(pos.row=row,pos.column=Math.min(this.document.getLine(pos.row).length,Math.max(0,column))),0>column&&(pos.column=0),pos}}).call(Anchor.prototype)}),ace.define(\"ace/document\",[\"require\",\"exports\",\"module\",\"ace/lib/oop\",\"ace/apply_delta\",\"ace/lib/event_emitter\",\"ace/range\",\"ace/anchor\"],function(acequire,exports){\"use strict\";var oop=acequire(\"./lib/oop\"),applyDelta=acequire(\"./apply_delta\").applyDelta,EventEmitter=acequire(\"./lib/event_emitter\").EventEmitter,Range=acequire(\"./range\").Range,Anchor=acequire(\"./anchor\").Anchor,Document=function(textOrLines){this.$lines=[\"\"],0===textOrLines.length?this.$lines=[\"\"]:Array.isArray(textOrLines)?this.insertMergedLines({row:0,column:0},textOrLines):this.insert({row:0,column:0},textOrLines)};(function(){oop.implement(this,EventEmitter),this.setValue=function(text){var len=this.getLength()-1;this.remove(new Range(0,0,len,this.getLine(len).length)),this.insert({row:0,column:0},text)},this.getValue=function(){return this.getAllLines().join(this.getNewLineCharacter())},this.createAnchor=function(row,column){return new Anchor(this,row,column)},this.$split=0===\"aaa\".split(/a/).length?function(text){return text.replace(/\\r\\n|\\r/g,\"\\n\").split(\"\\n\")}:function(text){return text.split(/\\r\\n|\\r|\\n/)},this.$detectNewLine=function(text){var match=text.match(/^.*?(\\r\\n|\\r|\\n)/m);this.$autoNewLine=match?match[1]:\"\\n\",this._signal(\"changeNewLineMode\")},this.getNewLineCharacter=function(){switch(this.$newLineMode){case\"windows\":return\"\\r\\n\";case\"unix\":return\"\\n\";default:return this.$autoNewLine||\"\\n\"}},this.$autoNewLine=\"\",this.$newLineMode=\"auto\",this.setNewLineMode=function(newLineMode){this.$newLineMode!==newLineMode&&(this.$newLineMode=newLineMode,this._signal(\"changeNewLineMode\"))},this.getNewLineMode=function(){return this.$newLineMode},this.isNewLine=function(text){return\"\\r\\n\"==text||\"\\r\"==text||\"\\n\"==text},this.getLine=function(row){return this.$lines[row]||\"\"},this.getLines=function(firstRow,lastRow){return this.$lines.slice(firstRow,lastRow+1)},this.getAllLines=function(){return this.getLines(0,this.getLength())},this.getLength=function(){return this.$lines.length},this.getTextRange=function(range){return this.getLinesForRange(range).join(this.getNewLineCharacter())},this.getLinesForRange=function(range){var lines;if(range.start.row===range.end.row)lines=[this.getLine(range.start.row).substring(range.start.column,range.end.column)];else{lines=this.getLines(range.start.row,range.end.row),lines[0]=(lines[0]||\"\").substring(range.start.column);var l=lines.length-1;range.end.row-range.start.row==l&&(lines[l]=lines[l].substring(0,range.end.column))}return lines},this.insertLines=function(row,lines){return console.warn(\"Use of document.insertLines is deprecated. Use the insertFullLines method instead.\"),this.insertFullLines(row,lines)},this.removeLines=function(firstRow,lastRow){return console.warn(\"Use of document.removeLines is deprecated. Use the removeFullLines method instead.\"),this.removeFullLines(firstRow,lastRow)},this.insertNewLine=function(position){return console.warn(\"Use of document.insertNewLine is deprecated. Use insertMergedLines(position, ['', '']) instead.\"),this.insertMergedLines(position,[\"\",\"\"])},this.insert=function(position,text){return 1>=this.getLength()&&this.$detectNewLine(text),this.insertMergedLines(position,this.$split(text))},this.insertInLine=function(position,text){var start=this.clippedPos(position.row,position.column),end=this.pos(position.row,position.column+text.length);return this.applyDelta({start:start,end:end,action:\"insert\",lines:[text]},!0),this.clonePos(end)},this.clippedPos=function(row,column){var length=this.getLength();void 0===row?row=length:0>row?row=0:row>=length&&(row=length-1,column=void 0);var line=this.getLine(row);return void 0==column&&(column=line.length),column=Math.min(Math.max(column,0),line.length),{row:row,column:column}},this.clonePos=function(pos){return{row:pos.row,column:pos.column}},this.pos=function(row,column){return{row:row,column:column}},this.$clipPosition=function(position){var length=this.getLength();return position.row>=length?(position.row=Math.max(0,length-1),position.column=this.getLine(length-1).length):(position.row=Math.max(0,position.row),position.column=Math.min(Math.max(position.column,0),this.getLine(position.row).length)),position},this.insertFullLines=function(row,lines){row=Math.min(Math.max(row,0),this.getLength());var column=0;this.getLength()>row?(lines=lines.concat([\"\"]),column=0):(lines=[\"\"].concat(lines),row--,column=this.$lines[row].length),this.insertMergedLines({row:row,column:column},lines)},this.insertMergedLines=function(position,lines){var start=this.clippedPos(position.row,position.column),end={row:start.row+lines.length-1,column:(1==lines.length?start.column:0)+lines[lines.length-1].length};return this.applyDelta({start:start,end:end,action:\"insert\",lines:lines}),this.clonePos(end)},this.remove=function(range){var start=this.clippedPos(range.start.row,range.start.column),end=this.clippedPos(range.end.row,range.end.column);return this.applyDelta({start:start,end:end,action:\"remove\",lines:this.getLinesForRange({start:start,end:end})}),this.clonePos(start)},this.removeInLine=function(row,startColumn,endColumn){var start=this.clippedPos(row,startColumn),end=this.clippedPos(row,endColumn);return this.applyDelta({start:start,end:end,action:\"remove\",lines:this.getLinesForRange({start:start,end:end})},!0),this.clonePos(start)},this.removeFullLines=function(firstRow,lastRow){firstRow=Math.min(Math.max(0,firstRow),this.getLength()-1),lastRow=Math.min(Math.max(0,lastRow),this.getLength()-1);var deleteFirstNewLine=lastRow==this.getLength()-1&&firstRow>0,deleteLastNewLine=this.getLength()-1>lastRow,startRow=deleteFirstNewLine?firstRow-1:firstRow,startCol=deleteFirstNewLine?this.getLine(startRow).length:0,endRow=deleteLastNewLine?lastRow+1:lastRow,endCol=deleteLastNewLine?0:this.getLine(endRow).length,range=new Range(startRow,startCol,endRow,endCol),deletedLines=this.$lines.slice(firstRow,lastRow+1);return this.applyDelta({start:range.start,end:range.end,action:\"remove\",lines:this.getLinesForRange(range)}),deletedLines},this.removeNewLine=function(row){this.getLength()-1>row&&row>=0&&this.applyDelta({start:this.pos(row,this.getLine(row).length),end:this.pos(row+1,0),action:\"remove\",lines:[\"\",\"\"]})},this.replace=function(range,text){if(range instanceof Range||(range=Range.fromPoints(range.start,range.end)),0===text.length&&range.isEmpty())return range.start;if(text==this.getTextRange(range))return range.end;this.remove(range);var end;return end=text?this.insert(range.start,text):range.start},this.applyDeltas=function(deltas){for(var i=0;deltas.length>i;i++)this.applyDelta(deltas[i])},this.revertDeltas=function(deltas){for(var i=deltas.length-1;i>=0;i--)this.revertDelta(deltas[i])},this.applyDelta=function(delta,doNotValidate){var isInsert=\"insert\"==delta.action;(isInsert?1>=delta.lines.length&&!delta.lines[0]:!Range.comparePoints(delta.start,delta.end))||(isInsert&&delta.lines.length>2e4&&this.$splitAndapplyLargeDelta(delta,2e4),applyDelta(this.$lines,delta,doNotValidate),this._signal(\"change\",delta))},this.$splitAndapplyLargeDelta=function(delta,MAX){for(var lines=delta.lines,l=lines.length,row=delta.start.row,column=delta.start.column,from=0,to=0;;){from=to,to+=MAX-1;var chunk=lines.slice(from,to);if(to>l){delta.lines=chunk,delta.start.row=row+from,delta.start.column=column;break}chunk.push(\"\"),this.applyDelta({start:this.pos(row+from,column),end:this.pos(row+to,column=0),action:delta.action,lines:chunk},!0)}},this.revertDelta=function(delta){this.applyDelta({start:this.clonePos(delta.start),end:this.clonePos(delta.end),action:\"insert\"==delta.action?\"remove\":\"insert\",lines:delta.lines.slice()})},this.indexToPosition=function(index,startRow){for(var lines=this.$lines||this.getAllLines(),newlineLength=this.getNewLineCharacter().length,i=startRow||0,l=lines.length;l>i;i++)if(index-=lines[i].length+newlineLength,0>index)return{row:i,column:index+lines[i].length+newlineLength};return{row:l-1,column:lines[l-1].length}},this.positionToIndex=function(pos,startRow){for(var lines=this.$lines||this.getAllLines(),newlineLength=this.getNewLineCharacter().length,index=0,row=Math.min(pos.row,lines.length),i=startRow||0;row>i;++i)index+=lines[i].length+newlineLength;return index+pos.column}}).call(Document.prototype),exports.Document=Document}),ace.define(\"ace/lib/lang\",[\"require\",\"exports\",\"module\"],function(acequire,exports){\"use strict\";exports.last=function(a){return a[a.length-1]},exports.stringReverse=function(string){return string.split(\"\").reverse().join(\"\")},exports.stringRepeat=function(string,count){for(var result=\"\";count>0;)1&count&&(result+=string),(count>>=1)&&(string+=string);return result};var trimBeginRegexp=/^\\s\\s*/,trimEndRegexp=/\\s\\s*$/;exports.stringTrimLeft=function(string){return string.replace(trimBeginRegexp,\"\")},exports.stringTrimRight=function(string){return string.replace(trimEndRegexp,\"\")},exports.copyObject=function(obj){var copy={};for(var key in obj)copy[key]=obj[key];return copy},exports.copyArray=function(array){for(var copy=[],i=0,l=array.length;l>i;i++)copy[i]=array[i]&&\"object\"==typeof array[i]?this.copyObject(array[i]):array[i];return copy},exports.deepCopy=function deepCopy(obj){if(\"object\"!=typeof obj||!obj)return obj;var copy;if(Array.isArray(obj)){copy=[];for(var key=0;obj.length>key;key++)copy[key]=deepCopy(obj[key]);return copy}if(\"[object Object]\"!==Object.prototype.toString.call(obj))return obj;copy={};for(var key in obj)copy[key]=deepCopy(obj[key]);return copy},exports.arrayToMap=function(arr){for(var map={},i=0;arr.length>i;i++)map[arr[i]]=1;return map},exports.createMap=function(props){var map=Object.create(null);for(var i in props)map[i]=props[i];return map},exports.arrayRemove=function(array,value){for(var i=0;array.length>=i;i++)value===array[i]&&array.splice(i,1)},exports.escapeRegExp=function(str){return str.replace(/([.*+?^${}()|[\\]\\/\\\\])/g,\"\\\\$1\")},exports.escapeHTML=function(str){return str.replace(/&/g,\"&#38;\").replace(/\"/g,\"&#34;\").replace(/'/g,\"&#39;\").replace(/</g,\"&#60;\")},exports.getMatchOffsets=function(string,regExp){var matches=[];return string.replace(regExp,function(str){matches.push({offset:arguments[arguments.length-2],length:str.length})}),matches},exports.deferredCall=function(fcn){var timer=null,callback=function(){timer=null,fcn()},deferred=function(timeout){return deferred.cancel(),timer=setTimeout(callback,timeout||0),deferred};return deferred.schedule=deferred,deferred.call=function(){return this.cancel(),fcn(),deferred},deferred.cancel=function(){return clearTimeout(timer),timer=null,deferred},deferred.isPending=function(){return timer},deferred},exports.delayedCall=function(fcn,defaultTimeout){var timer=null,callback=function(){timer=null,fcn()},_self=function(timeout){null==timer&&(timer=setTimeout(callback,timeout||defaultTimeout))};return _self.delay=function(timeout){timer&&clearTimeout(timer),timer=setTimeout(callback,timeout||defaultTimeout)},_self.schedule=_self,_self.call=function(){this.cancel(),fcn()},_self.cancel=function(){timer&&clearTimeout(timer),timer=null},_self.isPending=function(){return timer},_self}}),ace.define(\"ace/worker/mirror\",[\"require\",\"exports\",\"module\",\"ace/range\",\"ace/document\",\"ace/lib/lang\"],function(acequire,exports){\"use strict\";acequire(\"../range\").Range;var Document=acequire(\"../document\").Document,lang=acequire(\"../lib/lang\"),Mirror=exports.Mirror=function(sender){this.sender=sender;var doc=this.doc=new Document(\"\"),deferredUpdate=this.deferredUpdate=lang.delayedCall(this.onUpdate.bind(this)),_self=this;sender.on(\"change\",function(e){var data=e.data;if(data[0].start)doc.applyDeltas(data);else for(var i=0;data.length>i;i+=2){if(Array.isArray(data[i+1]))var d={action:\"insert\",start:data[i],lines:data[i+1]};else var d={action:\"remove\",start:data[i],end:data[i+1]};doc.applyDelta(d,!0)}return _self.$timeout?deferredUpdate.schedule(_self.$timeout):(_self.onUpdate(),void 0)})};(function(){this.$timeout=500,this.setTimeout=function(timeout){this.$timeout=timeout},this.setValue=function(value){this.doc.setValue(value),this.deferredUpdate.schedule(this.$timeout)},this.getValue=function(callbackId){this.sender.callback(this.doc.getValue(),callbackId)},this.onUpdate=function(){},this.isPending=function(){return this.deferredUpdate.isPending()}}).call(Mirror.prototype)}),ace.define(\"ace/mode/json/json_parse\",[\"require\",\"exports\",\"module\"],function(){\"use strict\";var at,ch,text,value,escapee={'\"':'\"',\"\\\\\":\"\\\\\",\"/\":\"/\",b:\"\\b\",f:\"\\f\",n:\"\\n\",r:\"\\r\",t:\"\t\"},error=function(m){throw{name:\"SyntaxError\",message:m,at:at,text:text}},next=function(c){return c&&c!==ch&&error(\"Expected '\"+c+\"' instead of '\"+ch+\"'\"),ch=text.charAt(at),at+=1,ch},number=function(){var number,string=\"\";for(\"-\"===ch&&(string=\"-\",next(\"-\"));ch>=\"0\"&&\"9\">=ch;)string+=ch,next();if(\".\"===ch)for(string+=\".\";next()&&ch>=\"0\"&&\"9\">=ch;)string+=ch;if(\"e\"===ch||\"E\"===ch)for(string+=ch,next(),(\"-\"===ch||\"+\"===ch)&&(string+=ch,next());ch>=\"0\"&&\"9\">=ch;)string+=ch,next();return number=+string,isNaN(number)?(error(\"Bad number\"),void 0):number},string=function(){var hex,i,uffff,string=\"\";if('\"'===ch)for(;next();){if('\"'===ch)return next(),string;if(\"\\\\\"===ch)if(next(),\"u\"===ch){for(uffff=0,i=0;4>i&&(hex=parseInt(next(),16),isFinite(hex));i+=1)uffff=16*uffff+hex;string+=String.fromCharCode(uffff)}else{if(\"string\"!=typeof escapee[ch])break;string+=escapee[ch]}else string+=ch}error(\"Bad string\")},white=function(){for(;ch&&\" \">=ch;)next()},word=function(){switch(ch){case\"t\":return next(\"t\"),next(\"r\"),next(\"u\"),next(\"e\"),!0;case\"f\":return next(\"f\"),next(\"a\"),next(\"l\"),next(\"s\"),next(\"e\"),!1;case\"n\":return next(\"n\"),next(\"u\"),next(\"l\"),next(\"l\"),null}error(\"Unexpected '\"+ch+\"'\")},array=function(){var array=[];if(\"[\"===ch){if(next(\"[\"),white(),\"]\"===ch)return next(\"]\"),array;for(;ch;){if(array.push(value()),white(),\"]\"===ch)return next(\"]\"),array;next(\",\"),white()}}error(\"Bad array\")},object=function(){var key,object={};if(\"{\"===ch){if(next(\"{\"),white(),\"}\"===ch)return next(\"}\"),object;for(;ch;){if(key=string(),white(),next(\":\"),Object.hasOwnProperty.call(object,key)&&error('Duplicate key \"'+key+'\"'),object[key]=value(),white(),\"}\"===ch)return next(\"}\"),object;next(\",\"),white()}}error(\"Bad object\")};return value=function(){switch(white(),ch){case\"{\":return object();case\"[\":return array();case'\"':return string();case\"-\":return number();default:return ch>=\"0\"&&\"9\">=ch?number():word()}},function(source,reviver){var result;return text=source,at=0,ch=\" \",result=value(),white(),ch&&error(\"Syntax error\"),\"function\"==typeof reviver?function walk(holder,key){var k,v,value=holder[key];if(value&&\"object\"==typeof value)for(k in value)Object.hasOwnProperty.call(value,k)&&(v=walk(value,k),void 0!==v?value[k]=v:delete value[k]);return reviver.call(holder,key,value)}({\"\":result},\"\"):result}}),ace.define(\"ace/mode/json_worker\",[\"require\",\"exports\",\"module\",\"ace/lib/oop\",\"ace/worker/mirror\",\"ace/mode/json/json_parse\"],function(acequire,exports){\"use strict\";var oop=acequire(\"../lib/oop\"),Mirror=acequire(\"../worker/mirror\").Mirror,parse=acequire(\"./json/json_parse\"),JsonWorker=exports.JsonWorker=function(sender){Mirror.call(this,sender),this.setTimeout(200)};oop.inherits(JsonWorker,Mirror),function(){this.onUpdate=function(){var value=this.doc.getValue(),errors=[];try{value&&parse(value)}catch(e){var pos=this.doc.indexToPosition(e.at-1);errors.push({row:pos.row,column:pos.column,text:e.message,type:\"error\"})}this.sender.emit(\"annotate\",errors)}}.call(JsonWorker.prototype)}),ace.define(\"ace/lib/es5-shim\",[\"require\",\"exports\",\"module\"],function(){function Empty(){}function doesDefinePropertyWork(object){try{return Object.defineProperty(object,\"sentinel\",{}),\"sentinel\"in object}catch(exception){}}function toInteger(n){return n=+n,n!==n?n=0:0!==n&&n!==1/0&&n!==-(1/0)&&(n=(n>0||-1)*Math.floor(Math.abs(n))),n}Function.prototype.bind||(Function.prototype.bind=function(that){var target=this;if(\"function\"!=typeof target)throw new TypeError(\"Function.prototype.bind called on incompatible \"+target);var args=slice.call(arguments,1),bound=function(){if(this instanceof bound){var result=target.apply(this,args.concat(slice.call(arguments)));return Object(result)===result?result:this}return target.apply(that,args.concat(slice.call(arguments)))};return target.prototype&&(Empty.prototype=target.prototype,bound.prototype=new Empty,Empty.prototype=null),bound});var defineGetter,defineSetter,lookupGetter,lookupSetter,supportsAccessors,call=Function.prototype.call,prototypeOfArray=Array.prototype,prototypeOfObject=Object.prototype,slice=prototypeOfArray.slice,_toString=call.bind(prototypeOfObject.toString),owns=call.bind(prototypeOfObject.hasOwnProperty);if((supportsAccessors=owns(prototypeOfObject,\"__defineGetter__\"))&&(defineGetter=call.bind(prototypeOfObject.__defineGetter__),defineSetter=call.bind(prototypeOfObject.__defineSetter__),lookupGetter=call.bind(prototypeOfObject.__lookupGetter__),lookupSetter=call.bind(prototypeOfObject.__lookupSetter__)),2!=[1,2].splice(0).length)if(function(){function makeArray(l){var a=Array(l+2);return a[0]=a[1]=0,a}var lengthBefore,array=[];return array.splice.apply(array,makeArray(20)),array.splice.apply(array,makeArray(26)),lengthBefore=array.length,array.splice(5,0,\"XXX\"),lengthBefore+1==array.length,lengthBefore+1==array.length?!0:void 0\n}()){var array_splice=Array.prototype.splice;Array.prototype.splice=function(start,deleteCount){return arguments.length?array_splice.apply(this,[void 0===start?0:start,void 0===deleteCount?this.length-start:deleteCount].concat(slice.call(arguments,2))):[]}}else Array.prototype.splice=function(pos,removeCount){var length=this.length;pos>0?pos>length&&(pos=length):void 0==pos?pos=0:0>pos&&(pos=Math.max(length+pos,0)),length>pos+removeCount||(removeCount=length-pos);var removed=this.slice(pos,pos+removeCount),insert=slice.call(arguments,2),add=insert.length;if(pos===length)add&&this.push.apply(this,insert);else{var remove=Math.min(removeCount,length-pos),tailOldPos=pos+remove,tailNewPos=tailOldPos+add-remove,tailCount=length-tailOldPos,lengthAfterRemove=length-remove;if(tailOldPos>tailNewPos)for(var i=0;tailCount>i;++i)this[tailNewPos+i]=this[tailOldPos+i];else if(tailNewPos>tailOldPos)for(i=tailCount;i--;)this[tailNewPos+i]=this[tailOldPos+i];if(add&&pos===lengthAfterRemove)this.length=lengthAfterRemove,this.push.apply(this,insert);else for(this.length=lengthAfterRemove+add,i=0;add>i;++i)this[pos+i]=insert[i]}return removed};Array.isArray||(Array.isArray=function(obj){return\"[object Array]\"==_toString(obj)});var boxedString=Object(\"a\"),splitString=\"a\"!=boxedString[0]||!(0 in boxedString);if(Array.prototype.forEach||(Array.prototype.forEach=function(fun){var object=toObject(this),self=splitString&&\"[object String]\"==_toString(this)?this.split(\"\"):object,thisp=arguments[1],i=-1,length=self.length>>>0;if(\"[object Function]\"!=_toString(fun))throw new TypeError;for(;length>++i;)i in self&&fun.call(thisp,self[i],i,object)}),Array.prototype.map||(Array.prototype.map=function(fun){var object=toObject(this),self=splitString&&\"[object String]\"==_toString(this)?this.split(\"\"):object,length=self.length>>>0,result=Array(length),thisp=arguments[1];if(\"[object Function]\"!=_toString(fun))throw new TypeError(fun+\" is not a function\");for(var i=0;length>i;i++)i in self&&(result[i]=fun.call(thisp,self[i],i,object));return result}),Array.prototype.filter||(Array.prototype.filter=function(fun){var value,object=toObject(this),self=splitString&&\"[object String]\"==_toString(this)?this.split(\"\"):object,length=self.length>>>0,result=[],thisp=arguments[1];if(\"[object Function]\"!=_toString(fun))throw new TypeError(fun+\" is not a function\");for(var i=0;length>i;i++)i in self&&(value=self[i],fun.call(thisp,value,i,object)&&result.push(value));return result}),Array.prototype.every||(Array.prototype.every=function(fun){var object=toObject(this),self=splitString&&\"[object String]\"==_toString(this)?this.split(\"\"):object,length=self.length>>>0,thisp=arguments[1];if(\"[object Function]\"!=_toString(fun))throw new TypeError(fun+\" is not a function\");for(var i=0;length>i;i++)if(i in self&&!fun.call(thisp,self[i],i,object))return!1;return!0}),Array.prototype.some||(Array.prototype.some=function(fun){var object=toObject(this),self=splitString&&\"[object String]\"==_toString(this)?this.split(\"\"):object,length=self.length>>>0,thisp=arguments[1];if(\"[object Function]\"!=_toString(fun))throw new TypeError(fun+\" is not a function\");for(var i=0;length>i;i++)if(i in self&&fun.call(thisp,self[i],i,object))return!0;return!1}),Array.prototype.reduce||(Array.prototype.reduce=function(fun){var object=toObject(this),self=splitString&&\"[object String]\"==_toString(this)?this.split(\"\"):object,length=self.length>>>0;if(\"[object Function]\"!=_toString(fun))throw new TypeError(fun+\" is not a function\");if(!length&&1==arguments.length)throw new TypeError(\"reduce of empty array with no initial value\");var result,i=0;if(arguments.length>=2)result=arguments[1];else for(;;){if(i in self){result=self[i++];break}if(++i>=length)throw new TypeError(\"reduce of empty array with no initial value\")}for(;length>i;i++)i in self&&(result=fun.call(void 0,result,self[i],i,object));return result}),Array.prototype.reduceRight||(Array.prototype.reduceRight=function(fun){var object=toObject(this),self=splitString&&\"[object String]\"==_toString(this)?this.split(\"\"):object,length=self.length>>>0;if(\"[object Function]\"!=_toString(fun))throw new TypeError(fun+\" is not a function\");if(!length&&1==arguments.length)throw new TypeError(\"reduceRight of empty array with no initial value\");var result,i=length-1;if(arguments.length>=2)result=arguments[1];else for(;;){if(i in self){result=self[i--];break}if(0>--i)throw new TypeError(\"reduceRight of empty array with no initial value\")}do i in this&&(result=fun.call(void 0,result,self[i],i,object));while(i--);return result}),Array.prototype.indexOf&&-1==[0,1].indexOf(1,2)||(Array.prototype.indexOf=function(sought){var self=splitString&&\"[object String]\"==_toString(this)?this.split(\"\"):toObject(this),length=self.length>>>0;if(!length)return-1;var i=0;for(arguments.length>1&&(i=toInteger(arguments[1])),i=i>=0?i:Math.max(0,length+i);length>i;i++)if(i in self&&self[i]===sought)return i;return-1}),Array.prototype.lastIndexOf&&-1==[0,1].lastIndexOf(0,-3)||(Array.prototype.lastIndexOf=function(sought){var self=splitString&&\"[object String]\"==_toString(this)?this.split(\"\"):toObject(this),length=self.length>>>0;if(!length)return-1;var i=length-1;for(arguments.length>1&&(i=Math.min(i,toInteger(arguments[1]))),i=i>=0?i:length-Math.abs(i);i>=0;i--)if(i in self&&sought===self[i])return i;return-1}),Object.getPrototypeOf||(Object.getPrototypeOf=function(object){return object.__proto__||(object.constructor?object.constructor.prototype:prototypeOfObject)}),!Object.getOwnPropertyDescriptor){var ERR_NON_OBJECT=\"Object.getOwnPropertyDescriptor called on a non-object: \";Object.getOwnPropertyDescriptor=function(object,property){if(\"object\"!=typeof object&&\"function\"!=typeof object||null===object)throw new TypeError(ERR_NON_OBJECT+object);if(owns(object,property)){var descriptor,getter,setter;if(descriptor={enumerable:!0,configurable:!0},supportsAccessors){var prototype=object.__proto__;object.__proto__=prototypeOfObject;var getter=lookupGetter(object,property),setter=lookupSetter(object,property);if(object.__proto__=prototype,getter||setter)return getter&&(descriptor.get=getter),setter&&(descriptor.set=setter),descriptor}return descriptor.value=object[property],descriptor}}}if(Object.getOwnPropertyNames||(Object.getOwnPropertyNames=function(object){return Object.keys(object)}),!Object.create){var createEmpty;createEmpty=null===Object.prototype.__proto__?function(){return{__proto__:null}}:function(){var empty={};for(var i in empty)empty[i]=null;return empty.constructor=empty.hasOwnProperty=empty.propertyIsEnumerable=empty.isPrototypeOf=empty.toLocaleString=empty.toString=empty.valueOf=empty.__proto__=null,empty},Object.create=function(prototype,properties){var object;if(null===prototype)object=createEmpty();else{if(\"object\"!=typeof prototype)throw new TypeError(\"typeof prototype[\"+typeof prototype+\"] != 'object'\");var Type=function(){};Type.prototype=prototype,object=new Type,object.__proto__=prototype}return void 0!==properties&&Object.defineProperties(object,properties),object}}if(Object.defineProperty){var definePropertyWorksOnObject=doesDefinePropertyWork({}),definePropertyWorksOnDom=\"undefined\"==typeof document||doesDefinePropertyWork(document.createElement(\"div\"));if(!definePropertyWorksOnObject||!definePropertyWorksOnDom)var definePropertyFallback=Object.defineProperty}if(!Object.defineProperty||definePropertyFallback){var ERR_NON_OBJECT_DESCRIPTOR=\"Property description must be an object: \",ERR_NON_OBJECT_TARGET=\"Object.defineProperty called on non-object: \",ERR_ACCESSORS_NOT_SUPPORTED=\"getters & setters can not be defined on this javascript engine\";Object.defineProperty=function(object,property,descriptor){if(\"object\"!=typeof object&&\"function\"!=typeof object||null===object)throw new TypeError(ERR_NON_OBJECT_TARGET+object);if(\"object\"!=typeof descriptor&&\"function\"!=typeof descriptor||null===descriptor)throw new TypeError(ERR_NON_OBJECT_DESCRIPTOR+descriptor);if(definePropertyFallback)try{return definePropertyFallback.call(Object,object,property,descriptor)}catch(exception){}if(owns(descriptor,\"value\"))if(supportsAccessors&&(lookupGetter(object,property)||lookupSetter(object,property))){var prototype=object.__proto__;object.__proto__=prototypeOfObject,delete object[property],object[property]=descriptor.value,object.__proto__=prototype}else object[property]=descriptor.value;else{if(!supportsAccessors)throw new TypeError(ERR_ACCESSORS_NOT_SUPPORTED);owns(descriptor,\"get\")&&defineGetter(object,property,descriptor.get),owns(descriptor,\"set\")&&defineSetter(object,property,descriptor.set)}return object}}Object.defineProperties||(Object.defineProperties=function(object,properties){for(var property in properties)owns(properties,property)&&Object.defineProperty(object,property,properties[property]);return object}),Object.seal||(Object.seal=function(object){return object}),Object.freeze||(Object.freeze=function(object){return object});try{Object.freeze(function(){})}catch(exception){Object.freeze=function(freezeObject){return function(object){return\"function\"==typeof object?object:freezeObject(object)}}(Object.freeze)}if(Object.preventExtensions||(Object.preventExtensions=function(object){return object}),Object.isSealed||(Object.isSealed=function(){return!1}),Object.isFrozen||(Object.isFrozen=function(){return!1}),Object.isExtensible||(Object.isExtensible=function(object){if(Object(object)===object)throw new TypeError;for(var name=\"\";owns(object,name);)name+=\"?\";object[name]=!0;var returnValue=owns(object,name);return delete object[name],returnValue}),!Object.keys){var hasDontEnumBug=!0,dontEnums=[\"toString\",\"toLocaleString\",\"valueOf\",\"hasOwnProperty\",\"isPrototypeOf\",\"propertyIsEnumerable\",\"constructor\"],dontEnumsLength=dontEnums.length;for(var key in{toString:null})hasDontEnumBug=!1;Object.keys=function(object){if(\"object\"!=typeof object&&\"function\"!=typeof object||null===object)throw new TypeError(\"Object.keys called on a non-object\");var keys=[];for(var name in object)owns(object,name)&&keys.push(name);if(hasDontEnumBug)for(var i=0,ii=dontEnumsLength;ii>i;i++){var dontEnum=dontEnums[i];owns(object,dontEnum)&&keys.push(dontEnum)}return keys}}Date.now||(Date.now=function(){return(new Date).getTime()});var ws=\"\t\\n\u000b\\f\\r   ᠎             　\\u2028\\u2029﻿\";if(!String.prototype.trim||ws.trim()){ws=\"[\"+ws+\"]\";var trimBeginRegexp=RegExp(\"^\"+ws+ws+\"*\"),trimEndRegexp=RegExp(ws+ws+\"*$\");String.prototype.trim=function(){return(this+\"\").replace(trimBeginRegexp,\"\").replace(trimEndRegexp,\"\")}}var toObject=function(o){if(null==o)throw new TypeError(\"can't convert \"+o+\" to object\");return Object(o)}});";

/***/ }),
/* 88 */,
/* 89 */
/***/ (function(module, exports) {

/*
 * Natural Sort algorithm for Javascript - Version 0.7 - Released under MIT license
 * Author: Jim Palmer (based on chunking idea from Dave Koelle)
 */
/*jshint unused:false */
module.exports = function naturalSort (a, b) {
	"use strict";
	var re = /(^([+\-]?(?:0|[1-9]\d*)(?:\.\d*)?(?:[eE][+\-]?\d+)?)?$|^0x[0-9a-f]+$|\d+)/gi,
		sre = /(^[ ]*|[ ]*$)/g,
		dre = /(^([\w ]+,?[\w ]+)?[\w ]+,?[\w ]+\d+:\d+(:\d+)?[\w ]?|^\d{1,4}[\/\-]\d{1,4}[\/\-]\d{1,4}|^\w+, \w+ \d+, \d{4})/,
		hre = /^0x[0-9a-f]+$/i,
		ore = /^0/,
		i = function(s) { return naturalSort.insensitive && ('' + s).toLowerCase() || '' + s; },
		// convert all to strings strip whitespace
		x = i(a).replace(sre, '') || '',
		y = i(b).replace(sre, '') || '',
		// chunk/tokenize
		xN = x.replace(re, '\0$1\0').replace(/\0$/,'').replace(/^\0/,'').split('\0'),
		yN = y.replace(re, '\0$1\0').replace(/\0$/,'').replace(/^\0/,'').split('\0'),
		// numeric, hex or date detection
		xD = parseInt(x.match(hre), 16) || (xN.length !== 1 && x.match(dre) && Date.parse(x)),
		yD = parseInt(y.match(hre), 16) || xD && y.match(dre) && Date.parse(y) || null,
		oFxNcL, oFyNcL;
	// first try and sort Hex codes or Dates
	if (yD) {
		if ( xD < yD ) { return -1; }
		else if ( xD > yD ) { return 1; }
	}
	// natural sorting through split numeric strings and default strings
	for(var cLoc=0, numS=Math.max(xN.length, yN.length); cLoc < numS; cLoc++) {
		// find floats not starting with '0', string or 0 if not defined (Clint Priest)
		oFxNcL = !(xN[cLoc] || '').match(ore) && parseFloat(xN[cLoc]) || xN[cLoc] || 0;
		oFyNcL = !(yN[cLoc] || '').match(ore) && parseFloat(yN[cLoc]) || yN[cLoc] || 0;
		// handle numeric vs string comparison - number < string - (Kyle Adams)
		if (isNaN(oFxNcL) !== isNaN(oFyNcL)) { return (isNaN(oFxNcL)) ? 1 : -1; }
		// rely on string comparison if different types - i.e. '02' < 2 != '02' < '2'
		else if (typeof oFxNcL !== typeof oFyNcL) {
			oFxNcL += '';
			oFyNcL += '';
		}
		if (oFxNcL < oFyNcL) { return -1; }
		if (oFxNcL > oFyNcL) { return 1; }
	}
	return 0;
};


/***/ }),
/* 90 */,
/* 91 */,
/* 92 */,
/* 93 */,
/* 94 */,
/* 95 */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(98);


/***/ }),
/* 96 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


/**
 * The highlighter can highlight/unhighlight a node, and
 * animate the visibility of a context menu.
 * @constructor Highlighter
 */
function Highlighter () {
  this.locked = false;
}

/**
 * Hightlight given node and its childs
 * @param {Node} node
 */
Highlighter.prototype.highlight = function (node) {
  if (this.locked) {
    return;
  }

  if (this.node != node) {
    // unhighlight current node
    if (this.node) {
      this.node.setHighlight(false);
    }

    // highlight new node
    this.node = node;
    this.node.setHighlight(true);
  }

  // cancel any current timeout
  this._cancelUnhighlight();
};

/**
 * Unhighlight currently highlighted node.
 * Will be done after a delay
 */
Highlighter.prototype.unhighlight = function () {
  if (this.locked) {
    return;
  }

  var me = this;
  if (this.node) {
    this._cancelUnhighlight();

    // do the unhighlighting after a small delay, to prevent re-highlighting
    // the same node when moving from the drag-icon to the contextmenu-icon
    // or vice versa.
    this.unhighlightTimer = setTimeout(function () {
      me.node.setHighlight(false);
      me.node = undefined;
      me.unhighlightTimer = undefined;
    }, 0);
  }
};

/**
 * Cancel an unhighlight action (if before the timeout of the unhighlight action)
 * @private
 */
Highlighter.prototype._cancelUnhighlight = function () {
  if (this.unhighlightTimer) {
    clearTimeout(this.unhighlightTimer);
    this.unhighlightTimer = undefined;
  }
};

/**
 * Lock highlighting or unhighlighting nodes.
 * methods highlight and unhighlight do not work while locked.
 */
Highlighter.prototype.lock = function () {
  this.locked = true;
};

/**
 * Unlock highlighting or unhighlighting nodes
 */
Highlighter.prototype.unlock = function () {
  this.locked = false;
};

module.exports = Highlighter;


/***/ }),
/* 97 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


var util = __webpack_require__(2);

/**
 * @constructor History
 * Store action history, enables undo and redo
 * @param {JSONEditor} editor
 */
function History (editor) {
  this.editor = editor;
  this.history = [];
  this.index = -1;

  this.clear();

  // map with all supported actions
  this.actions = {
    'editField': {
      'undo': function (params) {
        params.node.updateField(params.oldValue);
      },
      'redo': function (params) {
        params.node.updateField(params.newValue);
      }
    },
    'editValue': {
      'undo': function (params) {
        params.node.updateValue(params.oldValue);
      },
      'redo': function (params) {
        params.node.updateValue(params.newValue);
      }
    },
    'changeType': {
      'undo': function (params) {
        params.node.changeType(params.oldType);
      },
      'redo': function (params) {
        params.node.changeType(params.newType);
      }
    },

    'appendNodes': {
      'undo': function (params) {
        params.nodes.forEach(function (node) {
          params.parent.removeChild(node);
        });
      },
      'redo': function (params) {
        params.nodes.forEach(function (node) {
          params.parent.appendChild(node);
        });
      }
    },
    'insertBeforeNodes': {
      'undo': function (params) {
        params.nodes.forEach(function (node) {
          params.parent.removeChild(node);
        });
      },
      'redo': function (params) {
        params.nodes.forEach(function (node) {
          params.parent.insertBefore(node, params.beforeNode);
        });
      }
    },
    'insertAfterNodes': {
      'undo': function (params) {
        params.nodes.forEach(function (node) {
          params.parent.removeChild(node);
        });
      },
      'redo': function (params) {
        var afterNode = params.afterNode;
        params.nodes.forEach(function (node) {
          params.parent.insertAfter(params.node, afterNode);
          afterNode = node;
        });
      }
    },
    'removeNodes': {
      'undo': function (params) {
        var parent = params.parent;
        var beforeNode = parent.childs[params.index] || parent.append;
        params.nodes.forEach(function (node) {
          parent.insertBefore(node, beforeNode);
        });
      },
      'redo': function (params) {
        params.nodes.forEach(function (node) {
          params.parent.removeChild(node);
        });
      }
    },
    'duplicateNodes': {
      'undo': function (params) {
        params.nodes.forEach(function (node) {
          params.parent.removeChild(node);
        });
      },
      'redo': function (params) {
        var afterNode = params.afterNode;
        params.nodes.forEach(function (node) {
          params.parent.insertAfter(node, afterNode);
          afterNode = node;
        });
      }
    },
    'moveNodes': {
      'undo': function (params) {
        params.nodes.forEach(function (node) {
          params.oldBeforeNode.parent.moveBefore(node, params.oldBeforeNode);
        });
      },
      'redo': function (params) {
        params.nodes.forEach(function (node) {
          params.newBeforeNode.parent.moveBefore(node, params.newBeforeNode);
        });
      }
    },

    'sort': {
      'undo': function (params) {
        var node = params.node;
        node.hideChilds();
        node.sort = params.oldSort;
        node.childs = params.oldChilds;
        node.showChilds();
      },
      'redo': function (params) {
        var node = params.node;
        node.hideChilds();
        node.sort = params.newSort;
        node.childs = params.newChilds;
        node.showChilds();
      }
    }

    // TODO: restore the original caret position and selection with each undo
    // TODO: implement history for actions "expand", "collapse", "scroll", "setDocument"
  };
}

/**
 * The method onChange is executed when the History is changed, and can
 * be overloaded.
 */
History.prototype.onChange = function () {};

/**
 * Add a new action to the history
 * @param {String} action  The executed action. Available actions: "editField",
 *                         "editValue", "changeType", "appendNode",
 *                         "removeNode", "duplicateNode", "moveNode"
 * @param {Object} params  Object containing parameters describing the change.
 *                         The parameters in params depend on the action (for
 *                         example for "editValue" the Node, old value, and new
 *                         value are provided). params contains all information
 *                         needed to undo or redo the action.
 */
History.prototype.add = function (action, params) {
  this.index++;
  this.history[this.index] = {
    'action': action,
    'params': params,
    'timestamp': new Date()
  };

  // remove redo actions which are invalid now
  if (this.index < this.history.length - 1) {
    this.history.splice(this.index + 1, this.history.length - this.index - 1);
  }

  // fire onchange event
  this.onChange();
};

/**
 * Clear history
 */
History.prototype.clear = function () {
  this.history = [];
  this.index = -1;

  // fire onchange event
  this.onChange();
};

/**
 * Check if there is an action available for undo
 * @return {Boolean} canUndo
 */
History.prototype.canUndo = function () {
  return (this.index >= 0);
};

/**
 * Check if there is an action available for redo
 * @return {Boolean} canRedo
 */
History.prototype.canRedo = function () {
  return (this.index < this.history.length - 1);
};

/**
 * Undo the last action
 */
History.prototype.undo = function () {
  if (this.canUndo()) {
    var obj = this.history[this.index];
    if (obj) {
      var action = this.actions[obj.action];
      if (action && action.undo) {
        action.undo(obj.params);
        if (obj.params.oldSelection) {
          this.editor.setSelection(obj.params.oldSelection);
        }
      }
      else {
        console.error(new Error('unknown action "' + obj.action + '"'));
      }
    }
    this.index--;

    // fire onchange event
    this.onChange();
  }
};

/**
 * Redo the last action
 */
History.prototype.redo = function () {
  if (this.canRedo()) {
    this.index++;

    var obj = this.history[this.index];
    if (obj) {
      var action = this.actions[obj.action];
      if (action && action.redo) {
        action.redo(obj.params);
        if (obj.params.newSelection) {
          this.editor.setSelection(obj.params.newSelection);
        }
      }
      else {
        console.error(new Error('unknown action "' + obj.action + '"'));
      }
    }

    // fire onchange event
    this.onChange();
  }
};

/**
 * Destroy history
 */
History.prototype.destroy = function () {
  this.editor = null;

  this.history = [];
  this.index = -1;
};

module.exports = History;


/***/ }),
/* 98 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


var Ajv;
try {
  Ajv = __webpack_require__(44);
}
catch (err) {
  // no problem... when we need Ajv we will throw a neat exception
}

var treemode = __webpack_require__(106);
var textmode = __webpack_require__(105);
var util = __webpack_require__(2);

/**
 * @constructor JSONEditor
 * @param {Element} container    Container element
 * @param {Object}  [options]    Object with options. available options:
 *                               {String} mode        Editor mode. Available values:
 *                                                    'tree' (default), 'view',
 *                                                    'form', 'text', and 'code'.
 *                               {function} onChange  Callback method, triggered
 *                                                    on change of contents
 *                               {function} onError   Callback method, triggered
 *                                                    when an error occurs
 *                               {Boolean} search     Enable search box.
 *                                                    True by default
 *                                                    Only applicable for modes
 *                                                    'tree', 'view', and 'form'
 *                               {Boolean} history    Enable history (undo/redo).
 *                                                    True by default
 *                                                    Only applicable for modes
 *                                                    'tree', 'view', and 'form'
 *                               {String} name        Field name for the root node.
 *                                                    Only applicable for modes
 *                                                    'tree', 'view', and 'form'
 *                               {Number} indentation     Number of indentation
 *                                                        spaces. 4 by default.
 *                                                        Only applicable for
 *                                                        modes 'text' and 'code'
 *                               {boolean} escapeUnicode  If true, unicode
 *                                                        characters are escaped.
 *                                                        false by default.
 *                               {boolean} sortObjectKeys If true, object keys are
 *                                                        sorted before display.
 *                                                        false by default.
 * @param {Object | undefined} json JSON object
 */
function JSONEditor (container, options, json) {
  if (!(this instanceof JSONEditor)) {
    throw new Error('JSONEditor constructor called without "new".');
  }

  // check for unsupported browser (IE8 and older)
  var ieVersion = util.getInternetExplorerVersion();
  if (ieVersion != -1 && ieVersion < 9) {
    throw new Error('Unsupported browser, IE9 or newer required. ' +
        'Please install the newest version of your browser.');
  }

  if (options) {
    // check for deprecated options
    if (options.error) {
      console.warn('Option "error" has been renamed to "onError"');
      options.onError = options.error;
      delete options.error;
    }
    if (options.change) {
      console.warn('Option "change" has been renamed to "onChange"');
      options.onChange = options.change;
      delete options.change;
    }
    if (options.editable) {
      console.warn('Option "editable" has been renamed to "onEditable"');
      options.onEditable = options.editable;
      delete options.editable;
    }

    // validate options
    if (options) {
      var VALID_OPTIONS = [
        'ace', 'theme',
        'ajv', 'schema',
        'onChange', 'onEditable', 'onError', 'onModeChange',
        'escapeUnicode', 'history', 'search', 'mode', 'modes', 'name', 'indentation', 'sortObjectKeys'
      ];

      Object.keys(options).forEach(function (option) {
        if (VALID_OPTIONS.indexOf(option) === -1) {
          console.warn('Unknown option "' + option + '". This option will be ignored');
        }
      });
    }
  }

  if (arguments.length) {
    this._create(container, options, json);
  }
}

/**
 * Configuration for all registered modes. Example:
 * {
 *     tree: {
 *         mixin: TreeEditor,
 *         data: 'json'
 *     },
 *     text: {
 *         mixin: TextEditor,
 *         data: 'text'
 *     }
 * }
 *
 * @type { Object.<String, {mixin: Object, data: String} > }
 */
JSONEditor.modes = {};

// debounce interval for JSON schema vaidation in milliseconds
JSONEditor.prototype.DEBOUNCE_INTERVAL = 150;

/**
 * Create the JSONEditor
 * @param {Element} container    Container element
 * @param {Object}  [options]    See description in constructor
 * @param {Object | undefined} json JSON object
 * @private
 */
JSONEditor.prototype._create = function (container, options, json) {
  this.container = container;
  this.options = options || {};
  this.json = json || {};

  var mode = this.options.mode || 'tree';
  this.setMode(mode);
};

/**
 * Destroy the editor. Clean up DOM, event listeners, and web workers.
 */
JSONEditor.prototype.destroy = function () {};

/**
 * Set JSON object in editor
 * @param {Object | undefined} json      JSON data
 */
JSONEditor.prototype.set = function (json) {
  this.json = json;
};

/**
 * Get JSON from the editor
 * @returns {Object} json
 */
JSONEditor.prototype.get = function () {
  return this.json;
};

/**
 * Set string containing JSON for the editor
 * @param {String | undefined} jsonText
 */
JSONEditor.prototype.setText = function (jsonText) {
  this.json = util.parse(jsonText);
};

/**
 * Get stringified JSON contents from the editor
 * @returns {String} jsonText
 */
JSONEditor.prototype.getText = function () {
  return JSON.stringify(this.json);
};

/**
 * Set a field name for the root node.
 * @param {String | undefined} name
 */
JSONEditor.prototype.setName = function (name) {
  if (!this.options) {
    this.options = {};
  }
  this.options.name = name;
};

/**
 * Get the field name for the root node.
 * @return {String | undefined} name
 */
JSONEditor.prototype.getName = function () {
  return this.options && this.options.name;
};

/**
 * Change the mode of the editor.
 * JSONEditor will be extended with all methods needed for the chosen mode.
 * @param {String} mode     Available modes: 'tree' (default), 'view', 'form',
 *                          'text', and 'code'.
 */
JSONEditor.prototype.setMode = function (mode) {
  var container = this.container;
  var options = util.extend({}, this.options);
  var oldMode = options.mode;
  var data;
  var name;

  options.mode = mode;
  var config = JSONEditor.modes[mode];
  if (config) {
    try {
      var asText = (config.data == 'text');
      name = this.getName();
      data = this[asText ? 'getText' : 'get'](); // get text or json

      this.destroy();
      util.clear(this);
      util.extend(this, config.mixin);
      this.create(container, options);

      this.setName(name);
      this[asText ? 'setText' : 'set'](data); // set text or json

      if (typeof config.load === 'function') {
        try {
          config.load.call(this);
        }
        catch (err) {
          console.error(err);
        }
      }

      if (typeof options.onModeChange === 'function' && mode !== oldMode) {
        try {
          options.onModeChange(mode, oldMode);
        }
        catch (err) {
          console.error(err);
        }
      }
    }
    catch (err) {
      this._onError(err);
    }
  }
  else {
    throw new Error('Unknown mode "' + options.mode + '"');
  }
};

/**
 * Get the current mode
 * @return {string}
 */
JSONEditor.prototype.getMode = function () {
  return this.options.mode;
};

/**
 * Throw an error. If an error callback is configured in options.error, this
 * callback will be invoked. Else, a regular error is thrown.
 * @param {Error} err
 * @private
 */
JSONEditor.prototype._onError = function(err) {
  if (this.options && typeof this.options.onError === 'function') {
    this.options.onError(err);
  }
  else {
    throw err;
  }
};

/**
 * Set a JSON schema for validation of the JSON object.
 * To remove the schema, call JSONEditor.setSchema(null)
 * @param {Object | null} schema
 */
JSONEditor.prototype.setSchema = function (schema) {
  // compile a JSON schema validator if a JSON schema is provided
  if (schema) {
    var ajv;
    try {
      // grab ajv from options if provided, else create a new instance
      ajv = this.options.ajv || Ajv({ allErrors: true, verbose: true });

    }
    catch (err) {
      console.warn('Failed to create an instance of Ajv, JSON Schema validation is not available. Please use a JSONEditor bundle including Ajv, or pass an instance of Ajv as via the configuration option `ajv`.');
    }

    if (ajv) {
      this.validateSchema = ajv.compile(schema);

      // add schema to the options, so that when switching to an other mode,
      // the set schema is not lost
      this.options.schema = schema;

      // validate now
      this.validate();
    }

    this.refresh(); // update DOM
  }
  else {
    // remove current schema
    this.validateSchema = null;
    this.options.schema = null;
    this.validate(); // to clear current error messages
    this.refresh();  // update DOM
  }
};

/**
 * Validate current JSON object against the configured JSON schema
 * Throws an exception when no JSON schema is configured
 */
JSONEditor.prototype.validate = function () {
  // must be implemented by treemode and textmode
};

/**
 * Refresh the rendered contents
 */
JSONEditor.prototype.refresh = function () {
  // can be implemented by treemode and textmode
};

/**
 * Register a plugin with one ore multiple modes for the JSON Editor.
 *
 * A mode is described as an object with properties:
 *
 * - `mode: String`           The name of the mode.
 * - `mixin: Object`          An object containing the mixin functions which
 *                            will be added to the JSONEditor. Must contain functions
 *                            create, get, getText, set, and setText. May have
 *                            additional functions.
 *                            When the JSONEditor switches to a mixin, all mixin
 *                            functions are added to the JSONEditor, and then
 *                            the function `create(container, options)` is executed.
 * - `data: 'text' | 'json'`  The type of data that will be used to load the mixin.
 * - `[load: function]`       An optional function called after the mixin
 *                            has been loaded.
 *
 * @param {Object | Array} mode  A mode object or an array with multiple mode objects.
 */
JSONEditor.registerMode = function (mode) {
  var i, prop;

  if (util.isArray(mode)) {
    // multiple modes
    for (i = 0; i < mode.length; i++) {
      JSONEditor.registerMode(mode[i]);
    }
  }
  else {
    // validate the new mode
    if (!('mode' in mode)) throw new Error('Property "mode" missing');
    if (!('mixin' in mode)) throw new Error('Property "mixin" missing');
    if (!('data' in mode)) throw new Error('Property "data" missing');
    var name = mode.mode;
    if (name in JSONEditor.modes) {
      throw new Error('Mode "' + name + '" already registered');
    }

    // validate the mixin
    if (typeof mode.mixin.create !== 'function') {
      throw new Error('Required function "create" missing on mixin');
    }
    var reserved = ['setMode', 'registerMode', 'modes'];
    for (i = 0; i < reserved.length; i++) {
      prop = reserved[i];
      if (prop in mode.mixin) {
        throw new Error('Reserved property "' + prop + '" not allowed in mixin');
      }
    }

    JSONEditor.modes[name] = mode;
  }
};

// register tree and text modes
JSONEditor.registerMode(treemode);
JSONEditor.registerMode(textmode);

module.exports = JSONEditor;


/***/ }),
/* 99 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


var naturalSort = __webpack_require__(89);
var ContextMenu = __webpack_require__(4);
var appendNodeFactory = __webpack_require__(103);
var util = __webpack_require__(2);

/**
 * @constructor Node
 * Create a new Node
 * @param {./treemode} editor
 * @param {Object} [params] Can contain parameters:
 *                          {string}  field
 *                          {boolean} fieldEditable
 *                          {*}       value
 *                          {String}  type  Can have values 'auto', 'array',
 *                                          'object', or 'string'.
 */
function Node (editor, params) {
  /** @type {./treemode} */
  this.editor = editor;
  this.dom = {};
  this.expanded = false;

  if(params && (params instanceof Object)) {
    this.setField(params.field, params.fieldEditable);
    this.setValue(params.value, params.type);
  }
  else {
    this.setField('');
    this.setValue(null);
  }

  this._debouncedOnChangeValue = util.debounce(this._onChangeValue.bind(this), Node.prototype.DEBOUNCE_INTERVAL);
  this._debouncedOnChangeField = util.debounce(this._onChangeField.bind(this), Node.prototype.DEBOUNCE_INTERVAL);
}

// debounce interval for keyboard input in milliseconds
Node.prototype.DEBOUNCE_INTERVAL = 150;

/**
 * Determine whether the field and/or value of this node are editable
 * @private
 */
Node.prototype._updateEditability = function () {
  this.editable = {
    field: true,
    value: true
  };

  if (this.editor) {
    this.editable.field = this.editor.options.mode === 'tree';
    this.editable.value = this.editor.options.mode !== 'view';

    if ((this.editor.options.mode === 'tree' || this.editor.options.mode === 'form') &&
        (typeof this.editor.options.onEditable === 'function')) {
      var editable = this.editor.options.onEditable({
        field: this.field,
        value: this.value,
        path: this.getPath()
      });

      if (typeof editable === 'boolean') {
        this.editable.field = editable;
        this.editable.value = editable;
      }
      else {
        if (typeof editable.field === 'boolean') this.editable.field = editable.field;
        if (typeof editable.value === 'boolean') this.editable.value = editable.value;
      }
    }
  }
};

/**
 * Get the path of this node
 * @return {String[]} Array containing the path to this node
 */
Node.prototype.getPath = function () {
  var node = this;
  var path = [];
  while (node) {
    var field = !node.parent
        ? undefined  // do not add an (optional) field name of the root node
        :  (node.parent.type != 'array')
            ? node.field
            : node.index;

    if (field !== undefined) {
      path.unshift(field);
    }
    node = node.parent;
  }
  return path;
};

/**
 * Find a Node from a JSON path like '.items[3].name'
 * @param {string} jsonPath
 * @return {Node | null} Returns the Node when found, returns null if not found
 */
Node.prototype.findNode = function (jsonPath) {
  var path = util.parsePath(jsonPath);
  var node = this;
  while (node && path.length > 0) {
    var prop = path.shift();
    if (typeof prop === 'number') {
      if (node.type !== 'array') {
        throw new Error('Cannot get child node at index ' + prop + ': node is no array');
      }
      node = node.childs[prop];
    }
    else { // string
      if (node.type !== 'object') {
        throw new Error('Cannot get child node ' + prop + ': node is no object');
      }
      node = node.childs.filter(function (child) {
        return child.field === prop;
      })[0];
    }
  }

  return node;
};

/**
 * Find all parents of this node. The parents are ordered from root node towards
 * the original node.
 * @return {Array.<Node>}
 */
Node.prototype.findParents = function () {
  var parents = [];
  var parent = this.parent;
  while (parent) {
    parents.unshift(parent);
    parent = parent.parent;
  }
  return parents;
};

/**
 *
 * @param {{dataPath: string, keyword: string, message: string, params: Object, schemaPath: string} | null} error
 * @param {Node} [child]  When this is the error of a parent node, pointing
 *                        to an invalid child node, the child node itself
 *                        can be provided. If provided, clicking the error
 *                        icon will set focus to the invalid child node.
 */
Node.prototype.setError = function (error, child) {
  // ensure the dom exists
  this.getDom();

  this.error = error;
  var tdError = this.dom.tdError;
  if (error) {
    if (!tdError) {
      tdError = document.createElement('td');
      this.dom.tdError = tdError;
      this.dom.tdValue.parentNode.appendChild(tdError);
    }

    var popover = document.createElement('div');
    popover.className = 'jsoneditor-popover jsoneditor-right';
    popover.appendChild(document.createTextNode(error.message));

    var button = document.createElement('button');
    button.type = 'button';
    button.className = 'jsoneditor-schema-error';
    button.appendChild(popover);

    // update the direction of the popover
    button.onmouseover = button.onfocus = function updateDirection() {
      var directions = ['right', 'above', 'below', 'left'];
      for (var i = 0; i < directions.length; i++) {
        var direction = directions[i];
        popover.className = 'jsoneditor-popover jsoneditor-' + direction;

        var contentRect = this.editor.content.getBoundingClientRect();
        var popoverRect = popover.getBoundingClientRect();
        var margin = 20; // account for a scroll bar
        var fit = util.insideRect(contentRect, popoverRect, margin);

        if (fit) {
          break;
        }
      }
    }.bind(this);

    // when clicking the error icon, expand all nodes towards the invalid
    // child node, and set focus to the child node
    if (child) {
      button.onclick = function showInvalidNode() {
        child.findParents().forEach(function (parent) {
          parent.expand(false);
        });

        child.scrollTo(function () {
          child.focus();
        });
      };
    }

    // apply the error message to the node
    while (tdError.firstChild) {
      tdError.removeChild(tdError.firstChild);
    }
    tdError.appendChild(button);
  }
  else {
    if (tdError) {
      this.dom.tdError.parentNode.removeChild(this.dom.tdError);
      delete this.dom.tdError;
    }
  }
};

/**
 * Get the index of this node: the index in the list of childs where this
 * node is part of
 * @return {number} Returns the index, or -1 if this is the root node
 */
Node.prototype.getIndex = function () {
  return this.parent ? this.parent.childs.indexOf(this) : -1;
};

/**
 * Set parent node
 * @param {Node} parent
 */
Node.prototype.setParent = function(parent) {
  this.parent = parent;
};

/**
 * Set field
 * @param {String}  field
 * @param {boolean} [fieldEditable]
 */
Node.prototype.setField = function(field, fieldEditable) {
  this.field = field;
  this.previousField = field;
  this.fieldEditable = (fieldEditable === true);
};

/**
 * Get field
 * @return {String}
 */
Node.prototype.getField = function() {
  if (this.field === undefined) {
    this._getDomField();
  }

  return this.field;
};

/**
 * Set value. Value is a JSON structure or an element String, Boolean, etc.
 * @param {*} value
 * @param {String} [type]  Specify the type of the value. Can be 'auto',
 *                         'array', 'object', or 'string'
 */
Node.prototype.setValue = function(value, type) {
  var childValue, child;

  // first clear all current childs (if any)
  var childs = this.childs;
  if (childs) {
    while (childs.length) {
      this.removeChild(childs[0]);
    }
  }

  // TODO: remove the DOM of this Node

  this.type = this._getType(value);

  // check if type corresponds with the provided type
  if (type && type != this.type) {
    if (type == 'string' && this.type == 'auto') {
      this.type = type;
    }
    else {
      throw new Error('Type mismatch: ' +
          'cannot cast value of type "' + this.type +
          ' to the specified type "' + type + '"');
    }
  }

  if (this.type == 'array') {
    // array
    this.childs = [];
    for (var i = 0, iMax = value.length; i < iMax; i++) {
      childValue = value[i];
      if (childValue !== undefined && !(childValue instanceof Function)) {
        // ignore undefined and functions
        child = new Node(this.editor, {
          value: childValue
        });
        this.appendChild(child);
      }
    }
    this.value = '';
  }
  else if (this.type == 'object') {
    // object
    this.childs = [];
    for (var childField in value) {
      if (value.hasOwnProperty(childField)) {
        childValue = value[childField];
        if (childValue !== undefined && !(childValue instanceof Function)) {
          // ignore undefined and functions
          child = new Node(this.editor, {
            field: childField,
            value: childValue
          });
          this.appendChild(child);
        }
      }
    }
    this.value = '';

    // sort object keys
    if (this.editor.options.sortObjectKeys === true) {
      this.sort('asc');
    }
  }
  else {
    // value
    this.childs = undefined;
    this.value = value;
  }

  this.previousValue = this.value;
};

/**
 * Get value. Value is a JSON structure
 * @return {*} value
 */
Node.prototype.getValue = function() {
  //var childs, i, iMax;

  if (this.type == 'array') {
    var arr = [];
    this.childs.forEach (function (child) {
      arr.push(child.getValue());
    });
    return arr;
  }
  else if (this.type == 'object') {
    var obj = {};
    this.childs.forEach (function (child) {
      obj[child.getField()] = child.getValue();
    });
    return obj;
  }
  else {
    if (this.value === undefined) {
      this._getDomValue();
    }

    return this.value;
  }
};

/**
 * Get the nesting level of this node
 * @return {Number} level
 */
Node.prototype.getLevel = function() {
  return (this.parent ? this.parent.getLevel() + 1 : 0);
};

/**
 * Get path of the root node till the current node
 * @return {Node[]} Returns an array with nodes
 */
Node.prototype.getNodePath = function() {
  var path = this.parent ? this.parent.getNodePath() : [];
  path.push(this);
  return path;
};

/**
 * Create a clone of a node
 * The complete state of a clone is copied, including whether it is expanded or
 * not. The DOM elements are not cloned.
 * @return {Node} clone
 */
Node.prototype.clone = function() {
  var clone = new Node(this.editor);
  clone.type = this.type;
  clone.field = this.field;
  clone.fieldInnerText = this.fieldInnerText;
  clone.fieldEditable = this.fieldEditable;
  clone.value = this.value;
  clone.valueInnerText = this.valueInnerText;
  clone.expanded = this.expanded;

  if (this.childs) {
    // an object or array
    var cloneChilds = [];
    this.childs.forEach(function (child) {
      var childClone = child.clone();
      childClone.setParent(clone);
      cloneChilds.push(childClone);
    });
    clone.childs = cloneChilds;
  }
  else {
    // a value
    clone.childs = undefined;
  }

  return clone;
};

/**
 * Expand this node and optionally its childs.
 * @param {boolean} [recurse] Optional recursion, true by default. When
 *                            true, all childs will be expanded recursively
 */
Node.prototype.expand = function(recurse) {
  if (!this.childs) {
    return;
  }

  // set this node expanded
  this.expanded = true;
  if (this.dom.expand) {
    this.dom.expand.className = 'jsoneditor-expanded';
  }

  this.showChilds();

  if (recurse !== false) {
    this.childs.forEach(function (child) {
      child.expand(recurse);
    });
  }
};

/**
 * Collapse this node and optionally its childs.
 * @param {boolean} [recurse] Optional recursion, true by default. When
 *                            true, all childs will be collapsed recursively
 */
Node.prototype.collapse = function(recurse) {
  if (!this.childs) {
    return;
  }

  this.hideChilds();

  // collapse childs in case of recurse
  if (recurse !== false) {
    this.childs.forEach(function (child) {
      child.collapse(recurse);
    });

  }

  // make this node collapsed
  if (this.dom.expand) {
    this.dom.expand.className = 'jsoneditor-collapsed';
  }
  this.expanded = false;
};

/**
 * Recursively show all childs when they are expanded
 */
Node.prototype.showChilds = function() {
  var childs = this.childs;
  if (!childs) {
    return;
  }
  if (!this.expanded) {
    return;
  }

  var tr = this.dom.tr;
  var table = tr ? tr.parentNode : undefined;
  if (table) {
    // show row with append button
    var append = this.getAppend();
    var nextTr = tr.nextSibling;
    if (nextTr) {
      table.insertBefore(append, nextTr);
    }
    else {
      table.appendChild(append);
    }

    // show childs
    this.childs.forEach(function (child) {
      table.insertBefore(child.getDom(), append);
      child.showChilds();
    });
  }
};

/**
 * Hide the node with all its childs
 */
Node.prototype.hide = function() {
  var tr = this.dom.tr;
  var table = tr ? tr.parentNode : undefined;
  if (table) {
    table.removeChild(tr);
  }
  this.hideChilds();
};


/**
 * Recursively hide all childs
 */
Node.prototype.hideChilds = function() {
  var childs = this.childs;
  if (!childs) {
    return;
  }
  if (!this.expanded) {
    return;
  }

  // hide append row
  var append = this.getAppend();
  if (append.parentNode) {
    append.parentNode.removeChild(append);
  }

  // hide childs
  this.childs.forEach(function (child) {
    child.hide();
  });
};


/**
 * Add a new child to the node.
 * Only applicable when Node value is of type array or object
 * @param {Node} node
 */
Node.prototype.appendChild = function(node) {
  if (this._hasChilds()) {
    // adjust the link to the parent
    node.setParent(this);
    node.fieldEditable = (this.type == 'object');
    if (this.type == 'array') {
      node.index = this.childs.length;
    }
    this.childs.push(node);

    if (this.expanded) {
      // insert into the DOM, before the appendRow
      var newTr = node.getDom();
      var appendTr = this.getAppend();
      var table = appendTr ? appendTr.parentNode : undefined;
      if (appendTr && table) {
        table.insertBefore(newTr, appendTr);
      }

      node.showChilds();
    }

    this.updateDom({'updateIndexes': true});
    node.updateDom({'recurse': true});
  }
};


/**
 * Move a node from its current parent to this node
 * Only applicable when Node value is of type array or object
 * @param {Node} node
 * @param {Node} beforeNode
 */
Node.prototype.moveBefore = function(node, beforeNode) {
  if (this._hasChilds()) {
    // create a temporary row, to prevent the scroll position from jumping
    // when removing the node
    var tbody = (this.dom.tr) ? this.dom.tr.parentNode : undefined;
    if (tbody) {
      var trTemp = document.createElement('tr');
      trTemp.style.height = tbody.clientHeight + 'px';
      tbody.appendChild(trTemp);
    }

    if (node.parent) {
      node.parent.removeChild(node);
    }

    if (beforeNode instanceof AppendNode) {
      this.appendChild(node);
    }
    else {
      this.insertBefore(node, beforeNode);
    }

    if (tbody) {
      tbody.removeChild(trTemp);
    }
  }
};

/**
 * Move a node from its current parent to this node
 * Only applicable when Node value is of type array or object.
 * If index is out of range, the node will be appended to the end
 * @param {Node} node
 * @param {Number} index
 */
Node.prototype.moveTo = function (node, index) {
  if (node.parent == this) {
    // same parent
    var currentIndex = this.childs.indexOf(node);
    if (currentIndex < index) {
      // compensate the index for removal of the node itself
      index++;
    }
  }

  var beforeNode = this.childs[index] || this.append;
  this.moveBefore(node, beforeNode);
};

/**
 * Insert a new child before a given node
 * Only applicable when Node value is of type array or object
 * @param {Node} node
 * @param {Node} beforeNode
 */
Node.prototype.insertBefore = function(node, beforeNode) {
  if (this._hasChilds()) {
    if (beforeNode == this.append) {
      // append to the child nodes

      // adjust the link to the parent
      node.setParent(this);
      node.fieldEditable = (this.type == 'object');
      this.childs.push(node);
    }
    else {
      // insert before a child node
      var index = this.childs.indexOf(beforeNode);
      if (index == -1) {
        throw new Error('Node not found');
      }

      // adjust the link to the parent
      node.setParent(this);
      node.fieldEditable = (this.type == 'object');
      this.childs.splice(index, 0, node);
    }

    if (this.expanded) {
      // insert into the DOM
      var newTr = node.getDom();
      var nextTr = beforeNode.getDom();
      var table = nextTr ? nextTr.parentNode : undefined;
      if (nextTr && table) {
        table.insertBefore(newTr, nextTr);
      }

      node.showChilds();
    }

    this.updateDom({'updateIndexes': true});
    node.updateDom({'recurse': true});
  }
};

/**
 * Insert a new child before a given node
 * Only applicable when Node value is of type array or object
 * @param {Node} node
 * @param {Node} afterNode
 */
Node.prototype.insertAfter = function(node, afterNode) {
  if (this._hasChilds()) {
    var index = this.childs.indexOf(afterNode);
    var beforeNode = this.childs[index + 1];
    if (beforeNode) {
      this.insertBefore(node, beforeNode);
    }
    else {
      this.appendChild(node);
    }
  }
};

/**
 * Search in this node
 * The node will be expanded when the text is found one of its childs, else
 * it will be collapsed. Searches are case insensitive.
 * @param {String} text
 * @return {Node[]} results  Array with nodes containing the search text
 */
Node.prototype.search = function(text) {
  var results = [];
  var index;
  var search = text ? text.toLowerCase() : undefined;

  // delete old search data
  delete this.searchField;
  delete this.searchValue;

  // search in field
  if (this.field != undefined) {
    var field = String(this.field).toLowerCase();
    index = field.indexOf(search);
    if (index != -1) {
      this.searchField = true;
      results.push({
        'node': this,
        'elem': 'field'
      });
    }

    // update dom
    this._updateDomField();
  }

  // search in value
  if (this._hasChilds()) {
    // array, object

    // search the nodes childs
    if (this.childs) {
      var childResults = [];
      this.childs.forEach(function (child) {
        childResults = childResults.concat(child.search(text));
      });
      results = results.concat(childResults);
    }

    // update dom
    if (search != undefined) {
      var recurse = false;
      if (childResults.length == 0) {
        this.collapse(recurse);
      }
      else {
        this.expand(recurse);
      }
    }
  }
  else {
    // string, auto
    if (this.value != undefined ) {
      var value = String(this.value).toLowerCase();
      index = value.indexOf(search);
      if (index != -1) {
        this.searchValue = true;
        results.push({
          'node': this,
          'elem': 'value'
        });
      }
    }

    // update dom
    this._updateDomValue();
  }

  return results;
};

/**
 * Move the scroll position such that this node is in the visible area.
 * The node will not get the focus
 * @param {function(boolean)} [callback]
 */
Node.prototype.scrollTo = function(callback) {
  if (!this.dom.tr || !this.dom.tr.parentNode) {
    // if the node is not visible, expand its parents
    var parent = this.parent;
    var recurse = false;
    while (parent) {
      parent.expand(recurse);
      parent = parent.parent;
    }
  }

  if (this.dom.tr && this.dom.tr.parentNode) {
    this.editor.scrollTo(this.dom.tr.offsetTop, callback);
  }
};


// stores the element name currently having the focus
Node.focusElement = undefined;

/**
 * Set focus to this node
 * @param {String} [elementName]  The field name of the element to get the
 *                                focus available values: 'drag', 'menu',
 *                                'expand', 'field', 'value' (default)
 */
Node.prototype.focus = function(elementName) {
  Node.focusElement = elementName;

  if (this.dom.tr && this.dom.tr.parentNode) {
    var dom = this.dom;

    switch (elementName) {
      case 'drag':
        if (dom.drag) {
          dom.drag.focus();
        }
        else {
          dom.menu.focus();
        }
        break;

      case 'menu':
        dom.menu.focus();
        break;

      case 'expand':
        if (this._hasChilds()) {
          dom.expand.focus();
        }
        else if (dom.field && this.fieldEditable) {
          dom.field.focus();
          util.selectContentEditable(dom.field);
        }
        else if (dom.value && !this._hasChilds()) {
          dom.value.focus();
          util.selectContentEditable(dom.value);
        }
        else {
          dom.menu.focus();
        }
        break;

      case 'field':
        if (dom.field && this.fieldEditable) {
          dom.field.focus();
          util.selectContentEditable(dom.field);
        }
        else if (dom.value && !this._hasChilds()) {
          dom.value.focus();
          util.selectContentEditable(dom.value);
        }
        else if (this._hasChilds()) {
          dom.expand.focus();
        }
        else {
          dom.menu.focus();
        }
        break;

      case 'value':
      default:
        if (dom.select) {
          // enum select box
          dom.select.focus();
        }
        else if (dom.value && !this._hasChilds()) {
          dom.value.focus();
          util.selectContentEditable(dom.value);
        }
        else if (dom.field && this.fieldEditable) {
          dom.field.focus();
          util.selectContentEditable(dom.field);
        }
        else if (this._hasChilds()) {
          dom.expand.focus();
        }
        else {
          dom.menu.focus();
        }
        break;
    }
  }
};

/**
 * Select all text in an editable div after a delay of 0 ms
 * @param {Element} editableDiv
 */
Node.select = function(editableDiv) {
  setTimeout(function () {
    util.selectContentEditable(editableDiv);
  }, 0);
};

/**
 * Update the values from the DOM field and value of this node
 */
Node.prototype.blur = function() {
  // retrieve the actual field and value from the DOM.
  this._getDomValue(false);
  this._getDomField(false);
};

/**
 * Check if given node is a child. The method will check recursively to find
 * this node.
 * @param {Node} node
 * @return {boolean} containsNode
 */
Node.prototype.containsNode = function(node) {
  if (this == node) {
    return true;
  }

  var childs = this.childs;
  if (childs) {
    // TODO: use the js5 Array.some() here?
    for (var i = 0, iMax = childs.length; i < iMax; i++) {
      if (childs[i].containsNode(node)) {
        return true;
      }
    }
  }

  return false;
};

/**
 * Move given node into this node
 * @param {Node} node           the childNode to be moved
 * @param {Node} beforeNode     node will be inserted before given
 *                                         node. If no beforeNode is given,
 *                                         the node is appended at the end
 * @private
 */
Node.prototype._move = function(node, beforeNode) {
  if (node == beforeNode) {
    // nothing to do...
    return;
  }

  // check if this node is not a child of the node to be moved here
  if (node.containsNode(this)) {
    throw new Error('Cannot move a field into a child of itself');
  }

  // remove the original node
  if (node.parent) {
    node.parent.removeChild(node);
  }

  // create a clone of the node
  var clone = node.clone();
  node.clearDom();

  // insert or append the node
  if (beforeNode) {
    this.insertBefore(clone, beforeNode);
  }
  else {
    this.appendChild(clone);
  }

  /* TODO: adjust the field name (to prevent equal field names)
   if (this.type == 'object') {
   }
   */
};

/**
 * Remove a child from the node.
 * Only applicable when Node value is of type array or object
 * @param {Node} node   The child node to be removed;
 * @return {Node | undefined} node  The removed node on success,
 *                                             else undefined
 */
Node.prototype.removeChild = function(node) {
  if (this.childs) {
    var index = this.childs.indexOf(node);

    if (index != -1) {
      node.hide();

      // delete old search results
      delete node.searchField;
      delete node.searchValue;

      var removedNode = this.childs.splice(index, 1)[0];
      removedNode.parent = null;

      this.updateDom({'updateIndexes': true});

      return removedNode;
    }
  }

  return undefined;
};

/**
 * Remove a child node node from this node
 * This method is equal to Node.removeChild, except that _remove fire an
 * onChange event.
 * @param {Node} node
 * @private
 */
Node.prototype._remove = function (node) {
  this.removeChild(node);
};

/**
 * Change the type of the value of this Node
 * @param {String} newType
 */
Node.prototype.changeType = function (newType) {
  var oldType = this.type;

  if (oldType == newType) {
    // type is not changed
    return;
  }

  if ((newType == 'string' || newType == 'auto') &&
      (oldType == 'string' || oldType == 'auto')) {
    // this is an easy change
    this.type = newType;
  }
  else {
    // change from array to object, or from string/auto to object/array
    var table = this.dom.tr ? this.dom.tr.parentNode : undefined;
    var lastTr;
    if (this.expanded) {
      lastTr = this.getAppend();
    }
    else {
      lastTr = this.getDom();
    }
    var nextTr = (lastTr && lastTr.parentNode) ? lastTr.nextSibling : undefined;

    // hide current field and all its childs
    this.hide();
    this.clearDom();

    // adjust the field and the value
    this.type = newType;

    // adjust childs
    if (newType == 'object') {
      if (!this.childs) {
        this.childs = [];
      }

      this.childs.forEach(function (child, index) {
        child.clearDom();
        delete child.index;
        child.fieldEditable = true;
        if (child.field == undefined) {
          child.field = '';
        }
      });

      if (oldType == 'string' || oldType == 'auto') {
        this.expanded = true;
      }
    }
    else if (newType == 'array') {
      if (!this.childs) {
        this.childs = [];
      }

      this.childs.forEach(function (child, index) {
        child.clearDom();
        child.fieldEditable = false;
        child.index = index;
      });

      if (oldType == 'string' || oldType == 'auto') {
        this.expanded = true;
      }
    }
    else {
      this.expanded = false;
    }

    // create new DOM
    if (table) {
      if (nextTr) {
        table.insertBefore(this.getDom(), nextTr);
      }
      else {
        table.appendChild(this.getDom());
      }
    }
    this.showChilds();
  }

  if (newType == 'auto' || newType == 'string') {
    // cast value to the correct type
    if (newType == 'string') {
      this.value = String(this.value);
    }
    else {
      this.value = this._stringCast(String(this.value));
    }

    this.focus();
  }

  this.updateDom({'updateIndexes': true});
};

/**
 * Retrieve value from DOM
 * @param {boolean} [silent]  If true (default), no errors will be thrown in
 *                            case of invalid data
 * @private
 */
Node.prototype._getDomValue = function(silent) {
  if (this.dom.value && this.type != 'array' && this.type != 'object') {
    this.valueInnerText = util.getInnerText(this.dom.value);
  }

  if (this.valueInnerText != undefined) {
    try {
      // retrieve the value
      var value;
      if (this.type == 'string') {
        value = this._unescapeHTML(this.valueInnerText);
      }
      else {
        var str = this._unescapeHTML(this.valueInnerText);
        value = this._stringCast(str);
      }
      if (value !== this.value) {
        this.value = value;
        this._debouncedOnChangeValue();
      }
    }
    catch (err) {
      this.value = undefined;
      // TODO: sent an action with the new, invalid value?
      if (silent !== true) {
        throw err;
      }
    }
  }
};

/**
 * Handle a changed value
 * @private
 */
Node.prototype._onChangeValue = function () {
  // get current selection, then override the range such that we can select
  // the added/removed text on undo/redo
  var oldSelection = this.editor.getSelection();
  if (oldSelection.range) {
    var undoDiff = util.textDiff(String(this.value), String(this.previousValue));
    oldSelection.range.startOffset = undoDiff.start;
    oldSelection.range.endOffset = undoDiff.end;
  }
  var newSelection = this.editor.getSelection();
  if (newSelection.range) {
    var redoDiff = util.textDiff(String(this.previousValue), String(this.value));
    newSelection.range.startOffset = redoDiff.start;
    newSelection.range.endOffset = redoDiff.end;
  }

  this.editor._onAction('editValue', {
    node: this,
    oldValue: this.previousValue,
    newValue: this.value,
    oldSelection: oldSelection,
    newSelection: newSelection
  });

  this.previousValue = this.value;
};

/**
 * Handle a changed field
 * @private
 */
Node.prototype._onChangeField = function () {
  // get current selection, then override the range such that we can select
  // the added/removed text on undo/redo
  var oldSelection = this.editor.getSelection();
  if (oldSelection.range) {
    var undoDiff = util.textDiff(this.field, this.previousField);
    oldSelection.range.startOffset = undoDiff.start;
    oldSelection.range.endOffset = undoDiff.end;
  }
  var newSelection = this.editor.getSelection();
  if (newSelection.range) {
    var redoDiff = util.textDiff(this.previousField, this.field);
    newSelection.range.startOffset = redoDiff.start;
    newSelection.range.endOffset = redoDiff.end;
  }

  this.editor._onAction('editField', {
    node: this,
    oldValue: this.previousField,
    newValue: this.field,
    oldSelection: oldSelection,
    newSelection: newSelection
  });

  this.previousField = this.field;
};

/**
 * Update dom value:
 * - the text color of the value, depending on the type of the value
 * - the height of the field, depending on the width
 * - background color in case it is empty
 * @private
 */
Node.prototype._updateDomValue = function () {
  var domValue = this.dom.value;
  if (domValue) {
    var classNames = ['jsoneditor-value'];


    // set text color depending on value type
    var value = this.value;
    var type = (this.type == 'auto') ? util.type(value) : this.type;
    var isUrl = type == 'string' && util.isUrl(value);
    classNames.push('jsoneditor-' + type);
    if (isUrl) {
      classNames.push('jsoneditor-url');
    }

    // visual styling when empty
    var isEmpty = (String(this.value) == '' && this.type != 'array' && this.type != 'object');
    if (isEmpty) {
      classNames.push('jsoneditor-empty');
    }

    // highlight when there is a search result
    if (this.searchValueActive) {
      classNames.push('jsoneditor-highlight-active');
    }
    if (this.searchValue) {
      classNames.push('jsoneditor-highlight');
    }

    domValue.className = classNames.join(' ');

    // update title
    if (type == 'array' || type == 'object') {
      var count = this.childs ? this.childs.length : 0;
      domValue.title = this.type + ' containing ' + count + ' items';
    }
    else if (isUrl && this.editable.value) {
      domValue.title = 'Ctrl+Click or Ctrl+Enter to open url in new window';
    }
    else {
      domValue.title = '';
    }

    // show checkbox when the value is a boolean
    if (type === 'boolean' && this.editable.value) {
      if (!this.dom.checkbox) {
        this.dom.checkbox = document.createElement('input');
        this.dom.checkbox.type = 'checkbox';
        this.dom.tdCheckbox = document.createElement('td');
        this.dom.tdCheckbox.className = 'jsoneditor-tree';
        this.dom.tdCheckbox.appendChild(this.dom.checkbox);

        this.dom.tdValue.parentNode.insertBefore(this.dom.tdCheckbox, this.dom.tdValue);
      }

      this.dom.checkbox.checked = this.value;
    }
    else {
      // cleanup checkbox when displayed
      if (this.dom.tdCheckbox) {
        this.dom.tdCheckbox.parentNode.removeChild(this.dom.tdCheckbox);
        delete this.dom.tdCheckbox;
        delete this.dom.checkbox;
      }
    }

    if (this.enum && this.editable.value) {
      // create select box when this node has an enum object
      if (!this.dom.select) {
        this.dom.select = document.createElement('select');
        this.id = this.field + "_" + new Date().getUTCMilliseconds();
        this.dom.select.id = this.id;
        this.dom.select.name = this.dom.select.id;

        //Create the default empty option
        this.dom.select.option = document.createElement('option');
        this.dom.select.option.value = '';
        this.dom.select.option.innerHTML = '--';
        this.dom.select.appendChild(this.dom.select.option);

        //Iterate all enum values and add them as options
        for(var i = 0; i < this.enum.length; i++) {
          this.dom.select.option = document.createElement('option');
          this.dom.select.option.value = this.enum[i];
          this.dom.select.option.innerHTML = this.enum[i];
          if(this.dom.select.option.value == this.value){
            this.dom.select.option.selected = true;
          }
          this.dom.select.appendChild(this.dom.select.option);
        }

        this.dom.tdSelect = document.createElement('td');
        this.dom.tdSelect.className = 'jsoneditor-tree';
        this.dom.tdSelect.appendChild(this.dom.select);
        this.dom.tdValue.parentNode.insertBefore(this.dom.tdSelect, this.dom.tdValue);
      }

      // If the enum is inside a composite type display
      // both the simple input and the dropdown field
      if(this.schema && (
          !this.schema.hasOwnProperty("oneOf") &&
          !this.schema.hasOwnProperty("anyOf") &&
          !this.schema.hasOwnProperty("allOf"))
      ) {
        this.valueFieldHTML = this.dom.tdValue.innerHTML;
        this.dom.tdValue.style.visibility = 'hidden';
        this.dom.tdValue.innerHTML = '';
      } else {
        delete this.valueFieldHTML;
      }
    }
    else {
      // cleanup select box when displayed
      if (this.dom.tdSelect) {
        this.dom.tdSelect.parentNode.removeChild(this.dom.tdSelect);
        delete this.dom.tdSelect;
        delete this.dom.select;
        this.dom.tdValue.innerHTML = this.valueFieldHTML;
        this.dom.tdValue.style.visibility = '';
        delete this.valueFieldHTML;
      }
    }

    // strip formatting from the contents of the editable div
    util.stripFormatting(domValue);
  }
};

/**
 * Update dom field:
 * - the text color of the field, depending on the text
 * - the height of the field, depending on the width
 * - background color in case it is empty
 * @private
 */
Node.prototype._updateDomField = function () {
  var domField = this.dom.field;
  if (domField) {
    // make backgound color lightgray when empty
    var isEmpty = (String(this.field) == '' && this.parent.type != 'array');
    if (isEmpty) {
      util.addClassName(domField, 'jsoneditor-empty');
    }
    else {
      util.removeClassName(domField, 'jsoneditor-empty');
    }

    // highlight when there is a search result
    if (this.searchFieldActive) {
      util.addClassName(domField, 'jsoneditor-highlight-active');
    }
    else {
      util.removeClassName(domField, 'jsoneditor-highlight-active');
    }
    if (this.searchField) {
      util.addClassName(domField, 'jsoneditor-highlight');
    }
    else {
      util.removeClassName(domField, 'jsoneditor-highlight');
    }

    // strip formatting from the contents of the editable div
    util.stripFormatting(domField);
  }
};

/**
 * Retrieve field from DOM
 * @param {boolean} [silent]  If true (default), no errors will be thrown in
 *                            case of invalid data
 * @private
 */
Node.prototype._getDomField = function(silent) {
  if (this.dom.field && this.fieldEditable) {
    this.fieldInnerText = util.getInnerText(this.dom.field);
  }

  if (this.fieldInnerText != undefined) {
    try {
      var field = this._unescapeHTML(this.fieldInnerText);

      if (field !== this.field) {
        this.field = field;
        this._debouncedOnChangeField();
      }
    }
    catch (err) {
      this.field = undefined;
      // TODO: sent an action here, with the new, invalid value?
      if (silent !== true) {
        throw err;
      }
    }
  }
};

/**
 * Validate this node and all it's childs
 * @return {Array.<{node: Node, error: {message: string}}>} Returns a list with duplicates
 */
Node.prototype.validate = function () {
  var errors = [];

  // find duplicate keys
  if (this.type === 'object') {
    var keys = {};
    var duplicateKeys = [];
    for (var i = 0; i < this.childs.length; i++) {
      var child = this.childs[i];
      if (keys.hasOwnProperty(child.field)) {
        duplicateKeys.push(child.field);
      }
      keys[child.field] = true;
    }

    if (duplicateKeys.length > 0) {
      errors = this.childs
          .filter(function (node) {
            return duplicateKeys.indexOf(node.field) !== -1;
          })
          .map(function (node) {
            return {
              node: node,
              error: {
                message: 'duplicate key "' + node.field + '"'
              }
            }
          });
    }
  }

  // recurse over the childs
  if (this.childs) {
    for (var i = 0; i < this.childs.length; i++) {
      var e = this.childs[i].validate();
      if (e.length > 0) {
        errors = errors.concat(e);
      }
    }
  }

  return errors;
};

/**
 * Clear the dom of the node
 */
Node.prototype.clearDom = function() {
  // TODO: hide the node first?
  //this.hide();
  // TODO: recursively clear dom?

  this.dom = {};
};

/**
 * Get the HTML DOM TR element of the node.
 * The dom will be generated when not yet created
 * @return {Element} tr    HTML DOM TR Element
 */
Node.prototype.getDom = function() {
  var dom = this.dom;
  if (dom.tr) {
    return dom.tr;
  }

  this._updateEditability();

  // create row
  dom.tr = document.createElement('tr');
  dom.tr.node = this;

  if (this.editor.options.mode === 'tree') { // note: we take here the global setting
    var tdDrag = document.createElement('td');
    if (this.editable.field) {
      // create draggable area
      if (this.parent) {
        var domDrag = document.createElement('button');
        domDrag.type = 'button';
        dom.drag = domDrag;
        domDrag.className = 'jsoneditor-dragarea';
        domDrag.title = 'Drag to move this field (Alt+Shift+Arrows)';
        tdDrag.appendChild(domDrag);
      }
    }
    dom.tr.appendChild(tdDrag);

    // create context menu
    var tdMenu = document.createElement('td');
    var menu = document.createElement('button');
    menu.type = 'button';
    dom.menu = menu;
    menu.className = 'jsoneditor-contextmenu';
    menu.title = 'Click to open the actions menu (Ctrl+M)';
    tdMenu.appendChild(dom.menu);
    dom.tr.appendChild(tdMenu);
  }

  // create tree and field
  var tdField = document.createElement('td');
  dom.tr.appendChild(tdField);
  dom.tree = this._createDomTree();
  tdField.appendChild(dom.tree);

  this.updateDom({'updateIndexes': true});

  return dom.tr;
};

/**
 * DragStart event, fired on mousedown on the dragarea at the left side of a Node
 * @param {Node[] | Node} nodes
 * @param {Event} event
 */
Node.onDragStart = function (nodes, event) {
  if (!Array.isArray(nodes)) {
    return Node.onDragStart([nodes], event);
  }
  if (nodes.length === 0) {
    return;
  }

  var firstNode = nodes[0];
  var lastNode = nodes[nodes.length - 1];
  var draggedNode = Node.getNodeFromTarget(event.target);
  var beforeNode = lastNode._nextSibling();
  var editor = firstNode.editor;

  // in case of multiple selected nodes, offsetY prevents the selection from
  // jumping when you start dragging one of the lower down nodes in the selection
  var offsetY = util.getAbsoluteTop(draggedNode.dom.tr) - util.getAbsoluteTop(firstNode.dom.tr);

  if (!editor.mousemove) {
    editor.mousemove = util.addEventListener(window, 'mousemove', function (event) {
      Node.onDrag(nodes, event);
    });
  }

  if (!editor.mouseup) {
    editor.mouseup = util.addEventListener(window, 'mouseup',function (event ) {
      Node.onDragEnd(nodes, event);
    });
  }

  editor.highlighter.lock();
  editor.drag = {
    oldCursor: document.body.style.cursor,
    oldSelection: editor.getSelection(),
    oldBeforeNode: beforeNode,
    mouseX: event.pageX,
    offsetY: offsetY,
    level: firstNode.getLevel()
  };
  document.body.style.cursor = 'move';

  event.preventDefault();
};

/**
 * Drag event, fired when moving the mouse while dragging a Node
 * @param {Node[] | Node} nodes
 * @param {Event} event
 */
Node.onDrag = function (nodes, event) {
  if (!Array.isArray(nodes)) {
    return Node.onDrag([nodes], event);
  }
  if (nodes.length === 0) {
    return;
  }

  // TODO: this method has grown too large. Split it in a number of methods
  var editor = nodes[0].editor;
  var mouseY = event.pageY - editor.drag.offsetY;
  var mouseX = event.pageX;
  var trThis, trPrev, trNext, trFirst, trLast, trRoot;
  var nodePrev, nodeNext;
  var topThis, topPrev, topFirst, heightThis, bottomNext, heightNext;
  var moved = false;

  // TODO: add an ESC option, which resets to the original position

  // move up/down
  var firstNode = nodes[0];
  trThis = firstNode.dom.tr;
  topThis = util.getAbsoluteTop(trThis);
  heightThis = trThis.offsetHeight;
  if (mouseY < topThis) {
    // move up
    trPrev = trThis;
    do {
      trPrev = trPrev.previousSibling;
      nodePrev = Node.getNodeFromTarget(trPrev);
      topPrev = trPrev ? util.getAbsoluteTop(trPrev) : 0;
    }
    while (trPrev && mouseY < topPrev);

    if (nodePrev && !nodePrev.parent) {
      nodePrev = undefined;
    }

    if (!nodePrev) {
      // move to the first node
      trRoot = trThis.parentNode.firstChild;
      trPrev = trRoot ? trRoot.nextSibling : undefined;
      nodePrev = Node.getNodeFromTarget(trPrev);
      if (nodePrev == firstNode) {
        nodePrev = undefined;
      }
    }

    if (nodePrev) {
      // check if mouseY is really inside the found node
      trPrev = nodePrev.dom.tr;
      topPrev = trPrev ? util.getAbsoluteTop(trPrev) : 0;
      if (mouseY > topPrev + heightThis) {
        nodePrev = undefined;
      }
    }

    if (nodePrev) {
      nodes.forEach(function (node) {
        nodePrev.parent.moveBefore(node, nodePrev);
      });
      moved = true;
    }
  }
  else {
    // move down
    var lastNode = nodes[nodes.length - 1];
    trLast = (lastNode.expanded && lastNode.append) ? lastNode.append.getDom() : lastNode.dom.tr;
    trFirst = trLast ? trLast.nextSibling : undefined;
    if (trFirst) {
      topFirst = util.getAbsoluteTop(trFirst);
      trNext = trFirst;
      do {
        nodeNext = Node.getNodeFromTarget(trNext);
        if (trNext) {
          bottomNext = trNext.nextSibling ?
              util.getAbsoluteTop(trNext.nextSibling) : 0;
          heightNext = trNext ? (bottomNext - topFirst) : 0;

          if (nodeNext.parent.childs.length == nodes.length &&
              nodeNext.parent.childs[nodes.length - 1] == lastNode) {
            // We are about to remove the last child of this parent,
            // which will make the parents appendNode visible.
            topThis += 27;
            // TODO: dangerous to suppose the height of the appendNode a constant of 27 px.
          }
        }

        trNext = trNext.nextSibling;
      }
      while (trNext && mouseY > topThis + heightNext);

      if (nodeNext && nodeNext.parent) {
        // calculate the desired level
        var diffX = (mouseX - editor.drag.mouseX);
        var diffLevel = Math.round(diffX / 24 / 2);
        var level = editor.drag.level + diffLevel; // desired level
        var levelNext = nodeNext.getLevel();     // level to be

        // find the best fitting level (move upwards over the append nodes)
        trPrev = nodeNext.dom.tr.previousSibling;
        while (levelNext < level && trPrev) {
          nodePrev = Node.getNodeFromTarget(trPrev);

          var isDraggedNode = nodes.some(function (node) {
            return node === nodePrev || nodePrev._isChildOf(node);
          });

          if (isDraggedNode) {
            // neglect the dragged nodes themselves and their childs
          }
          else if (nodePrev instanceof AppendNode) {
            var childs = nodePrev.parent.childs;
            if (childs.length != nodes.length || childs[nodes.length - 1] != lastNode) {
              // non-visible append node of a list of childs
              // consisting of not only this node (else the
              // append node will change into a visible "empty"
              // text when removing this node).
              nodeNext = Node.getNodeFromTarget(trPrev);
              levelNext = nodeNext.getLevel();
            }
            else {
              break;
            }
          }
          else {
            break;
          }

          trPrev = trPrev.previousSibling;
        }

        // move the node when its position is changed
        if (trLast.nextSibling != nodeNext.dom.tr) {
          nodes.forEach(function (node) {
            nodeNext.parent.moveBefore(node, nodeNext);
          });
          moved = true;
        }
      }
    }
  }

  if (moved) {
    // update the dragging parameters when moved
    editor.drag.mouseX = mouseX;
    editor.drag.level = firstNode.getLevel();
  }

  // auto scroll when hovering around the top of the editor
  editor.startAutoScroll(mouseY);

  event.preventDefault();
};

/**
 * Drag event, fired on mouseup after having dragged a node
 * @param {Node[] | Node} nodes
 * @param {Event} event
 */
Node.onDragEnd = function (nodes, event) {
  if (!Array.isArray(nodes)) {
    return Node.onDrag([nodes], event);
  }
  if (nodes.length === 0) {
    return;
  }

  var firstNode = nodes[0];
  var editor = firstNode.editor;
  var parent = firstNode.parent;
  var firstIndex = parent.childs.indexOf(firstNode);
  var beforeNode = parent.childs[firstIndex + nodes.length] || parent.append;

  // set focus to the context menu button of the first node
  if (nodes[0]) {
    nodes[0].dom.menu.focus();
  }

  var params = {
    nodes: nodes,
    oldSelection: editor.drag.oldSelection,
    newSelection: editor.getSelection(),
    oldBeforeNode: editor.drag.oldBeforeNode,
    newBeforeNode: beforeNode
  };

  if (params.oldBeforeNode != params.newBeforeNode) {
    // only register this action if the node is actually moved to another place
    editor._onAction('moveNodes', params);
  }

  document.body.style.cursor = editor.drag.oldCursor;
  editor.highlighter.unlock();
  nodes.forEach(function (node) {
    if (event.target !== node.dom.drag && event.target !== node.dom.menu) {
      editor.highlighter.unhighlight();
    }
  });
  delete editor.drag;

  if (editor.mousemove) {
    util.removeEventListener(window, 'mousemove', editor.mousemove);
    delete editor.mousemove;
  }
  if (editor.mouseup) {
    util.removeEventListener(window, 'mouseup', editor.mouseup);
    delete editor.mouseup;
  }

  // Stop any running auto scroll
  editor.stopAutoScroll();

  event.preventDefault();
};

/**
 * Test if this node is a child of an other node
 * @param {Node} node
 * @return {boolean} isChild
 * @private
 */
Node.prototype._isChildOf = function (node) {
  var n = this.parent;
  while (n) {
    if (n == node) {
      return true;
    }
    n = n.parent;
  }

  return false;
};

/**
 * Create an editable field
 * @return {Element} domField
 * @private
 */
Node.prototype._createDomField = function () {
  return document.createElement('div');
};

/**
 * Set highlighting for this node and all its childs.
 * Only applied to the currently visible (expanded childs)
 * @param {boolean} highlight
 */
Node.prototype.setHighlight = function (highlight) {
  if (this.dom.tr) {
    if (highlight) {
      util.addClassName(this.dom.tr, 'jsoneditor-highlight');
    }
    else {
      util.removeClassName(this.dom.tr, 'jsoneditor-highlight');
    }

    if (this.append) {
      this.append.setHighlight(highlight);
    }

    if (this.childs) {
      this.childs.forEach(function (child) {
        child.setHighlight(highlight);
      });
    }
  }
};

/**
 * Select or deselect a node
 * @param {boolean} selected
 * @param {boolean} [isFirst]
 */
Node.prototype.setSelected = function (selected, isFirst) {
  this.selected = selected;

  if (this.dom.tr) {
    if (selected) {
      util.addClassName(this.dom.tr, 'jsoneditor-selected');
    }
    else {
      util.removeClassName(this.dom.tr, 'jsoneditor-selected');
    }

    if (isFirst) {
      util.addClassName(this.dom.tr, 'jsoneditor-first');
    }
    else {
      util.removeClassName(this.dom.tr, 'jsoneditor-first');
    }

    if (this.append) {
      this.append.setSelected(selected);
    }

    if (this.childs) {
      this.childs.forEach(function (child) {
        child.setSelected(selected);
      });
    }
  }
};

/**
 * Update the value of the node. Only primitive types are allowed, no Object
 * or Array is allowed.
 * @param {String | Number | Boolean | null} value
 */
Node.prototype.updateValue = function (value) {
  this.value = value;
  this.updateDom();
};

/**
 * Update the field of the node.
 * @param {String} field
 */
Node.prototype.updateField = function (field) {
  this.field = field;
  this.updateDom();
};

/**
 * Update the HTML DOM, optionally recursing through the childs
 * @param {Object} [options] Available parameters:
 *                          {boolean} [recurse]         If true, the
 *                          DOM of the childs will be updated recursively.
 *                          False by default.
 *                          {boolean} [updateIndexes]   If true, the childs
 *                          indexes of the node will be updated too. False by
 *                          default.
 */
Node.prototype.updateDom = function (options) {
  // update level indentation
  var domTree = this.dom.tree;
  if (domTree) {
    domTree.style.marginLeft = this.getLevel() * 24 + 'px';
  }

  // apply field to DOM
  var domField = this.dom.field;
  if (domField) {
    if (this.fieldEditable) {
      // parent is an object
      domField.contentEditable = this.editable.field;
      domField.spellcheck = false;
      domField.className = 'jsoneditor-field';
    }
    else {
      // parent is an array this is the root node
      domField.className = 'jsoneditor-readonly';
    }

    var fieldText;
    if (this.index != undefined) {
      fieldText = this.index;
    }
    else if (this.field != undefined) {
      fieldText = this.field;
    }
    else if (this._hasChilds()) {
      fieldText = this.type;
    }
    else {
      fieldText = '';
    }
    domField.innerHTML = this._escapeHTML(fieldText);

    this._updateSchema();
  }

  // apply value to DOM
  var domValue = this.dom.value;
  if (domValue) {
    var count = this.childs ? this.childs.length : 0;
    if (this.type == 'array') {
      domValue.innerHTML = '[' + count + ']';
      util.addClassName(this.dom.tr, 'jsoneditor-expandable');
    }
    else if (this.type == 'object') {
      domValue.innerHTML = '{' + count + '}';
      util.addClassName(this.dom.tr, 'jsoneditor-expandable');
    }
    else {
      domValue.innerHTML = this._escapeHTML(this.value);
      util.removeClassName(this.dom.tr, 'jsoneditor-expandable');
    }
  }

  // update field and value
  this._updateDomField();
  this._updateDomValue();

  // update childs indexes
  if (options && options.updateIndexes === true) {
    // updateIndexes is true or undefined
    this._updateDomIndexes();
  }

  if (options && options.recurse === true) {
    // recurse is true or undefined. update childs recursively
    if (this.childs) {
      this.childs.forEach(function (child) {
        child.updateDom(options);
      });
    }
  }

  // update row with append button
  if (this.append) {
    this.append.updateDom();
  }
};

/**
 * Locate the JSON schema of the node and check for any enum type
 * @private
 */
Node.prototype._updateSchema = function () {
  //Locating the schema of the node and checking for any enum type
  if(this.editor && this.editor.options) {
    // find the part of the json schema matching this nodes path
    this.schema = Node._findSchema(this.editor.options.schema, this.getPath());
    if (this.schema) {
      this.enum = Node._findEnum(this.schema);
    }
    else {
      delete this.enum;
    }
  }
};

/**
 * find an enum definition in a JSON schema, as property `enum` or inside
 * one of the schemas composites (`oneOf`, `anyOf`, `allOf`)
 * @param  {Object} schema
 * @return {Array | null} Returns the enum when found, null otherwise.
 * @private
 */
Node._findEnum = function (schema) {
  if (schema.enum) {
    return schema.enum;
  }

  var composite = schema.oneOf || schema.anyOf || schema.allOf;
  if (composite) {
    var match = composite.filter(function (entry) {return entry.enum});
    if (match.length > 0) {
      return match[0].enum;
    }
  }

  return null
};

/**
 * Return the part of a JSON schema matching given path.
 * @param {Object} schema
 * @param {Array.<string | number>} path
 * @return {Object | null}
 * @private
 */
Node._findSchema = function (schema, path) {
  var childSchema = schema;

  for (var i = 0; i < path.length && childSchema; i++) {
    var key = path[i];
    if (typeof key === 'string' && childSchema.properties) {
      childSchema = childSchema.properties[key] || null
    }
    else if (typeof key === 'number' && childSchema.items) {
      childSchema = childSchema.items
    }
  }

  return childSchema
};

/**
 * Update the DOM of the childs of a node: update indexes and undefined field
 * names.
 * Only applicable when structure is an array or object
 * @private
 */
Node.prototype._updateDomIndexes = function () {
  var domValue = this.dom.value;
  var childs = this.childs;
  if (domValue && childs) {
    if (this.type == 'array') {
      childs.forEach(function (child, index) {
        child.index = index;
        var childField = child.dom.field;
        if (childField) {
          childField.innerHTML = index;
        }
      });
    }
    else if (this.type == 'object') {
      childs.forEach(function (child) {
        if (child.index != undefined) {
          delete child.index;

          if (child.field == undefined) {
            child.field = '';
          }
        }
      });
    }
  }
};

/**
 * Create an editable value
 * @private
 */
Node.prototype._createDomValue = function () {
  var domValue;

  if (this.type == 'array') {
    domValue = document.createElement('div');
    domValue.innerHTML = '[...]';
  }
  else if (this.type == 'object') {
    domValue = document.createElement('div');
    domValue.innerHTML = '{...}';
  }
  else {
    if (!this.editable.value && util.isUrl(this.value)) {
      // create a link in case of read-only editor and value containing an url
      domValue = document.createElement('a');
      domValue.href = this.value;
      domValue.target = '_blank';
      domValue.innerHTML = this._escapeHTML(this.value);
    }
    else {
      // create an editable or read-only div
      domValue = document.createElement('div');
      domValue.contentEditable = this.editable.value;
      domValue.spellcheck = false;
      domValue.innerHTML = this._escapeHTML(this.value);
    }
  }

  return domValue;
};

/**
 * Create an expand/collapse button
 * @return {Element} expand
 * @private
 */
Node.prototype._createDomExpandButton = function () {
  // create expand button
  var expand = document.createElement('button');
  expand.type = 'button';
  if (this._hasChilds()) {
    expand.className = this.expanded ? 'jsoneditor-expanded' : 'jsoneditor-collapsed';
    expand.title =
        'Click to expand/collapse this field (Ctrl+E). \n' +
        'Ctrl+Click to expand/collapse including all childs.';
  }
  else {
    expand.className = 'jsoneditor-invisible';
    expand.title = '';
  }

  return expand;
};


/**
 * Create a DOM tree element, containing the expand/collapse button
 * @return {Element} domTree
 * @private
 */
Node.prototype._createDomTree = function () {
  var dom = this.dom;
  var domTree = document.createElement('table');
  var tbody = document.createElement('tbody');
  domTree.style.borderCollapse = 'collapse'; // TODO: put in css
  domTree.className = 'jsoneditor-values';
  domTree.appendChild(tbody);
  var tr = document.createElement('tr');
  tbody.appendChild(tr);

  // create expand button
  var tdExpand = document.createElement('td');
  tdExpand.className = 'jsoneditor-tree';
  tr.appendChild(tdExpand);
  dom.expand = this._createDomExpandButton();
  tdExpand.appendChild(dom.expand);
  dom.tdExpand = tdExpand;

  // create the field
  var tdField = document.createElement('td');
  tdField.className = 'jsoneditor-tree';
  tr.appendChild(tdField);
  dom.field = this._createDomField();
  tdField.appendChild(dom.field);
  dom.tdField = tdField;

  // create a separator
  var tdSeparator = document.createElement('td');
  tdSeparator.className = 'jsoneditor-tree';
  tr.appendChild(tdSeparator);
  if (this.type != 'object' && this.type != 'array') {
    tdSeparator.appendChild(document.createTextNode(':'));
    tdSeparator.className = 'jsoneditor-separator';
  }
  dom.tdSeparator = tdSeparator;

  // create the value
  var tdValue = document.createElement('td');
  tdValue.className = 'jsoneditor-tree';
  tr.appendChild(tdValue);
  dom.value = this._createDomValue();
  tdValue.appendChild(dom.value);
  dom.tdValue = tdValue;

  return domTree;
};

/**
 * Handle an event. The event is caught centrally by the editor
 * @param {Event} event
 */
Node.prototype.onEvent = function (event) {
  var type = event.type,
      target = event.target || event.srcElement,
      dom = this.dom,
      node = this,
      expandable = this._hasChilds();

  // check if mouse is on menu or on dragarea.
  // If so, highlight current row and its childs
  if (target == dom.drag || target == dom.menu) {
    if (type == 'mouseover') {
      this.editor.highlighter.highlight(this);
    }
    else if (type == 'mouseout') {
      this.editor.highlighter.unhighlight();
    }
  }

  // context menu events
  if (type == 'click' && target == dom.menu) {
    var highlighter = node.editor.highlighter;
    highlighter.highlight(node);
    highlighter.lock();
    util.addClassName(dom.menu, 'jsoneditor-selected');
    this.showContextMenu(dom.menu, function () {
      util.removeClassName(dom.menu, 'jsoneditor-selected');
      highlighter.unlock();
      highlighter.unhighlight();
    });
  }

  // expand events
  if (type == 'click') {
    if (target == dom.expand ||
        ((node.editor.options.mode === 'view' || node.editor.options.mode === 'form') && target.nodeName === 'DIV')) {
      if (expandable) {
        var recurse = event.ctrlKey; // with ctrl-key, expand/collapse all
        this._onExpand(recurse);
      }
    }
  }

  // swap the value of a boolean when the checkbox displayed left is clicked
  if (type == 'change' && target == dom.checkbox) {
    this.dom.value.innerHTML = !this.value;
    this._getDomValue();
  }

  // update the value of the node based on the selected option
  if (type == 'change' && target == dom.select) {
    this.dom.value.innerHTML = dom.select.value;
    this._getDomValue();
    this._updateDomValue();
  }

  // value events
  var domValue = dom.value;
  if (target == domValue) {
    //noinspection FallthroughInSwitchStatementJS
    switch (type) {
      case 'blur':
      case 'change':
        this._getDomValue(true);
        this._updateDomValue();
        if (this.value) {
          domValue.innerHTML = this._escapeHTML(this.value);
        }
        break;

      case 'input':
        //this._debouncedGetDomValue(true); // TODO
        this._getDomValue(true);
        this._updateDomValue();
        break;

      case 'keydown':
      case 'mousedown':
          // TODO: cleanup
        this.editor.selection = this.editor.getSelection();
        break;

      case 'click':
        if (event.ctrlKey || !this.editable.value) {
          if (util.isUrl(this.value)) {
            window.open(this.value, '_blank');
          }
        }
        break;

      case 'keyup':
        //this._debouncedGetDomValue(true); // TODO
        this._getDomValue(true);
        this._updateDomValue();
        break;

      case 'cut':
      case 'paste':
        setTimeout(function () {
          node._getDomValue(true);
          node._updateDomValue();
        }, 1);
        break;
    }
  }

  // field events
  var domField = dom.field;
  if (target == domField) {
    switch (type) {
      case 'blur':
      case 'change':
        this._getDomField(true);
        this._updateDomField();
        if (this.field) {
          domField.innerHTML = this._escapeHTML(this.field);
        }
        break;

      case 'input':
        this._getDomField(true);
        this._updateSchema();
        this._updateDomField();
        this._updateDomValue();
        break;

      case 'keydown':
      case 'mousedown':
        this.editor.selection = this.editor.getSelection();
        break;

      case 'keyup':
        this._getDomField(true);
        this._updateDomField();
        break;

      case 'cut':
      case 'paste':
        setTimeout(function () {
          node._getDomField(true);
          node._updateDomField();
        }, 1);
        break;
    }
  }

  // focus
  // when clicked in whitespace left or right from the field or value, set focus
  var domTree = dom.tree;
  if (target == domTree.parentNode && type == 'click' && !event.hasMoved) {
    var left = (event.offsetX != undefined) ?
        (event.offsetX < (this.getLevel() + 1) * 24) :
        (event.pageX < util.getAbsoluteLeft(dom.tdSeparator));// for FF
    if (left || expandable) {
      // node is expandable when it is an object or array
      if (domField) {
        util.setEndOfContentEditable(domField);
        domField.focus();
      }
    }
    else {
      if (domValue && !this.enum) {
        util.setEndOfContentEditable(domValue);
        domValue.focus();
      }
    }
  }
  if (((target == dom.tdExpand && !expandable) || target == dom.tdField || target == dom.tdSeparator) &&
      (type == 'click' && !event.hasMoved)) {
    if (domField) {
      util.setEndOfContentEditable(domField);
      domField.focus();
    }
  }

  if (type == 'keydown') {
    this.onKeyDown(event);
  }
};

/**
 * Key down event handler
 * @param {Event} event
 */
Node.prototype.onKeyDown = function (event) {
  var keynum = event.which || event.keyCode;
  var target = event.target || event.srcElement;
  var ctrlKey = event.ctrlKey;
  var shiftKey = event.shiftKey;
  var altKey = event.altKey;
  var handled = false;
  var prevNode, nextNode, nextDom, nextDom2;
  var editable = this.editor.options.mode === 'tree';
  var oldSelection;
  var oldBeforeNode;
  var nodes;
  var multiselection;
  var selectedNodes = this.editor.multiselection.nodes.length > 0
      ? this.editor.multiselection.nodes
      : [this];
  var firstNode = selectedNodes[0];
  var lastNode = selectedNodes[selectedNodes.length - 1];

  // console.log(ctrlKey, keynum, event.charCode); // TODO: cleanup
  if (keynum == 13) { // Enter
    if (target == this.dom.value) {
      if (!this.editable.value || event.ctrlKey) {
        if (util.isUrl(this.value)) {
          window.open(this.value, '_blank');
          handled = true;
        }
      }
    }
    else if (target == this.dom.expand) {
      var expandable = this._hasChilds();
      if (expandable) {
        var recurse = event.ctrlKey; // with ctrl-key, expand/collapse all
        this._onExpand(recurse);
        target.focus();
        handled = true;
      }
    }
  }
  else if (keynum == 68) {  // D
    if (ctrlKey && editable) {   // Ctrl+D
      Node.onDuplicate(selectedNodes);
      handled = true;
    }
  }
  else if (keynum == 69) { // E
    if (ctrlKey) {       // Ctrl+E and Ctrl+Shift+E
      this._onExpand(shiftKey);  // recurse = shiftKey
      target.focus(); // TODO: should restore focus in case of recursing expand (which takes DOM offline)
      handled = true;
    }
  }
  else if (keynum == 77 && editable) { // M
    if (ctrlKey) { // Ctrl+M
      this.showContextMenu(target);
      handled = true;
    }
  }
  else if (keynum == 46 && editable) { // Del
    if (ctrlKey) {       // Ctrl+Del
      Node.onRemove(selectedNodes);
      handled = true;
    }
  }
  else if (keynum == 45 && editable) { // Ins
    if (ctrlKey && !shiftKey) {       // Ctrl+Ins
      this._onInsertBefore();
      handled = true;
    }
    else if (ctrlKey && shiftKey) {   // Ctrl+Shift+Ins
      this._onInsertAfter();
      handled = true;
    }
  }
  else if (keynum == 35) { // End
    if (altKey) { // Alt+End
      // find the last node
      var endNode = this._lastNode();
      if (endNode) {
        endNode.focus(Node.focusElement || this._getElementName(target));
      }
      handled = true;
    }
  }
  else if (keynum == 36) { // Home
    if (altKey) { // Alt+Home
      // find the first node
      var homeNode = this._firstNode();
      if (homeNode) {
        homeNode.focus(Node.focusElement || this._getElementName(target));
      }
      handled = true;
    }
  }
  else if (keynum == 37) {        // Arrow Left
    if (altKey && !shiftKey) {  // Alt + Arrow Left
      // move to left element
      var prevElement = this._previousElement(target);
      if (prevElement) {
        this.focus(this._getElementName(prevElement));
      }
      handled = true;
    }
    else if (altKey && shiftKey && editable) { // Alt + Shift + Arrow left
      if (lastNode.expanded) {
        var appendDom = lastNode.getAppend();
        nextDom = appendDom ? appendDom.nextSibling : undefined;
      }
      else {
        var dom = lastNode.getDom();
        nextDom = dom.nextSibling;
      }
      if (nextDom) {
        nextNode = Node.getNodeFromTarget(nextDom);
        nextDom2 = nextDom.nextSibling;
        nextNode2 = Node.getNodeFromTarget(nextDom2);
        if (nextNode && nextNode instanceof AppendNode &&
            !(lastNode.parent.childs.length == 1) &&
            nextNode2 && nextNode2.parent) {
          oldSelection = this.editor.getSelection();
          oldBeforeNode = lastNode._nextSibling();

          selectedNodes.forEach(function (node) {
            nextNode2.parent.moveBefore(node, nextNode2);
          });
          this.focus(Node.focusElement || this._getElementName(target));

          this.editor._onAction('moveNodes', {
            nodes: selectedNodes,
            oldBeforeNode: oldBeforeNode,
            newBeforeNode: nextNode2,
            oldSelection: oldSelection,
            newSelection: this.editor.getSelection()
          });
        }
      }
    }
  }
  else if (keynum == 38) {        // Arrow Up
    if (altKey && !shiftKey) {  // Alt + Arrow Up
      // find the previous node
      prevNode = this._previousNode();
      if (prevNode) {
        this.editor.deselect(true);
        prevNode.focus(Node.focusElement || this._getElementName(target));
      }
      handled = true;
    }
    else if (!altKey && ctrlKey && shiftKey && editable) { // Ctrl + Shift + Arrow Up
      // select multiple nodes
      prevNode = this._previousNode();
      if (prevNode) {
        multiselection = this.editor.multiselection;
        multiselection.start = multiselection.start || this;
        multiselection.end = prevNode;
        nodes = this.editor._findTopLevelNodes(multiselection.start, multiselection.end);

        this.editor.select(nodes);
        prevNode.focus('field'); // select field as we know this always exists
      }
      handled = true;
    }
    else if (altKey && shiftKey && editable) { // Alt + Shift + Arrow Up
      // find the previous node
      prevNode = firstNode._previousNode();
      if (prevNode && prevNode.parent) {
        oldSelection = this.editor.getSelection();
        oldBeforeNode = lastNode._nextSibling();

        selectedNodes.forEach(function (node) {
          prevNode.parent.moveBefore(node, prevNode);
        });
        this.focus(Node.focusElement || this._getElementName(target));

        this.editor._onAction('moveNodes', {
          nodes: selectedNodes,
          oldBeforeNode: oldBeforeNode,
          newBeforeNode: prevNode,
          oldSelection: oldSelection,
          newSelection: this.editor.getSelection()
        });
      }
      handled = true;
    }
  }
  else if (keynum == 39) {        // Arrow Right
    if (altKey && !shiftKey) {  // Alt + Arrow Right
      // move to right element
      var nextElement = this._nextElement(target);
      if (nextElement) {
        this.focus(this._getElementName(nextElement));
      }
      handled = true;
    }
    else if (altKey && shiftKey && editable) { // Alt + Shift + Arrow Right
      dom = firstNode.getDom();
      var prevDom = dom.previousSibling;
      if (prevDom) {
        prevNode = Node.getNodeFromTarget(prevDom);
        if (prevNode && prevNode.parent &&
            (prevNode instanceof AppendNode)
            && !prevNode.isVisible()) {
          oldSelection = this.editor.getSelection();
          oldBeforeNode = lastNode._nextSibling();

          selectedNodes.forEach(function (node) {
            prevNode.parent.moveBefore(node, prevNode);
          });
          this.focus(Node.focusElement || this._getElementName(target));

          this.editor._onAction('moveNodes', {
            nodes: selectedNodes,
            oldBeforeNode: oldBeforeNode,
            newBeforeNode: prevNode,
            oldSelection: oldSelection,
            newSelection: this.editor.getSelection()
          });
        }
      }
    }
  }
  else if (keynum == 40) {        // Arrow Down
    if (altKey && !shiftKey) {  // Alt + Arrow Down
      // find the next node
      nextNode = this._nextNode();
      if (nextNode) {
        this.editor.deselect(true);
        nextNode.focus(Node.focusElement || this._getElementName(target));
      }
      handled = true;
    }
    else if (!altKey && ctrlKey && shiftKey && editable) { // Ctrl + Shift + Arrow Down
      // select multiple nodes
      nextNode = this._nextNode();
      if (nextNode) {
        multiselection = this.editor.multiselection;
        multiselection.start = multiselection.start || this;
        multiselection.end = nextNode;
        nodes = this.editor._findTopLevelNodes(multiselection.start, multiselection.end);

        this.editor.select(nodes);
        nextNode.focus('field'); // select field as we know this always exists
      }
      handled = true;
    }
    else if (altKey && shiftKey && editable) { // Alt + Shift + Arrow Down
      // find the 2nd next node and move before that one
      if (lastNode.expanded) {
        nextNode = lastNode.append ? lastNode.append._nextNode() : undefined;
      }
      else {
        nextNode = lastNode._nextNode();
      }
      var nextNode2 = nextNode && (nextNode._nextNode() || nextNode.parent.append);
      if (nextNode2 && nextNode2.parent) {
        oldSelection = this.editor.getSelection();
        oldBeforeNode = lastNode._nextSibling();

        selectedNodes.forEach(function (node) {
          nextNode2.parent.moveBefore(node, nextNode2);
        });
        this.focus(Node.focusElement || this._getElementName(target));

        this.editor._onAction('moveNodes', {
          nodes: selectedNodes,
          oldBeforeNode: oldBeforeNode,
          newBeforeNode: nextNode2,
          oldSelection: oldSelection,
          newSelection: this.editor.getSelection()
        });
      }
      handled = true;
    }
  }

  if (handled) {
    event.preventDefault();
    event.stopPropagation();
  }
};

/**
 * Handle the expand event, when clicked on the expand button
 * @param {boolean} recurse   If true, child nodes will be expanded too
 * @private
 */
Node.prototype._onExpand = function (recurse) {
  if (recurse) {
    // Take the table offline
    var table = this.dom.tr.parentNode; // TODO: not nice to access the main table like this
    var frame = table.parentNode;
    var scrollTop = frame.scrollTop;
    frame.removeChild(table);
  }

  if (this.expanded) {
    this.collapse(recurse);
  }
  else {
    this.expand(recurse);
  }

  if (recurse) {
    // Put the table online again
    frame.appendChild(table);
    frame.scrollTop = scrollTop;
  }
};

/**
 * Remove nodes
 * @param {Node[] | Node} nodes
 */
Node.onRemove = function(nodes) {
  if (!Array.isArray(nodes)) {
    return Node.onRemove([nodes]);
  }

  if (nodes && nodes.length > 0) {
    var firstNode = nodes[0];
    var parent = firstNode.parent;
    var editor = firstNode.editor;
    var firstIndex = firstNode.getIndex();
    editor.highlighter.unhighlight();

    // adjust the focus
    var oldSelection = editor.getSelection();
    Node.blurNodes(nodes);
    var newSelection = editor.getSelection();

    // remove the nodes
    nodes.forEach(function (node) {
      node.parent._remove(node);
    });

    // store history action
    editor._onAction('removeNodes', {
      nodes: nodes.slice(0), // store a copy of the array!
      parent: parent,
      index: firstIndex,
      oldSelection: oldSelection,
      newSelection: newSelection
    });
  }
};


/**
 * Duplicate nodes
 * duplicated nodes will be added right after the original nodes
 * @param {Node[] | Node} nodes
 */
Node.onDuplicate = function(nodes) {
  if (!Array.isArray(nodes)) {
    return Node.onDuplicate([nodes]);
  }

  if (nodes && nodes.length > 0) {
    var lastNode = nodes[nodes.length - 1];
    var parent = lastNode.parent;
    var editor = lastNode.editor;

    editor.deselect(editor.multiselection.nodes);

    // duplicate the nodes
    var oldSelection = editor.getSelection();
    var afterNode = lastNode;
    var clones = nodes.map(function (node) {
      var clone = node.clone();
      parent.insertAfter(clone, afterNode);
      afterNode = clone;
      return clone;
    });

    // set selection to the duplicated nodes
    if (nodes.length === 1) {
      clones[0].focus();
    }
    else {
      editor.select(clones);
    }
    var newSelection = editor.getSelection();

    editor._onAction('duplicateNodes', {
      afterNode: lastNode,
      nodes: clones,
      parent: parent,
      oldSelection: oldSelection,
      newSelection: newSelection
    });
  }
};

/**
 * Handle insert before event
 * @param {String} [field]
 * @param {*} [value]
 * @param {String} [type]   Can be 'auto', 'array', 'object', or 'string'
 * @private
 */
Node.prototype._onInsertBefore = function (field, value, type) {
  var oldSelection = this.editor.getSelection();

  var newNode = new Node(this.editor, {
    field: (field != undefined) ? field : '',
    value: (value != undefined) ? value : '',
    type: type
  });
  newNode.expand(true);
  this.parent.insertBefore(newNode, this);
  this.editor.highlighter.unhighlight();
  newNode.focus('field');
  var newSelection = this.editor.getSelection();

  this.editor._onAction('insertBeforeNodes', {
    nodes: [newNode],
    beforeNode: this,
    parent: this.parent,
    oldSelection: oldSelection,
    newSelection: newSelection
  });
};

/**
 * Handle insert after event
 * @param {String} [field]
 * @param {*} [value]
 * @param {String} [type]   Can be 'auto', 'array', 'object', or 'string'
 * @private
 */
Node.prototype._onInsertAfter = function (field, value, type) {
  var oldSelection = this.editor.getSelection();

  var newNode = new Node(this.editor, {
    field: (field != undefined) ? field : '',
    value: (value != undefined) ? value : '',
    type: type
  });
  newNode.expand(true);
  this.parent.insertAfter(newNode, this);
  this.editor.highlighter.unhighlight();
  newNode.focus('field');
  var newSelection = this.editor.getSelection();

  this.editor._onAction('insertAfterNodes', {
    nodes: [newNode],
    afterNode: this,
    parent: this.parent,
    oldSelection: oldSelection,
    newSelection: newSelection
  });
};

/**
 * Handle append event
 * @param {String} [field]
 * @param {*} [value]
 * @param {String} [type]   Can be 'auto', 'array', 'object', or 'string'
 * @private
 */
Node.prototype._onAppend = function (field, value, type) {
  var oldSelection = this.editor.getSelection();

  var newNode = new Node(this.editor, {
    field: (field != undefined) ? field : '',
    value: (value != undefined) ? value : '',
    type: type
  });
  newNode.expand(true);
  this.parent.appendChild(newNode);
  this.editor.highlighter.unhighlight();
  newNode.focus('field');
  var newSelection = this.editor.getSelection();

  this.editor._onAction('appendNodes', {
    nodes: [newNode],
    parent: this.parent,
    oldSelection: oldSelection,
    newSelection: newSelection
  });
};

/**
 * Change the type of the node's value
 * @param {String} newType
 * @private
 */
Node.prototype._onChangeType = function (newType) {
  var oldType = this.type;
  if (newType != oldType) {
    var oldSelection = this.editor.getSelection();
    this.changeType(newType);
    var newSelection = this.editor.getSelection();

    this.editor._onAction('changeType', {
      node: this,
      oldType: oldType,
      newType: newType,
      oldSelection: oldSelection,
      newSelection: newSelection
    });
  }
};

/**
 * Sort the child's of the node. Only applicable when the node has type 'object'
 * or 'array'.
 * @param {String} direction   Sorting direction. Available values: "asc", "desc"
 * @private
 */
Node.prototype.sort = function (direction) {
  if (!this._hasChilds()) {
    return;
  }

  var order = (direction == 'desc') ? -1 : 1;
  var prop = (this.type == 'array') ? 'value': 'field';
  this.hideChilds();

  var oldChilds = this.childs;
  var oldSortOrder = this.sortOrder;

  // copy the array (the old one will be kept for an undo action
  this.childs = this.childs.concat();

  // sort the arrays
  this.childs.sort(function (a, b) {
    return order * naturalSort(a[prop], b[prop]);
  });
  this.sortOrder = (order == 1) ? 'asc' : 'desc';

  this.editor._onAction('sort', {
    node: this,
    oldChilds: oldChilds,
    oldSort: oldSortOrder,
    newChilds: this.childs,
    newSort: this.sortOrder
  });

  this.showChilds();
};

/**
 * Create a table row with an append button.
 * @return {HTMLElement | undefined} buttonAppend or undefined when inapplicable
 */
Node.prototype.getAppend = function () {
  if (!this.append) {
    this.append = new AppendNode(this.editor);
    this.append.setParent(this);
  }
  return this.append.getDom();
};

/**
 * Find the node from an event target
 * @param {Node} target
 * @return {Node | undefined} node  or undefined when not found
 * @static
 */
Node.getNodeFromTarget = function (target) {
  while (target) {
    if (target.node) {
      return target.node;
    }
    target = target.parentNode;
  }

  return undefined;
};

/**
 * Remove the focus of given nodes, and move the focus to the (a) node before,
 * (b) the node after, or (c) the parent node.
 * @param {Array.<Node> | Node} nodes
 */
Node.blurNodes = function (nodes) {
  if (!Array.isArray(nodes)) {
    Node.blurNodes([nodes]);
    return;
  }

  var firstNode = nodes[0];
  var parent = firstNode.parent;
  var firstIndex = firstNode.getIndex();

  if (parent.childs[firstIndex + nodes.length]) {
    parent.childs[firstIndex + nodes.length].focus();
  }
  else if (parent.childs[firstIndex - 1]) {
    parent.childs[firstIndex - 1].focus();
  }
  else {
    parent.focus();
  }
};

/**
 * Get the next sibling of current node
 * @return {Node} nextSibling
 * @private
 */
Node.prototype._nextSibling = function () {
  var index = this.parent.childs.indexOf(this);
  return this.parent.childs[index + 1] || this.parent.append;
};

/**
 * Get the previously rendered node
 * @return {Node | null} previousNode
 * @private
 */
Node.prototype._previousNode = function () {
  var prevNode = null;
  var dom = this.getDom();
  if (dom && dom.parentNode) {
    // find the previous field
    var prevDom = dom;
    do {
      prevDom = prevDom.previousSibling;
      prevNode = Node.getNodeFromTarget(prevDom);
    }
    while (prevDom && (prevNode instanceof AppendNode && !prevNode.isVisible()));
  }
  return prevNode;
};

/**
 * Get the next rendered node
 * @return {Node | null} nextNode
 * @private
 */
Node.prototype._nextNode = function () {
  var nextNode = null;
  var dom = this.getDom();
  if (dom && dom.parentNode) {
    // find the previous field
    var nextDom = dom;
    do {
      nextDom = nextDom.nextSibling;
      nextNode = Node.getNodeFromTarget(nextDom);
    }
    while (nextDom && (nextNode instanceof AppendNode && !nextNode.isVisible()));
  }

  return nextNode;
};

/**
 * Get the first rendered node
 * @return {Node | null} firstNode
 * @private
 */
Node.prototype._firstNode = function () {
  var firstNode = null;
  var dom = this.getDom();
  if (dom && dom.parentNode) {
    var firstDom = dom.parentNode.firstChild;
    firstNode = Node.getNodeFromTarget(firstDom);
  }

  return firstNode;
};

/**
 * Get the last rendered node
 * @return {Node | null} lastNode
 * @private
 */
Node.prototype._lastNode = function () {
  var lastNode = null;
  var dom = this.getDom();
  if (dom && dom.parentNode) {
    var lastDom = dom.parentNode.lastChild;
    lastNode =  Node.getNodeFromTarget(lastDom);
    while (lastDom && (lastNode instanceof AppendNode && !lastNode.isVisible())) {
      lastDom = lastDom.previousSibling;
      lastNode =  Node.getNodeFromTarget(lastDom);
    }
  }
  return lastNode;
};

/**
 * Get the next element which can have focus.
 * @param {Element} elem
 * @return {Element | null} nextElem
 * @private
 */
Node.prototype._previousElement = function (elem) {
  var dom = this.dom;
  // noinspection FallthroughInSwitchStatementJS
  switch (elem) {
    case dom.value:
      if (this.fieldEditable) {
        return dom.field;
      }
    // intentional fall through
    case dom.field:
      if (this._hasChilds()) {
        return dom.expand;
      }
    // intentional fall through
    case dom.expand:
      return dom.menu;
    case dom.menu:
      if (dom.drag) {
        return dom.drag;
      }
    // intentional fall through
    default:
      return null;
  }
};

/**
 * Get the next element which can have focus.
 * @param {Element} elem
 * @return {Element | null} nextElem
 * @private
 */
Node.prototype._nextElement = function (elem) {
  var dom = this.dom;
  // noinspection FallthroughInSwitchStatementJS
  switch (elem) {
    case dom.drag:
      return dom.menu;
    case dom.menu:
      if (this._hasChilds()) {
        return dom.expand;
      }
    // intentional fall through
    case dom.expand:
      if (this.fieldEditable) {
        return dom.field;
      }
    // intentional fall through
    case dom.field:
      if (!this._hasChilds()) {
        return dom.value;
      }
    default:
      return null;
  }
};

/**
 * Get the dom name of given element. returns null if not found.
 * For example when element == dom.field, "field" is returned.
 * @param {Element} element
 * @return {String | null} elementName  Available elements with name: 'drag',
 *                                      'menu', 'expand', 'field', 'value'
 * @private
 */
Node.prototype._getElementName = function (element) {
  var dom = this.dom;
  for (var name in dom) {
    if (dom.hasOwnProperty(name)) {
      if (dom[name] == element) {
        return name;
      }
    }
  }
  return null;
};

/**
 * Test if this node has childs. This is the case when the node is an object
 * or array.
 * @return {boolean} hasChilds
 * @private
 */
Node.prototype._hasChilds = function () {
  return this.type == 'array' || this.type == 'object';
};

// titles with explanation for the different types
Node.TYPE_TITLES = {
  'auto': 'Field type "auto". ' +
      'The field type is automatically determined from the value ' +
      'and can be a string, number, boolean, or null.',
  'object': 'Field type "object". ' +
      'An object contains an unordered set of key/value pairs.',
  'array': 'Field type "array". ' +
      'An array contains an ordered collection of values.',
  'string': 'Field type "string". ' +
      'Field type is not determined from the value, ' +
      'but always returned as string.'
};

/**
 * Show a contextmenu for this node
 * @param {HTMLElement} anchor   Anchor element to attach the context menu to
 *                               as sibling.
 * @param {function} [onClose]   Callback method called when the context menu
 *                               is being closed.
 */
Node.prototype.showContextMenu = function (anchor, onClose) {
  var node = this;
  var titles = Node.TYPE_TITLES;
  var items = [];

  if (this.editable.value) {
    items.push({
      text: 'Type',
      title: 'Change the type of this field',
      className: 'jsoneditor-type-' + this.type,
      submenu: [
        {
          text: 'Auto',
          className: 'jsoneditor-type-auto' +
              (this.type == 'auto' ? ' jsoneditor-selected' : ''),
          title: titles.auto,
          click: function () {
            node._onChangeType('auto');
          }
        },
        {
          text: 'Array',
          className: 'jsoneditor-type-array' +
              (this.type == 'array' ? ' jsoneditor-selected' : ''),
          title: titles.array,
          click: function () {
            node._onChangeType('array');
          }
        },
        {
          text: 'Object',
          className: 'jsoneditor-type-object' +
              (this.type == 'object' ? ' jsoneditor-selected' : ''),
          title: titles.object,
          click: function () {
            node._onChangeType('object');
          }
        },
        {
          text: 'String',
          className: 'jsoneditor-type-string' +
              (this.type == 'string' ? ' jsoneditor-selected' : ''),
          title: titles.string,
          click: function () {
            node._onChangeType('string');
          }
        }
      ]
    });
  }

  if (this._hasChilds()) {
    var direction = ((this.sortOrder == 'asc') ? 'desc': 'asc');
    items.push({
      text: 'Sort',
      title: 'Sort the childs of this ' + this.type,
      className: 'jsoneditor-sort-' + direction,
      click: function () {
        node.sort(direction);
      },
      submenu: [
        {
          text: 'Ascending',
          className: 'jsoneditor-sort-asc',
          title: 'Sort the childs of this ' + this.type + ' in ascending order',
          click: function () {
            node.sort('asc');
          }
        },
        {
          text: 'Descending',
          className: 'jsoneditor-sort-desc',
          title: 'Sort the childs of this ' + this.type +' in descending order',
          click: function () {
            node.sort('desc');
          }
        }
      ]
    });
  }

  if (this.parent && this.parent._hasChilds()) {
    if (items.length) {
      // create a separator
      items.push({
        'type': 'separator'
      });
    }

    // create append button (for last child node only)
    var childs = node.parent.childs;
    if (node == childs[childs.length - 1]) {
      items.push({
        text: 'Append',
        title: 'Append a new field with type \'auto\' after this field (Ctrl+Shift+Ins)',
        submenuTitle: 'Select the type of the field to be appended',
        className: 'jsoneditor-append',
        click: function () {
          node._onAppend('', '', 'auto');
        },
        submenu: [
          {
            text: 'Auto',
            className: 'jsoneditor-type-auto',
            title: titles.auto,
            click: function () {
              node._onAppend('', '', 'auto');
            }
          },
          {
            text: 'Array',
            className: 'jsoneditor-type-array',
            title: titles.array,
            click: function () {
              node._onAppend('', []);
            }
          },
          {
            text: 'Object',
            className: 'jsoneditor-type-object',
            title: titles.object,
            click: function () {
              node._onAppend('', {});
            }
          },
          {
            text: 'String',
            className: 'jsoneditor-type-string',
            title: titles.string,
            click: function () {
              node._onAppend('', '', 'string');
            }
          }
        ]
      });
    }

    // create insert button
    items.push({
      text: 'Insert',
      title: 'Insert a new field with type \'auto\' before this field (Ctrl+Ins)',
      submenuTitle: 'Select the type of the field to be inserted',
      className: 'jsoneditor-insert',
      click: function () {
        node._onInsertBefore('', '', 'auto');
      },
      submenu: [
        {
          text: 'Auto',
          className: 'jsoneditor-type-auto',
          title: titles.auto,
          click: function () {
            node._onInsertBefore('', '', 'auto');
          }
        },
        {
          text: 'Array',
          className: 'jsoneditor-type-array',
          title: titles.array,
          click: function () {
            node._onInsertBefore('', []);
          }
        },
        {
          text: 'Object',
          className: 'jsoneditor-type-object',
          title: titles.object,
          click: function () {
            node._onInsertBefore('', {});
          }
        },
        {
          text: 'String',
          className: 'jsoneditor-type-string',
          title: titles.string,
          click: function () {
            node._onInsertBefore('', '', 'string');
          }
        }
      ]
    });

    if (this.editable.field) {
      // create duplicate button
      items.push({
        text: 'Duplicate',
        title: 'Duplicate this field (Ctrl+D)',
        className: 'jsoneditor-duplicate',
        click: function () {
          Node.onDuplicate(node);
        }
      });

      // create remove button
      items.push({
        text: 'Remove',
        title: 'Remove this field (Ctrl+Del)',
        className: 'jsoneditor-remove',
        click: function () {
          Node.onRemove(node);
        }
      });
    }
  }

  var menu = new ContextMenu(items, {close: onClose});
  menu.show(anchor, this.editor.content);
};

/**
 * get the type of a value
 * @param {*} value
 * @return {String} type   Can be 'object', 'array', 'string', 'auto'
 * @private
 */
Node.prototype._getType = function(value) {
  if (value instanceof Array) {
    return 'array';
  }
  if (value instanceof Object) {
    return 'object';
  }
  if (typeof(value) == 'string' && typeof(this._stringCast(value)) != 'string') {
    return 'string';
  }

  return 'auto';
};

/**
 * cast contents of a string to the correct type. This can be a string,
 * a number, a boolean, etc
 * @param {String} str
 * @return {*} castedStr
 * @private
 */
Node.prototype._stringCast = function(str) {
  var lower = str.toLowerCase(),
      num = Number(str),          // will nicely fail with '123ab'
      numFloat = parseFloat(str); // will nicely fail with '  '

  if (str == '') {
    return '';
  }
  else if (lower == 'null') {
    return null;
  }
  else if (lower == 'true') {
    return true;
  }
  else if (lower == 'false') {
    return false;
  }
  else if (!isNaN(num) && !isNaN(numFloat)) {
    return num;
  }
  else {
    return str;
  }
};

/**
 * escape a text, such that it can be displayed safely in an HTML element
 * @param {String} text
 * @return {String} escapedText
 * @private
 */
Node.prototype._escapeHTML = function (text) {
  if (typeof text !== 'string') {
    return String(text);
  }
  else {
    var htmlEscaped = String(text)
        .replace(/&/g, '&amp;')    // must be replaced first!
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/  /g, ' &nbsp;') // replace double space with an nbsp and space
        .replace(/^ /, '&nbsp;')   // space at start
        .replace(/ $/, '&nbsp;');  // space at end

    var json = JSON.stringify(htmlEscaped);
    var html = json.substring(1, json.length - 1);
    if (this.editor.options.escapeUnicode === true) {
      html = util.escapeUnicodeChars(html);
    }
    return html;
  }
};

/**
 * unescape a string.
 * @param {String} escapedText
 * @return {String} text
 * @private
 */
Node.prototype._unescapeHTML = function (escapedText) {
  var json = '"' + this._escapeJSON(escapedText) + '"';
  var htmlEscaped = util.parse(json);

  return htmlEscaped
      .replace(/&lt;/g, '<')
      .replace(/&gt;/g, '>')
      .replace(/&nbsp;|\u00A0/g, ' ')
      .replace(/&amp;/g, '&');   // must be replaced last
};

/**
 * escape a text to make it a valid JSON string. The method will:
 *   - replace unescaped double quotes with '\"'
 *   - replace unescaped backslash with '\\'
 *   - replace returns with '\n'
 * @param {String} text
 * @return {String} escapedText
 * @private
 */
Node.prototype._escapeJSON = function (text) {
  // TODO: replace with some smart regex (only when a new solution is faster!)
  var escaped = '';
  var i = 0;
  while (i < text.length) {
    var c = text.charAt(i);
    if (c == '\n') {
      escaped += '\\n';
    }
    else if (c == '\\') {
      escaped += c;
      i++;

      c = text.charAt(i);
      if (c === '' || '"\\/bfnrtu'.indexOf(c) == -1) {
        escaped += '\\';  // no valid escape character
      }
      escaped += c;
    }
    else if (c == '"') {
      escaped += '\\"';
    }
    else {
      escaped += c;
    }
    i++;
  }

  return escaped;
};

// TODO: find a nicer solution to resolve this circular dependency between Node and AppendNode
var AppendNode = appendNodeFactory(Node);

module.exports = Node;


/***/ }),
/* 100 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


/**
 * @constructor SearchBox
 * Create a search box in given HTML container
 * @param {JSONEditor} editor    The JSON Editor to attach to
 * @param {Element} container               HTML container element of where to
 *                                          create the search box
 */
function SearchBox (editor, container) {
  var searchBox = this;

  this.editor = editor;
  this.timeout = undefined;
  this.delay = 200; // ms
  this.lastText = undefined;

  this.dom = {};
  this.dom.container = container;

  var table = document.createElement('table');
  this.dom.table = table;
  table.className = 'jsoneditor-search';
  container.appendChild(table);
  var tbody = document.createElement('tbody');
  this.dom.tbody = tbody;
  table.appendChild(tbody);
  var tr = document.createElement('tr');
  tbody.appendChild(tr);

  var td = document.createElement('td');
  tr.appendChild(td);
  var results = document.createElement('div');
  this.dom.results = results;
  results.className = 'jsoneditor-results';
  td.appendChild(results);

  td = document.createElement('td');
  tr.appendChild(td);
  var divInput = document.createElement('div');
  this.dom.input = divInput;
  divInput.className = 'jsoneditor-frame';
  divInput.title = 'Search fields and values';
  td.appendChild(divInput);

  // table to contain the text input and search button
  var tableInput = document.createElement('table');
  divInput.appendChild(tableInput);
  var tbodySearch = document.createElement('tbody');
  tableInput.appendChild(tbodySearch);
  tr = document.createElement('tr');
  tbodySearch.appendChild(tr);

  var refreshSearch = document.createElement('button');
  refreshSearch.type = 'button';
  refreshSearch.className = 'jsoneditor-refresh';
  td = document.createElement('td');
  td.appendChild(refreshSearch);
  tr.appendChild(td);

  var search = document.createElement('input');
  // search.type = 'button';
  this.dom.search = search;
  search.oninput = function (event) {
    searchBox._onDelayedSearch(event);
  };
  search.onchange = function (event) { // For IE 9
    searchBox._onSearch();
  };
  search.onkeydown = function (event) {
    searchBox._onKeyDown(event);
  };
  search.onkeyup = function (event) {
    searchBox._onKeyUp(event);
  };
  refreshSearch.onclick = function (event) {
    search.select();
  };

  // TODO: ESC in FF restores the last input, is a FF bug, https://bugzilla.mozilla.org/show_bug.cgi?id=598819
  td = document.createElement('td');
  td.appendChild(search);
  tr.appendChild(td);

  var searchNext = document.createElement('button');
  searchNext.type = 'button';
  searchNext.title = 'Next result (Enter)';
  searchNext.className = 'jsoneditor-next';
  searchNext.onclick = function () {
    searchBox.next();
  };
  td = document.createElement('td');
  td.appendChild(searchNext);
  tr.appendChild(td);

  var searchPrevious = document.createElement('button');
  searchPrevious.type = 'button';
  searchPrevious.title = 'Previous result (Shift+Enter)';
  searchPrevious.className = 'jsoneditor-previous';
  searchPrevious.onclick = function () {
    searchBox.previous();
  };
  td = document.createElement('td');
  td.appendChild(searchPrevious);
  tr.appendChild(td);
}

/**
 * Go to the next search result
 * @param {boolean} [focus]   If true, focus will be set to the next result
 *                            focus is false by default.
 */
SearchBox.prototype.next = function(focus) {
  if (this.results != undefined) {
    var index = (this.resultIndex != undefined) ? this.resultIndex + 1 : 0;
    if (index > this.results.length - 1) {
      index = 0;
    }
    this._setActiveResult(index, focus);
  }
};

/**
 * Go to the prevous search result
 * @param {boolean} [focus]   If true, focus will be set to the next result
 *                            focus is false by default.
 */
SearchBox.prototype.previous = function(focus) {
  if (this.results != undefined) {
    var max = this.results.length - 1;
    var index = (this.resultIndex != undefined) ? this.resultIndex - 1 : max;
    if (index < 0) {
      index = max;
    }
    this._setActiveResult(index, focus);
  }
};

/**
 * Set new value for the current active result
 * @param {Number} index
 * @param {boolean} [focus]   If true, focus will be set to the next result.
 *                            focus is false by default.
 * @private
 */
SearchBox.prototype._setActiveResult = function(index, focus) {
  // de-activate current active result
  if (this.activeResult) {
    var prevNode = this.activeResult.node;
    var prevElem = this.activeResult.elem;
    if (prevElem == 'field') {
      delete prevNode.searchFieldActive;
    }
    else {
      delete prevNode.searchValueActive;
    }
    prevNode.updateDom();
  }

  if (!this.results || !this.results[index]) {
    // out of range, set to undefined
    this.resultIndex = undefined;
    this.activeResult = undefined;
    return;
  }

  this.resultIndex = index;

  // set new node active
  var node = this.results[this.resultIndex].node;
  var elem = this.results[this.resultIndex].elem;
  if (elem == 'field') {
    node.searchFieldActive = true;
  }
  else {
    node.searchValueActive = true;
  }
  this.activeResult = this.results[this.resultIndex];
  node.updateDom();

  // TODO: not so nice that the focus is only set after the animation is finished
  node.scrollTo(function () {
    if (focus) {
      node.focus(elem);
    }
  });
};

/**
 * Cancel any running onDelayedSearch.
 * @private
 */
SearchBox.prototype._clearDelay = function() {
  if (this.timeout != undefined) {
    clearTimeout(this.timeout);
    delete this.timeout;
  }
};

/**
 * Start a timer to execute a search after a short delay.
 * Used for reducing the number of searches while typing.
 * @param {Event} event
 * @private
 */
SearchBox.prototype._onDelayedSearch = function (event) {
  // execute the search after a short delay (reduces the number of
  // search actions while typing in the search text box)
  this._clearDelay();
  var searchBox = this;
  this.timeout = setTimeout(function (event) {
    searchBox._onSearch();
  },
  this.delay);
};

/**
 * Handle onSearch event
 * @param {boolean} [forceSearch]  If true, search will be executed again even
 *                                 when the search text is not changed.
 *                                 Default is false.
 * @private
 */
SearchBox.prototype._onSearch = function (forceSearch) {
  this._clearDelay();

  var value = this.dom.search.value;
  var text = (value.length > 0) ? value : undefined;
  if (text != this.lastText || forceSearch) {
    // only search again when changed
    this.lastText = text;
    this.results = this.editor.search(text);
    this._setActiveResult(undefined);

    // display search results
    if (text != undefined) {
      var resultCount = this.results.length;
      switch (resultCount) {
        case 0: this.dom.results.innerHTML = 'no&nbsp;results'; break;
        case 1: this.dom.results.innerHTML = '1&nbsp;result'; break;
        default: this.dom.results.innerHTML = resultCount + '&nbsp;results'; break;
      }
    }
    else {
      this.dom.results.innerHTML = '';
    }
  }
};

/**
 * Handle onKeyDown event in the input box
 * @param {Event} event
 * @private
 */
SearchBox.prototype._onKeyDown = function (event) {
  var keynum = event.which;
  if (keynum == 27) { // ESC
    this.dom.search.value = '';  // clear search
    this._onSearch();
    event.preventDefault();
    event.stopPropagation();
  }
  else if (keynum == 13) { // Enter
    if (event.ctrlKey) {
      // force to search again
      this._onSearch(true);
    }
    else if (event.shiftKey) {
      // move to the previous search result
      this.previous();
    }
    else {
      // move to the next search result
      this.next();
    }
    event.preventDefault();
    event.stopPropagation();
  }
};

/**
 * Handle onKeyUp event in the input box
 * @param {Event} event
 * @private
 */
SearchBox.prototype._onKeyUp = function (event) {
  var keynum = event.keyCode;
  if (keynum != 27 && keynum != 13) { // !show and !Enter
    this._onDelayedSearch(event);   // For IE 9
  }
};

/**
 * Clear the search results
 */
SearchBox.prototype.clear = function () {
  this.dom.search.value = '';
  this._onSearch();
};

/**
 * Destroy the search box
 */
SearchBox.prototype.destroy = function () {
  this.editor = null;
  this.dom.container.removeChild(this.dom.table);
  this.dom = null;

  this.results = null;
  this.activeResult = null;

  this._clearDelay();

};

module.exports = SearchBox;


/***/ }),
/* 101 */
/***/ (function(module, exports, __webpack_require__) {

var ace
if (window.ace) {
  // use the already loaded instance of Ace
  ace = window.ace
}
else {
  try {
    // load brace
    ace = __webpack_require__(!(function webpackMissingModule() { var e = new Error("Cannot find module \"brace\""); e.code = 'MODULE_NOT_FOUND'; throw e; }()));

    // load required Ace plugins
    __webpack_require__(86);
    __webpack_require__(85);
  }
  catch (err) {
    // failed to load brace (can be minimalist bundle).
    // No worries, the editor will fall back to plain text if needed.
  }
}

module.exports = ace;


/***/ }),
/* 102 */
/***/ (function(module, exports) {

/* ***** BEGIN LICENSE BLOCK *****
 * Distributed under the BSD license:
 *
 * Copyright (c) 2010, Ajax.org B.V.
 * All rights reserved.
 * 
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions are met:
 *     * Redistributions of source code must retain the above copyright
 *       notice, this list of conditions and the following disclaimer.
 *     * Redistributions in binary form must reproduce the above copyright
 *       notice, this list of conditions and the following disclaimer in the
 *       documentation and/or other materials provided with the distribution.
 *     * Neither the name of Ajax.org B.V. nor the
 *       names of its contributors may be used to endorse or promote products
 *       derived from this software without specific prior written permission.
 * 
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND
 * ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED
 * WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
 * DISCLAIMED. IN NO EVENT SHALL AJAX.ORG B.V. BE LIABLE FOR ANY
 * DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES
 * (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
 * LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND
 * ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS
 * SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * ***** END LICENSE BLOCK ***** */

ace.define('ace/theme/jsoneditor', ['require', 'exports', 'module', 'ace/lib/dom'], function(acequire, exports, module) {

exports.isDark = false;
exports.cssClass = "ace-jsoneditor";
exports.cssText = ".ace-jsoneditor .ace_gutter {\
background: #ebebeb;\
color: #333\
}\
\
.ace-jsoneditor.ace_editor {\
font-family: droid sans mono, consolas, monospace, courier new, courier, sans-serif;\
line-height: 1.3;\
background-color: #fff;\
}\
.ace-jsoneditor .ace_print-margin {\
width: 1px;\
background: #e8e8e8\
}\
.ace-jsoneditor .ace_scroller {\
background-color: #FFFFFF\
}\
.ace-jsoneditor .ace_text-layer {\
color: gray\
}\
.ace-jsoneditor .ace_variable {\
color: #1a1a1a\
}\
.ace-jsoneditor .ace_cursor {\
border-left: 2px solid #000000\
}\
.ace-jsoneditor .ace_overwrite-cursors .ace_cursor {\
border-left: 0px;\
border-bottom: 1px solid #000000\
}\
.ace-jsoneditor .ace_marker-layer .ace_selection {\
background: lightgray\
}\
.ace-jsoneditor.ace_multiselect .ace_selection.ace_start {\
box-shadow: 0 0 3px 0px #FFFFFF;\
border-radius: 2px\
}\
.ace-jsoneditor .ace_marker-layer .ace_step {\
background: rgb(255, 255, 0)\
}\
.ace-jsoneditor .ace_marker-layer .ace_bracket {\
margin: -1px 0 0 -1px;\
border: 1px solid #BFBFBF\
}\
.ace-jsoneditor .ace_marker-layer .ace_active-line {\
background: #FFFBD1\
}\
.ace-jsoneditor .ace_gutter-active-line {\
background-color : #dcdcdc\
}\
.ace-jsoneditor .ace_marker-layer .ace_selected-word {\
border: 1px solid lightgray\
}\
.ace-jsoneditor .ace_invisible {\
color: #BFBFBF\
}\
.ace-jsoneditor .ace_keyword,\
.ace-jsoneditor .ace_meta,\
.ace-jsoneditor .ace_support.ace_constant.ace_property-value {\
color: #AF956F\
}\
.ace-jsoneditor .ace_keyword.ace_operator {\
color: #484848\
}\
.ace-jsoneditor .ace_keyword.ace_other.ace_unit {\
color: #96DC5F\
}\
.ace-jsoneditor .ace_constant.ace_language {\
color: darkorange\
}\
.ace-jsoneditor .ace_constant.ace_numeric {\
color: red\
}\
.ace-jsoneditor .ace_constant.ace_character.ace_entity {\
color: #BF78CC\
}\
.ace-jsoneditor .ace_invalid {\
color: #FFFFFF;\
background-color: #FF002A;\
}\
.ace-jsoneditor .ace_fold {\
background-color: #AF956F;\
border-color: #000000\
}\
.ace-jsoneditor .ace_storage,\
.ace-jsoneditor .ace_support.ace_class,\
.ace-jsoneditor .ace_support.ace_function,\
.ace-jsoneditor .ace_support.ace_other,\
.ace-jsoneditor .ace_support.ace_type {\
color: #C52727\
}\
.ace-jsoneditor .ace_string {\
color: green\
}\
.ace-jsoneditor .ace_comment {\
color: #BCC8BA\
}\
.ace-jsoneditor .ace_entity.ace_name.ace_tag,\
.ace-jsoneditor .ace_entity.ace_other.ace_attribute-name {\
color: #606060\
}\
.ace-jsoneditor .ace_markup.ace_underline {\
text-decoration: underline\
}\
.ace-jsoneditor .ace_indent-guide {\
background: url(\"data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAACCAYAAACZgbYnAAAAE0lEQVQImWP4////f4bLly//BwAmVgd1/w11/gAAAABJRU5ErkJggg==\") right repeat-y\
}";

var dom = acequire("../lib/dom");
dom.importCssString(exports.cssText, exports.cssClass);
});


/***/ }),
/* 103 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


var util = __webpack_require__(2);
var ContextMenu = __webpack_require__(4);

/**
 * A factory function to create an AppendNode, which depends on a Node
 * @param {Node} Node
 */
function appendNodeFactory(Node) {
  /**
   * @constructor AppendNode
   * @extends Node
   * @param {TreeEditor} editor
   * Create a new AppendNode. This is a special node which is created at the
   * end of the list with childs for an object or array
   */
  function AppendNode (editor) {
    /** @type {TreeEditor} */
    this.editor = editor;
    this.dom = {};
  }

  AppendNode.prototype = new Node();

  /**
   * Return a table row with an append button.
   * @return {Element} dom   TR element
   */
  AppendNode.prototype.getDom = function () {
    // TODO: implement a new solution for the append node
    var dom = this.dom;

    if (dom.tr) {
      return dom.tr;
    }

    this._updateEditability();

    // a row for the append button
    var trAppend = document.createElement('tr');
    trAppend.node = this;
    dom.tr = trAppend;

    // TODO: consistent naming

    if (this.editor.options.mode === 'tree') {
      // a cell for the dragarea column
      dom.tdDrag = document.createElement('td');

      // create context menu
      var tdMenu = document.createElement('td');
      dom.tdMenu = tdMenu;
      var menu = document.createElement('button');
      menu.type = 'button';
      menu.className = 'jsoneditor-contextmenu';
      menu.title = 'Click to open the actions menu (Ctrl+M)';
      dom.menu = menu;
      tdMenu.appendChild(dom.menu);
    }

    // a cell for the contents (showing text 'empty')
    var tdAppend = document.createElement('td');
    var domText = document.createElement('div');
    domText.innerHTML = '(empty)';
    domText.className = 'jsoneditor-readonly';
    tdAppend.appendChild(domText);
    dom.td = tdAppend;
    dom.text = domText;

    this.updateDom();

    return trAppend;
  };

  /**
   * Update the HTML dom of the Node
   */
  AppendNode.prototype.updateDom = function () {
    var dom = this.dom;
    var tdAppend = dom.td;
    if (tdAppend) {
      tdAppend.style.paddingLeft = (this.getLevel() * 24 + 26) + 'px';
      // TODO: not so nice hard coded offset
    }

    var domText = dom.text;
    if (domText) {
      domText.innerHTML = '(empty ' + this.parent.type + ')';
    }

    // attach or detach the contents of the append node:
    // hide when the parent has childs, show when the parent has no childs
    var trAppend = dom.tr;
    if (!this.isVisible()) {
      if (dom.tr.firstChild) {
        if (dom.tdDrag) {
          trAppend.removeChild(dom.tdDrag);
        }
        if (dom.tdMenu) {
          trAppend.removeChild(dom.tdMenu);
        }
        trAppend.removeChild(tdAppend);
      }
    }
    else {
      if (!dom.tr.firstChild) {
        if (dom.tdDrag) {
          trAppend.appendChild(dom.tdDrag);
        }
        if (dom.tdMenu) {
          trAppend.appendChild(dom.tdMenu);
        }
        trAppend.appendChild(tdAppend);
      }
    }
  };

  /**
   * Check whether the AppendNode is currently visible.
   * the AppendNode is visible when its parent has no childs (i.e. is empty).
   * @return {boolean} isVisible
   */
  AppendNode.prototype.isVisible = function () {
    return (this.parent.childs.length == 0);
  };

  /**
   * Show a contextmenu for this node
   * @param {HTMLElement} anchor   The element to attach the menu to.
   * @param {function} [onClose]   Callback method called when the context menu
   *                               is being closed.
   */
  AppendNode.prototype.showContextMenu = function (anchor, onClose) {
    var node = this;
    var titles = Node.TYPE_TITLES;
    var items = [
      // create append button
      {
        'text': 'Append',
        'title': 'Append a new field with type \'auto\' (Ctrl+Shift+Ins)',
        'submenuTitle': 'Select the type of the field to be appended',
        'className': 'jsoneditor-insert',
        'click': function () {
          node._onAppend('', '', 'auto');
        },
        'submenu': [
          {
            'text': 'Auto',
            'className': 'jsoneditor-type-auto',
            'title': titles.auto,
            'click': function () {
              node._onAppend('', '', 'auto');
            }
          },
          {
            'text': 'Array',
            'className': 'jsoneditor-type-array',
            'title': titles.array,
            'click': function () {
              node._onAppend('', []);
            }
          },
          {
            'text': 'Object',
            'className': 'jsoneditor-type-object',
            'title': titles.object,
            'click': function () {
              node._onAppend('', {});
            }
          },
          {
            'text': 'String',
            'className': 'jsoneditor-type-string',
            'title': titles.string,
            'click': function () {
              node._onAppend('', '', 'string');
            }
          }
        ]
      }
    ];

    var menu = new ContextMenu(items, {close: onClose});
    menu.show(anchor, this.editor.content);
  };

  /**
   * Handle an event. The event is catched centrally by the editor
   * @param {Event} event
   */
  AppendNode.prototype.onEvent = function (event) {
    var type = event.type;
    var target = event.target || event.srcElement;
    var dom = this.dom;

    // highlight the append nodes parent
    var menu = dom.menu;
    if (target == menu) {
      if (type == 'mouseover') {
        this.editor.highlighter.highlight(this.parent);
      }
      else if (type == 'mouseout') {
        this.editor.highlighter.unhighlight();
      }
    }

    // context menu events
    if (type == 'click' && target == dom.menu) {
      var highlighter = this.editor.highlighter;
      highlighter.highlight(this.parent);
      highlighter.lock();
      util.addClassName(dom.menu, 'jsoneditor-selected');
      this.showContextMenu(dom.menu, function () {
        util.removeClassName(dom.menu, 'jsoneditor-selected');
        highlighter.unlock();
        highlighter.unhighlight();
      });
    }

    if (type == 'keydown') {
      this.onKeyDown(event);
    }
  };

  return AppendNode;
}

module.exports = appendNodeFactory;


/***/ }),
/* 104 */
/***/ (function(module, exports, __webpack_require__) {

/* Jison generated parser */
var jsonlint = (function(){
var parser = {trace: function trace() { },
yy: {},
symbols_: {"error":2,"JSONString":3,"STRING":4,"JSONNumber":5,"NUMBER":6,"JSONNullLiteral":7,"NULL":8,"JSONBooleanLiteral":9,"TRUE":10,"FALSE":11,"JSONText":12,"JSONValue":13,"EOF":14,"JSONObject":15,"JSONArray":16,"{":17,"}":18,"JSONMemberList":19,"JSONMember":20,":":21,",":22,"[":23,"]":24,"JSONElementList":25,"$accept":0,"$end":1},
terminals_: {2:"error",4:"STRING",6:"NUMBER",8:"NULL",10:"TRUE",11:"FALSE",14:"EOF",17:"{",18:"}",21:":",22:",",23:"[",24:"]"},
productions_: [0,[3,1],[5,1],[7,1],[9,1],[9,1],[12,2],[13,1],[13,1],[13,1],[13,1],[13,1],[13,1],[15,2],[15,3],[20,3],[19,1],[19,3],[16,2],[16,3],[25,1],[25,3]],
performAction: function anonymous(yytext,yyleng,yylineno,yy,yystate,$$,_$) {

var $0 = $$.length - 1;
switch (yystate) {
case 1: // replace escaped characters with actual character
          this.$ = yytext.replace(/\\(\\|")/g, "$"+"1")
                     .replace(/\\n/g,'\n')
                     .replace(/\\r/g,'\r')
                     .replace(/\\t/g,'\t')
                     .replace(/\\v/g,'\v')
                     .replace(/\\f/g,'\f')
                     .replace(/\\b/g,'\b');
        
break;
case 2:this.$ = Number(yytext);
break;
case 3:this.$ = null;
break;
case 4:this.$ = true;
break;
case 5:this.$ = false;
break;
case 6:return this.$ = $$[$0-1];
break;
case 13:this.$ = {};
break;
case 14:this.$ = $$[$0-1];
break;
case 15:this.$ = [$$[$0-2], $$[$0]];
break;
case 16:this.$ = {}; this.$[$$[$0][0]] = $$[$0][1];
break;
case 17:this.$ = $$[$0-2]; $$[$0-2][$$[$0][0]] = $$[$0][1];
break;
case 18:this.$ = [];
break;
case 19:this.$ = $$[$0-1];
break;
case 20:this.$ = [$$[$0]];
break;
case 21:this.$ = $$[$0-2]; $$[$0-2].push($$[$0]);
break;
}
},
table: [{3:5,4:[1,12],5:6,6:[1,13],7:3,8:[1,9],9:4,10:[1,10],11:[1,11],12:1,13:2,15:7,16:8,17:[1,14],23:[1,15]},{1:[3]},{14:[1,16]},{14:[2,7],18:[2,7],22:[2,7],24:[2,7]},{14:[2,8],18:[2,8],22:[2,8],24:[2,8]},{14:[2,9],18:[2,9],22:[2,9],24:[2,9]},{14:[2,10],18:[2,10],22:[2,10],24:[2,10]},{14:[2,11],18:[2,11],22:[2,11],24:[2,11]},{14:[2,12],18:[2,12],22:[2,12],24:[2,12]},{14:[2,3],18:[2,3],22:[2,3],24:[2,3]},{14:[2,4],18:[2,4],22:[2,4],24:[2,4]},{14:[2,5],18:[2,5],22:[2,5],24:[2,5]},{14:[2,1],18:[2,1],21:[2,1],22:[2,1],24:[2,1]},{14:[2,2],18:[2,2],22:[2,2],24:[2,2]},{3:20,4:[1,12],18:[1,17],19:18,20:19},{3:5,4:[1,12],5:6,6:[1,13],7:3,8:[1,9],9:4,10:[1,10],11:[1,11],13:23,15:7,16:8,17:[1,14],23:[1,15],24:[1,21],25:22},{1:[2,6]},{14:[2,13],18:[2,13],22:[2,13],24:[2,13]},{18:[1,24],22:[1,25]},{18:[2,16],22:[2,16]},{21:[1,26]},{14:[2,18],18:[2,18],22:[2,18],24:[2,18]},{22:[1,28],24:[1,27]},{22:[2,20],24:[2,20]},{14:[2,14],18:[2,14],22:[2,14],24:[2,14]},{3:20,4:[1,12],20:29},{3:5,4:[1,12],5:6,6:[1,13],7:3,8:[1,9],9:4,10:[1,10],11:[1,11],13:30,15:7,16:8,17:[1,14],23:[1,15]},{14:[2,19],18:[2,19],22:[2,19],24:[2,19]},{3:5,4:[1,12],5:6,6:[1,13],7:3,8:[1,9],9:4,10:[1,10],11:[1,11],13:31,15:7,16:8,17:[1,14],23:[1,15]},{18:[2,17],22:[2,17]},{18:[2,15],22:[2,15]},{22:[2,21],24:[2,21]}],
defaultActions: {16:[2,6]},
parseError: function parseError(str, hash) {
    throw new Error(str);
},
parse: function parse(input) {
    var self = this,
        stack = [0],
        vstack = [null], // semantic value stack
        lstack = [], // location stack
        table = this.table,
        yytext = '',
        yylineno = 0,
        yyleng = 0,
        recovering = 0,
        TERROR = 2,
        EOF = 1;

    //this.reductionCount = this.shiftCount = 0;

    this.lexer.setInput(input);
    this.lexer.yy = this.yy;
    this.yy.lexer = this.lexer;
    if (typeof this.lexer.yylloc == 'undefined')
        this.lexer.yylloc = {};
    var yyloc = this.lexer.yylloc;
    lstack.push(yyloc);

    if (typeof this.yy.parseError === 'function')
        this.parseError = this.yy.parseError;

    function popStack (n) {
        stack.length = stack.length - 2*n;
        vstack.length = vstack.length - n;
        lstack.length = lstack.length - n;
    }

    function lex() {
        var token;
        token = self.lexer.lex() || 1; // $end = 1
        // if token isn't its numeric value, convert
        if (typeof token !== 'number') {
            token = self.symbols_[token] || token;
        }
        return token;
    }

    var symbol, preErrorSymbol, state, action, a, r, yyval={},p,len,newState, expected;
    while (true) {
        // retreive state number from top of stack
        state = stack[stack.length-1];

        // use default actions if available
        if (this.defaultActions[state]) {
            action = this.defaultActions[state];
        } else {
            if (symbol == null)
                symbol = lex();
            // read action for current state and first input
            action = table[state] && table[state][symbol];
        }

        // handle parse error
        _handle_error:
        if (typeof action === 'undefined' || !action.length || !action[0]) {

            if (!recovering) {
                // Report error
                expected = [];
                for (p in table[state]) if (this.terminals_[p] && p > 2) {
                    expected.push("'"+this.terminals_[p]+"'");
                }
                var errStr = '';
                if (this.lexer.showPosition) {
                    errStr = 'Parse error on line '+(yylineno+1)+":\n"+this.lexer.showPosition()+"\nExpecting "+expected.join(', ') + ", got '" + this.terminals_[symbol]+ "'";
                } else {
                    errStr = 'Parse error on line '+(yylineno+1)+": Unexpected " +
                                  (symbol == 1 /*EOF*/ ? "end of input" :
                                              ("'"+(this.terminals_[symbol] || symbol)+"'"));
                }
                this.parseError(errStr,
                    {text: this.lexer.match, token: this.terminals_[symbol] || symbol, line: this.lexer.yylineno, loc: yyloc, expected: expected});
            }

            // just recovered from another error
            if (recovering == 3) {
                if (symbol == EOF) {
                    throw new Error(errStr || 'Parsing halted.');
                }

                // discard current lookahead and grab another
                yyleng = this.lexer.yyleng;
                yytext = this.lexer.yytext;
                yylineno = this.lexer.yylineno;
                yyloc = this.lexer.yylloc;
                symbol = lex();
            }

            // try to recover from error
            while (1) {
                // check for error recovery rule in this state
                if ((TERROR.toString()) in table[state]) {
                    break;
                }
                if (state == 0) {
                    throw new Error(errStr || 'Parsing halted.');
                }
                popStack(1);
                state = stack[stack.length-1];
            }

            preErrorSymbol = symbol; // save the lookahead token
            symbol = TERROR;         // insert generic error symbol as new lookahead
            state = stack[stack.length-1];
            action = table[state] && table[state][TERROR];
            recovering = 3; // allow 3 real symbols to be shifted before reporting a new error
        }

        // this shouldn't happen, unless resolve defaults are off
        if (action[0] instanceof Array && action.length > 1) {
            throw new Error('Parse Error: multiple actions possible at state: '+state+', token: '+symbol);
        }

        switch (action[0]) {

            case 1: // shift
                //this.shiftCount++;

                stack.push(symbol);
                vstack.push(this.lexer.yytext);
                lstack.push(this.lexer.yylloc);
                stack.push(action[1]); // push state
                symbol = null;
                if (!preErrorSymbol) { // normal execution/no error
                    yyleng = this.lexer.yyleng;
                    yytext = this.lexer.yytext;
                    yylineno = this.lexer.yylineno;
                    yyloc = this.lexer.yylloc;
                    if (recovering > 0)
                        recovering--;
                } else { // error just occurred, resume old lookahead f/ before error
                    symbol = preErrorSymbol;
                    preErrorSymbol = null;
                }
                break;

            case 2: // reduce
                //this.reductionCount++;

                len = this.productions_[action[1]][1];

                // perform semantic action
                yyval.$ = vstack[vstack.length-len]; // default to $$ = $1
                // default location, uses first token for firsts, last for lasts
                yyval._$ = {
                    first_line: lstack[lstack.length-(len||1)].first_line,
                    last_line: lstack[lstack.length-1].last_line,
                    first_column: lstack[lstack.length-(len||1)].first_column,
                    last_column: lstack[lstack.length-1].last_column
                };
                r = this.performAction.call(yyval, yytext, yyleng, yylineno, this.yy, action[1], vstack, lstack);

                if (typeof r !== 'undefined') {
                    return r;
                }

                // pop off stack
                if (len) {
                    stack = stack.slice(0,-1*len*2);
                    vstack = vstack.slice(0, -1*len);
                    lstack = lstack.slice(0, -1*len);
                }

                stack.push(this.productions_[action[1]][0]);    // push nonterminal (reduce)
                vstack.push(yyval.$);
                lstack.push(yyval._$);
                // goto new state = table[STATE][NONTERMINAL]
                newState = table[stack[stack.length-2]][stack[stack.length-1]];
                stack.push(newState);
                break;

            case 3: // accept
                return true;
        }

    }

    return true;
}};
/* Jison generated lexer */
var lexer = (function(){
var lexer = ({EOF:1,
parseError:function parseError(str, hash) {
        if (this.yy.parseError) {
            this.yy.parseError(str, hash);
        } else {
            throw new Error(str);
        }
    },
setInput:function (input) {
        this._input = input;
        this._more = this._less = this.done = false;
        this.yylineno = this.yyleng = 0;
        this.yytext = this.matched = this.match = '';
        this.conditionStack = ['INITIAL'];
        this.yylloc = {first_line:1,first_column:0,last_line:1,last_column:0};
        return this;
    },
input:function () {
        var ch = this._input[0];
        this.yytext+=ch;
        this.yyleng++;
        this.match+=ch;
        this.matched+=ch;
        var lines = ch.match(/\n/);
        if (lines) this.yylineno++;
        this._input = this._input.slice(1);
        return ch;
    },
unput:function (ch) {
        this._input = ch + this._input;
        return this;
    },
more:function () {
        this._more = true;
        return this;
    },
less:function (n) {
        this._input = this.match.slice(n) + this._input;
    },
pastInput:function () {
        var past = this.matched.substr(0, this.matched.length - this.match.length);
        return (past.length > 20 ? '...':'') + past.substr(-20).replace(/\n/g, "");
    },
upcomingInput:function () {
        var next = this.match;
        if (next.length < 20) {
            next += this._input.substr(0, 20-next.length);
        }
        return (next.substr(0,20)+(next.length > 20 ? '...':'')).replace(/\n/g, "");
    },
showPosition:function () {
        var pre = this.pastInput();
        var c = new Array(pre.length + 1).join("-");
        return pre + this.upcomingInput() + "\n" + c+"^";
    },
next:function () {
        if (this.done) {
            return this.EOF;
        }
        if (!this._input) this.done = true;

        var token,
            match,
            tempMatch,
            index,
            col,
            lines;
        if (!this._more) {
            this.yytext = '';
            this.match = '';
        }
        var rules = this._currentRules();
        for (var i=0;i < rules.length; i++) {
            tempMatch = this._input.match(this.rules[rules[i]]);
            if (tempMatch && (!match || tempMatch[0].length > match[0].length)) {
                match = tempMatch;
                index = i;
                if (!this.options.flex) break;
            }
        }
        if (match) {
            lines = match[0].match(/\n.*/g);
            if (lines) this.yylineno += lines.length;
            this.yylloc = {first_line: this.yylloc.last_line,
                           last_line: this.yylineno+1,
                           first_column: this.yylloc.last_column,
                           last_column: lines ? lines[lines.length-1].length-1 : this.yylloc.last_column + match[0].length}
            this.yytext += match[0];
            this.match += match[0];
            this.yyleng = this.yytext.length;
            this._more = false;
            this._input = this._input.slice(match[0].length);
            this.matched += match[0];
            token = this.performAction.call(this, this.yy, this, rules[index],this.conditionStack[this.conditionStack.length-1]);
            if (this.done && this._input) this.done = false;
            if (token) return token;
            else return;
        }
        if (this._input === "") {
            return this.EOF;
        } else {
            this.parseError('Lexical error on line '+(this.yylineno+1)+'. Unrecognized text.\n'+this.showPosition(), 
                    {text: "", token: null, line: this.yylineno});
        }
    },
lex:function lex() {
        var r = this.next();
        if (typeof r !== 'undefined') {
            return r;
        } else {
            return this.lex();
        }
    },
begin:function begin(condition) {
        this.conditionStack.push(condition);
    },
popState:function popState() {
        return this.conditionStack.pop();
    },
_currentRules:function _currentRules() {
        return this.conditions[this.conditionStack[this.conditionStack.length-1]].rules;
    },
topState:function () {
        return this.conditionStack[this.conditionStack.length-2];
    },
pushState:function begin(condition) {
        this.begin(condition);
    }});
lexer.options = {};
lexer.performAction = function anonymous(yy,yy_,$avoiding_name_collisions,YY_START) {

var YYSTATE=YY_START
switch($avoiding_name_collisions) {
case 0:/* skip whitespace */
break;
case 1:return 6
break;
case 2:yy_.yytext = yy_.yytext.substr(1,yy_.yyleng-2); return 4
break;
case 3:return 17
break;
case 4:return 18
break;
case 5:return 23
break;
case 6:return 24
break;
case 7:return 22
break;
case 8:return 21
break;
case 9:return 10
break;
case 10:return 11
break;
case 11:return 8
break;
case 12:return 14
break;
case 13:return 'INVALID'
break;
}
};
lexer.rules = [/^(?:\s+)/,/^(?:(-?([0-9]|[1-9][0-9]+))(\.[0-9]+)?([eE][-+]?[0-9]+)?\b)/,/^(?:"(?:\\[\\"bfnrt/]|\\u[a-fA-F0-9]{4}|[^\\\0-\x09\x0a-\x1f"])*")/,/^(?:\{)/,/^(?:\})/,/^(?:\[)/,/^(?:\])/,/^(?:,)/,/^(?::)/,/^(?:true\b)/,/^(?:false\b)/,/^(?:null\b)/,/^(?:$)/,/^(?:.)/];
lexer.conditions = {"INITIAL":{"rules":[0,1,2,3,4,5,6,7,8,9,10,11,12,13],"inclusive":true}};


;
return lexer;})()
parser.lexer = lexer;
return parser;
})();
if (true) {
  exports.parser = jsonlint;
  exports.parse = jsonlint.parse.bind(jsonlint);
}

/***/ }),
/* 105 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


var ace = __webpack_require__(101);
var ModeSwitcher = __webpack_require__(22);
var util = __webpack_require__(2);

// create a mixin with the functions for text mode
var textmode = {};

var MAX_ERRORS = 3; // maximum number of displayed errors at the bottom

var DEFAULT_THEME = 'ace/theme/jsoneditor';

/**
 * Create a text editor
 * @param {Element} container
 * @param {Object} [options]   Object with options. available options:
 *                             {String} mode             Available values:
 *                                                       "text" (default)
 *                                                       or "code".
 *                             {Number} indentation      Number of indentation
 *                                                       spaces. 2 by default.
 *                             {function} onChange       Callback method
 *                                                       triggered on change
 *                             {function} onModeChange   Callback method
 *                                                       triggered after setMode
 *                             {function} onEditable     Determine if textarea is readOnly
 *                                                       readOnly defaults true
 *                             {Object} ace              A custom instance of
 *                                                       Ace editor.
 *                             {boolean} escapeUnicode   If true, unicode
 *                                                       characters are escaped.
 *                                                       false by default.
 * @private
 */
textmode.create = function (container, options) {
  // read options
  options = options || {};
  this.options = options;

  // indentation
  if (options.indentation) {
    this.indentation = Number(options.indentation);
  }
  else {
    this.indentation = 2; // number of spaces
  }

  // grab ace from options if provided
  var _ace = options.ace ? options.ace : ace;
  // TODO: make the option options.ace deprecated, it's not needed anymore (see #309)

  // determine mode
  this.mode = (options.mode == 'code') ? 'code' : 'text';
  if (this.mode == 'code') {
    // verify whether Ace editor is available and supported
    if (typeof _ace === 'undefined') {
      this.mode = 'text';
      console.warn('Failed to load Ace editor, falling back to plain text mode. Please use a JSONEditor bundle including Ace, or pass Ace as via the configuration option `ace`.');
    }
  }

  // determine theme
  this.theme = options.theme || DEFAULT_THEME;
  if (this.theme === DEFAULT_THEME && _ace) {
    try {
      __webpack_require__(102);
    }
    catch (err) {
      console.error(err);
    }
  }

  var me = this;
  this.container = container;
  this.dom = {};
  this.aceEditor = undefined;  // ace code editor
  this.textarea = undefined;  // plain text editor (fallback when Ace is not available)
  this.validateSchema = null;

  // create a debounced validate function
  this._debouncedValidate = util.debounce(this.validate.bind(this), this.DEBOUNCE_INTERVAL);

  this.width = container.clientWidth;
  this.height = container.clientHeight;

  this.frame = document.createElement('div');
  this.frame.className = 'jsoneditor jsoneditor-mode-' + this.options.mode;
  this.frame.onclick = function (event) {
    // prevent default submit action when the editor is located inside a form
    event.preventDefault();
  };
  this.frame.onkeydown = function (event) {
    me._onKeyDown(event);
  };

  // create menu
  this.menu = document.createElement('div');
  this.menu.className = 'jsoneditor-menu';
  this.frame.appendChild(this.menu);

  // create format button
  var buttonFormat = document.createElement('button');
  buttonFormat.type = 'button';
  buttonFormat.className = 'jsoneditor-format';
  buttonFormat.title = 'Format JSON data, with proper indentation and line feeds (Ctrl+\\)';
  this.menu.appendChild(buttonFormat);
  buttonFormat.onclick = function () {
    try {
      me.format();
      me._onChange();
    }
    catch (err) {
      me._onError(err);
    }
  };

  // create compact button
  var buttonCompact = document.createElement('button');
  buttonCompact.type = 'button';
  buttonCompact.className = 'jsoneditor-compact';
  buttonCompact.title = 'Compact JSON data, remove all whitespaces (Ctrl+Shift+\\)';
  this.menu.appendChild(buttonCompact);
  buttonCompact.onclick = function () {
    try {
      me.compact();
      me._onChange();
    }
    catch (err) {
      me._onError(err);
    }
  };

  // create mode box
  if (this.options && this.options.modes && this.options.modes.length) {
    this.modeSwitcher = new ModeSwitcher(this.menu, this.options.modes, this.options.mode, function onSwitch(mode) {
      // switch mode and restore focus
      me.setMode(mode);
      me.modeSwitcher.focus();
    });
  }

  var emptyNode = {};
  var isReadOnly = (this.options.onEditable
  && typeof(this.options.onEditable === 'function')
  && !this.options.onEditable(emptyNode));

  this.content = document.createElement('div');
  this.content.className = 'jsoneditor-outer';
  this.frame.appendChild(this.content);

  this.container.appendChild(this.frame);

  if (this.mode == 'code') {
    this.editorDom = document.createElement('div');
    this.editorDom.style.height = '100%'; // TODO: move to css
    this.editorDom.style.width = '100%'; // TODO: move to css
    this.content.appendChild(this.editorDom);

    var aceEditor = _ace.edit(this.editorDom);
    aceEditor.$blockScrolling = Infinity;
    aceEditor.setTheme(this.theme);
    aceEditor.setOptions({ readOnly: isReadOnly });
    aceEditor.setShowPrintMargin(false);
    aceEditor.setFontSize(13);
    aceEditor.getSession().setMode('ace/mode/json');
    aceEditor.getSession().setTabSize(this.indentation);
    aceEditor.getSession().setUseSoftTabs(true);
    aceEditor.getSession().setUseWrapMode(true);
    aceEditor.commands.bindKey('Ctrl-L', null);    // disable Ctrl+L (is used by the browser to select the address bar)
    aceEditor.commands.bindKey('Command-L', null); // disable Ctrl+L (is used by the browser to select the address bar)
    this.aceEditor = aceEditor;

    // TODO: deprecated since v5.0.0. Cleanup backward compatibility some day
    if (!this.hasOwnProperty('editor')) {
      Object.defineProperty(this, 'editor', {
        get: function () {
          console.warn('Property "editor" has been renamed to "aceEditor".');
          return me.aceEditor;
        },
        set: function (aceEditor) {
          console.warn('Property "editor" has been renamed to "aceEditor".');
          me.aceEditor = aceEditor;
        }
      });
    }

    var poweredBy = document.createElement('a');
    poweredBy.appendChild(document.createTextNode('powered by ace'));
    poweredBy.href = 'http://ace.ajax.org';
    poweredBy.target = '_blank';
    poweredBy.className = 'jsoneditor-poweredBy';
    poweredBy.onclick = function () {
      // TODO: this anchor falls below the margin of the content,
      // therefore the normal a.href does not work. We use a click event
      // for now, but this should be fixed.
      window.open(poweredBy.href, poweredBy.target);
    };
    this.menu.appendChild(poweredBy);

    // register onchange event
    aceEditor.on('change', this._onChange.bind(this));
  }
  else {
    // load a plain text textarea
    var textarea = document.createElement('textarea');
    textarea.className = 'jsoneditor-text';
    textarea.spellcheck = false;
    this.content.appendChild(textarea);
    this.textarea = textarea;
    this.textarea.readOnly = isReadOnly;

    // register onchange event
    if (this.textarea.oninput === null) {
      this.textarea.oninput = this._onChange.bind(this);
    }
    else {
      // oninput is undefined. For IE8-
      this.textarea.onchange = this._onChange.bind(this);
    }
  }

  this.setSchema(this.options.schema);
};

/**
 * Handle a change:
 * - Validate JSON schema
 * - Send a callback to the onChange listener if provided
 * @private
 */
textmode._onChange = function () {
  // validate JSON schema (if configured)
  this._debouncedValidate();

  // trigger the onChange callback
  if (this.options.onChange) {
    try {
      this.options.onChange();
    }
    catch (err) {
      console.error('Error in onChange callback: ', err);
    }
  }
};

/**
 * Event handler for keydown. Handles shortcut keys
 * @param {Event} event
 * @private
 */
textmode._onKeyDown = function (event) {
  var keynum = event.which || event.keyCode;
  var handled = false;

  if (keynum == 220 && event.ctrlKey) {
    if (event.shiftKey) { // Ctrl+Shift+\
      this.compact();
      this._onChange();
    }
    else { // Ctrl+\
      this.format();
      this._onChange();
    }
    handled = true;
  }

  if (handled) {
    event.preventDefault();
    event.stopPropagation();
  }
};

/**
 * Destroy the editor. Clean up DOM, event listeners, and web workers.
 */
textmode.destroy = function () {
  // remove old ace editor
  if (this.aceEditor) {
    this.aceEditor.destroy();
    this.aceEditor = null;
  }

  if (this.frame && this.container && this.frame.parentNode == this.container) {
    this.container.removeChild(this.frame);
  }

  if (this.modeSwitcher) {
    this.modeSwitcher.destroy();
    this.modeSwitcher = null;
  }

  this.textarea = null;
  
  this._debouncedValidate = null;
};

/**
 * Compact the code in the formatter
 */
textmode.compact = function () {
  var json = this.get();
  var text = JSON.stringify(json);
  this.setText(text);
};

/**
 * Format the code in the formatter
 */
textmode.format = function () {
  var json = this.get();
  var text = JSON.stringify(json, null, this.indentation);
  this.setText(text);
};

/**
 * Set focus to the formatter
 */
textmode.focus = function () {
  if (this.textarea) {
    this.textarea.focus();
  }
  if (this.aceEditor) {
    this.aceEditor.focus();
  }
};

/**
 * Resize the formatter
 */
textmode.resize = function () {
  if (this.aceEditor) {
    var force = false;
    this.aceEditor.resize(force);
  }
};

/**
 * Set json data in the formatter
 * @param {Object} json
 */
textmode.set = function(json) {
  this.setText(JSON.stringify(json, null, this.indentation));
};

/**
 * Get json data from the formatter
 * @return {Object} json
 */
textmode.get = function() {
  var text = this.getText();
  var json;

  try {
    json = util.parse(text); // this can throw an error
  }
  catch (err) {
    // try to sanitize json, replace JavaScript notation with JSON notation
    text = util.sanitize(text);

    // try to parse again
    json = util.parse(text); // this can throw an error
  }

  return json;
};

/**
 * Get the text contents of the editor
 * @return {String} jsonText
 */
textmode.getText = function() {
  if (this.textarea) {
    return this.textarea.value;
  }
  if (this.aceEditor) {
    return this.aceEditor.getValue();
  }
  return '';
};

/**
 * Set the text contents of the editor
 * @param {String} jsonText
 */
textmode.setText = function(jsonText) {
  var text;

  if (this.options.escapeUnicode === true) {
    text = util.escapeUnicodeChars(jsonText);
  }
  else {
    text = jsonText;
  }

  if (this.textarea) {
    this.textarea.value = text;
  }
  if (this.aceEditor) {
    // prevent emitting onChange events while setting new text
    var originalOnChange = this.options.onChange;
    this.options.onChange = null;

    this.aceEditor.setValue(text, -1);

    this.options.onChange = originalOnChange;
  }

  // validate JSON schema
  this.validate();
};

/**
 * Validate current JSON object against the configured JSON schema
 * Throws an exception when no JSON schema is configured
 */
textmode.validate = function () {
  // clear all current errors
  if (this.dom.validationErrors) {
    this.dom.validationErrors.parentNode.removeChild(this.dom.validationErrors);
    this.dom.validationErrors = null;

    this.content.style.marginBottom = '';
    this.content.style.paddingBottom = '';
  }

  var doValidate = false;
  var errors = [];
  var json;
  try {
    json = this.get(); // this can fail when there is no valid json
    doValidate = true;
  }
  catch (err) {
    // no valid JSON, don't validate
  }

  // only validate the JSON when parsing the JSON succeeded
  if (doValidate && this.validateSchema) {
    var valid = this.validateSchema(json);
    if (!valid) {
      errors = this.validateSchema.errors.map(function (error) {
        return util.improveSchemaError(error);
      });
    }
  }

  if (errors.length > 0) {
    // limit the number of displayed errors
    var limit = errors.length > MAX_ERRORS;
    if (limit) {
      errors = errors.slice(0, MAX_ERRORS);
      var hidden = this.validateSchema.errors.length - MAX_ERRORS;
      errors.push('(' + hidden + ' more errors...)')
    }

    var validationErrors = document.createElement('div');
    validationErrors.innerHTML = '<table class="jsoneditor-text-errors">' +
        '<tbody>' +
        errors.map(function (error) {
          var message;
          if (typeof error === 'string') {
            message = '<td colspan="2"><pre>' + error + '</pre></td>';
          }
          else {
            message = '<td>' + error.dataPath + '</td>' +
                '<td>' + error.message + '</td>';
          }

          return '<tr><td><button class="jsoneditor-schema-error"></button></td>' + message + '</tr>'
        }).join('') +
        '</tbody>' +
        '</table>';

    this.dom.validationErrors = validationErrors;
    this.frame.appendChild(validationErrors);

    var height = validationErrors.clientHeight;
    this.content.style.marginBottom = (-height) + 'px';
    this.content.style.paddingBottom = height + 'px';
  }

  // update the height of the ace editor
  if (this.aceEditor) {
    var force = false;
    this.aceEditor.resize(force);
  }
};

// define modes
module.exports = [
  {
    mode: 'text',
    mixin: textmode,
    data: 'text',
    load: textmode.format
  },
  {
    mode: 'code',
    mixin: textmode,
    data: 'text',
    load: textmode.format
  }
];


/***/ }),
/* 106 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";



var Highlighter = __webpack_require__(96);
var History = __webpack_require__(97);
var SearchBox = __webpack_require__(100);
var ContextMenu = __webpack_require__(4);
var Node = __webpack_require__(99);
var ModeSwitcher = __webpack_require__(22);
var util = __webpack_require__(2);

// create a mixin with the functions for tree mode
var treemode = {};

/**
 * Create a tree editor
 * @param {Element} container    Container element
 * @param {Object}  [options]    Object with options. available options:
 *                               {String} mode            Editor mode. Available values:
 *                                                        'tree' (default), 'view',
 *                                                        and 'form'.
 *                               {Boolean} search         Enable search box.
 *                                                        True by default
 *                               {Boolean} history        Enable history (undo/redo).
 *                                                        True by default
 *                               {function} onChange      Callback method, triggered
 *                                                        on change of contents
 *                               {String} name            Field name for the root node.
 *                               {boolean} escapeUnicode  If true, unicode
 *                                                        characters are escaped.
 *                                                        false by default.
 *                               {Object} schema          A JSON Schema for validation
 * @private
 */
treemode.create = function (container, options) {
  if (!container) {
    throw new Error('No container element provided.');
  }
  this.container = container;
  this.dom = {};
  this.highlighter = new Highlighter();
  this.selection = undefined; // will hold the last input selection
  this.multiselection = {
    nodes: []
  };
  this.validateSchema = null; // will be set in .setSchema(schema)
  this.errorNodes = [];

  this.node = null;
  this.focusTarget = null;

  this._setOptions(options);

  if (this.options.history && this.options.mode !== 'view') {
    this.history = new History(this);
  }

  this._createFrame();
  this._createTable();
};

/**
 * Destroy the editor. Clean up DOM, event listeners, and web workers.
 */
treemode.destroy = function () {
  if (this.frame && this.container && this.frame.parentNode == this.container) {
    this.container.removeChild(this.frame);
    this.frame = null;
  }
  this.container = null;

  this.dom = null;

  this.clear();
  this.node = null;
  this.focusTarget = null;
  this.selection = null;
  this.multiselection = null;
  this.errorNodes = null;
  this.validateSchema = null;
  this._debouncedValidate = null;

  if (this.history) {
    this.history.destroy();
    this.history = null;
  }

  if (this.searchBox) {
    this.searchBox.destroy();
    this.searchBox = null;
  }

  if (this.modeSwitcher) {
    this.modeSwitcher.destroy();
    this.modeSwitcher = null;
  }
};

/**
 * Initialize and set default options
 * @param {Object}  [options]    See description in constructor
 * @private
 */
treemode._setOptions = function (options) {
  this.options = {
    search: true,
    history: true,
    mode: 'tree',
    name: undefined,   // field name of root node
    schema: null
  };

  // copy all options
  if (options) {
    for (var prop in options) {
      if (options.hasOwnProperty(prop)) {
        this.options[prop] = options[prop];
      }
    }
  }

  // compile a JSON schema validator if a JSON schema is provided
  this.setSchema(this.options.schema);

  // create a debounced validate function
  this._debouncedValidate = util.debounce(this.validate.bind(this), this.DEBOUNCE_INTERVAL);
};

/**
 * Set JSON object in editor
 * @param {Object | undefined} json      JSON data
 * @param {String}             [name]    Optional field name for the root node.
 *                                       Can also be set using setName(name).
 */
treemode.set = function (json, name) {
  // adjust field name for root node
  if (name) {
    // TODO: deprecated since version 2.2.0. Cleanup some day.
    console.warn('Second parameter "name" is deprecated. Use setName(name) instead.');
    this.options.name = name;
  }

  // verify if json is valid JSON, ignore when a function
  if (json instanceof Function || (json === undefined)) {
    this.clear();
  }
  else {
    this.content.removeChild(this.table);  // Take the table offline

    // replace the root node
    var params = {
      field: this.options.name,
      value: json
    };
    var node = new Node(this, params);
    this._setRoot(node);

    // validate JSON schema (if configured)
    this.validate();

    // expand
    var recurse = false;
    this.node.expand(recurse);

    this.content.appendChild(this.table);  // Put the table online again
  }

  // TODO: maintain history, store last state and previous document
  if (this.history) {
    this.history.clear();
  }

  // clear search
  if (this.searchBox) {
    this.searchBox.clear();
  }
};

/**
 * Get JSON object from editor
 * @return {Object | undefined} json
 */
treemode.get = function () {
  // remove focus from currently edited node
  if (this.focusTarget) {
    var node = Node.getNodeFromTarget(this.focusTarget);
    if (node) {
      node.blur();
    }
  }

  if (this.node) {
    return this.node.getValue();
  }
  else {
    return undefined;
  }
};

/**
 * Get the text contents of the editor
 * @return {String} jsonText
 */
treemode.getText = function() {
  return JSON.stringify(this.get());
};

/**
 * Set the text contents of the editor
 * @param {String} jsonText
 */
treemode.setText = function(jsonText) {
  this.set(util.parse(jsonText));
};

/**
 * Set a field name for the root node.
 * @param {String | undefined} name
 */
treemode.setName = function (name) {
  this.options.name = name;
  if (this.node) {
    this.node.updateField(this.options.name);
  }
};

/**
 * Get the field name for the root node.
 * @return {String | undefined} name
 */
treemode.getName = function () {
  return this.options.name;
};

/**
 * Set focus to the editor. Focus will be set to:
 * - the first editable field or value, or else
 * - to the expand button of the root node, or else
 * - to the context menu button of the root node, or else
 * - to the first button in the top menu
 */
treemode.focus = function () {
  var input = this.content.querySelector('[contenteditable=true]');
  if (input) {
    input.focus();
  }
  else if (this.node.dom.expand) {
    this.node.dom.expand.focus();
  }
  else if (this.node.dom.menu) {
    this.node.dom.menu.focus();
  }
  else {
    // focus to the first button in the menu
    input = this.frame.querySelector('button');
    if (input) {
      input.focus();
    }
  }
};

/**
 * Remove the root node from the editor
 */
treemode.clear = function () {
  if (this.node) {
    this.node.collapse();
    this.tbody.removeChild(this.node.getDom());
    delete this.node;
  }
};

/**
 * Set the root node for the json editor
 * @param {Node} node
 * @private
 */
treemode._setRoot = function (node) {
  this.clear();

  this.node = node;

  // append to the dom
  this.tbody.appendChild(node.getDom());
};

/**
 * Search text in all nodes
 * The nodes will be expanded when the text is found one of its childs,
 * else it will be collapsed. Searches are case insensitive.
 * @param {String} text
 * @return {Object[]} results  Array with nodes containing the search results
 *                             The result objects contains fields:
 *                             - {Node} node,
 *                             - {String} elem  the dom element name where
 *                                              the result is found ('field' or
 *                                              'value')
 */
treemode.search = function (text) {
  var results;
  if (this.node) {
    this.content.removeChild(this.table);  // Take the table offline
    results = this.node.search(text);
    this.content.appendChild(this.table);  // Put the table online again
  }
  else {
    results = [];
  }

  return results;
};

/**
 * Expand all nodes
 */
treemode.expandAll = function () {
  if (this.node) {
    this.content.removeChild(this.table);  // Take the table offline
    this.node.expand();
    this.content.appendChild(this.table);  // Put the table online again
  }
};

/**
 * Collapse all nodes
 */
treemode.collapseAll = function () {
  if (this.node) {
    this.content.removeChild(this.table);  // Take the table offline
    this.node.collapse();
    this.content.appendChild(this.table);  // Put the table online again
  }
};

/**
 * The method onChange is called whenever a field or value is changed, created,
 * deleted, duplicated, etc.
 * @param {String} action  Change action. Available values: "editField",
 *                         "editValue", "changeType", "appendNode",
 *                         "removeNode", "duplicateNode", "moveNode", "expand",
 *                         "collapse".
 * @param {Object} params  Object containing parameters describing the change.
 *                         The parameters in params depend on the action (for
 *                         example for "editValue" the Node, old value, and new
 *                         value are provided). params contains all information
 *                         needed to undo or redo the action.
 * @private
 */
treemode._onAction = function (action, params) {
  // add an action to the history
  if (this.history) {
    this.history.add(action, params);
  }

  this._onChange();
};

/**
 * Handle a change:
 * - Validate JSON schema
 * - Send a callback to the onChange listener if provided
 * @private
 */
treemode._onChange = function () {
  // validate JSON schema (if configured)
  this._debouncedValidate();

  // trigger the onChange callback
  if (this.options.onChange) {
    try {
      this.options.onChange();
    }
    catch (err) {
      console.error('Error in onChange callback: ', err);
    }
  }
};

/**
 * Validate current JSON object against the configured JSON schema
 * Throws an exception when no JSON schema is configured
 */
treemode.validate = function () {
  // clear all current errors
  if (this.errorNodes) {
    this.errorNodes.forEach(function (node) {
      node.setError(null);
    });
  }

  var root = this.node;
  if (!root) { // TODO: this should be redundant but is needed on mode switch
    return;
  }

  // check for duplicate keys
  var duplicateErrors = root.validate();

  // validate the JSON
  var schemaErrors = [];
  if (this.validateSchema) {
    var valid = this.validateSchema(root.getValue());
    if (!valid) {
      // apply all new errors
      schemaErrors = this.validateSchema.errors
          .map(function (error) {
            return util.improveSchemaError(error);
          })
          .map(function findNode (error) {
            return {
              node: root.findNode(error.dataPath),
              error: error
            }
          })
          .filter(function hasNode (entry) {
            return entry.node != null
          });
    }
  }

  // display the error in the nodes with a problem
  this.errorNodes = duplicateErrors
      .concat(schemaErrors)
      .reduce(function expandParents (all, entry) {
        // expand parents, then merge such that parents come first and
        // original entries last
        return entry.node
            .findParents()
            .map(function (parent) {
              return {
                node: parent,
                child: entry.node,
                error: {
                  message: parent.type === 'object'
                      ? 'Contains invalid properties' // object
                      : 'Contains invalid items'      // array
                }
              };
            })
            .concat(all, [entry]);
      }, [])
      // TODO: dedupe the parent nodes
      .map(function setError (entry) {
        entry.node.setError(entry.error, entry.child);
        return entry.node;
      });
};

/**
 * Refresh the rendered contents
 */
treemode.refresh = function () {
  if (this.node) {
    this.node.updateDom({recurse: true});
  }
};

/**
 * Start autoscrolling when given mouse position is above the top of the
 * editor contents, or below the bottom.
 * @param {Number} mouseY  Absolute mouse position in pixels
 */
treemode.startAutoScroll = function (mouseY) {
  var me = this;
  var content = this.content;
  var top = util.getAbsoluteTop(content);
  var height = content.clientHeight;
  var bottom = top + height;
  var margin = 24;
  var interval = 50; // ms

  if ((mouseY < top + margin) && content.scrollTop > 0) {
    this.autoScrollStep = ((top + margin) - mouseY) / 3;
  }
  else if (mouseY > bottom - margin &&
      height + content.scrollTop < content.scrollHeight) {
    this.autoScrollStep = ((bottom - margin) - mouseY) / 3;
  }
  else {
    this.autoScrollStep = undefined;
  }

  if (this.autoScrollStep) {
    if (!this.autoScrollTimer) {
      this.autoScrollTimer = setInterval(function () {
        if (me.autoScrollStep) {
          content.scrollTop -= me.autoScrollStep;
        }
        else {
          me.stopAutoScroll();
        }
      }, interval);
    }
  }
  else {
    this.stopAutoScroll();
  }
};

/**
 * Stop auto scrolling. Only applicable when scrolling
 */
treemode.stopAutoScroll = function () {
  if (this.autoScrollTimer) {
    clearTimeout(this.autoScrollTimer);
    delete this.autoScrollTimer;
  }
  if (this.autoScrollStep) {
    delete this.autoScrollStep;
  }
};


/**
 * Set the focus to an element in the editor, set text selection, and
 * set scroll position.
 * @param {Object} selection  An object containing fields:
 *                            {Element | undefined} dom     The dom element
 *                                                          which has focus
 *                            {Range | TextRange} range     A text selection
 *                            {Node[]} nodes                Nodes in case of multi selection
 *                            {Number} scrollTop            Scroll position
 */
treemode.setSelection = function (selection) {
  if (!selection) {
    return;
  }

  if ('scrollTop' in selection && this.content) {
    // TODO: animated scroll
    this.content.scrollTop = selection.scrollTop;
  }
  if (selection.nodes) {
    // multi-select
    this.select(selection.nodes);
  }
  if (selection.range) {
    util.setSelectionOffset(selection.range);
  }
  if (selection.dom) {
    selection.dom.focus();
  }
};

/**
 * Get the current focus
 * @return {Object} selection An object containing fields:
 *                            {Element | undefined} dom     The dom element
 *                                                          which has focus
 *                            {Range | TextRange} range     A text selection
 *                            {Node[]} nodes                Nodes in case of multi selection
 *                            {Number} scrollTop            Scroll position
 */
treemode.getSelection = function () {
  var range = util.getSelectionOffset();
  if (range && range.container.nodeName !== 'DIV') { // filter on (editable) divs)
    range = null;
  }

  return {
    dom: this.focusTarget,
    range: range,
    nodes: this.multiselection.nodes.slice(0),
    scrollTop: this.content ? this.content.scrollTop : 0
  };
};

/**
 * Adjust the scroll position such that given top position is shown at 1/4
 * of the window height.
 * @param {Number} top
 * @param {function(boolean)} [callback]   Callback, executed when animation is
 *                                         finished. The callback returns true
 *                                         when animation is finished, or false
 *                                         when not.
 */
treemode.scrollTo = function (top, callback) {
  var content = this.content;
  if (content) {
    var editor = this;
    // cancel any running animation
    if (editor.animateTimeout) {
      clearTimeout(editor.animateTimeout);
      delete editor.animateTimeout;
    }
    if (editor.animateCallback) {
      editor.animateCallback(false);
      delete editor.animateCallback;
    }

    // calculate final scroll position
    var height = content.clientHeight;
    var bottom = content.scrollHeight - height;
    var finalScrollTop = Math.min(Math.max(top - height / 4, 0), bottom);

    // animate towards the new scroll position
    var animate = function () {
      var scrollTop = content.scrollTop;
      var diff = (finalScrollTop - scrollTop);
      if (Math.abs(diff) > 3) {
        content.scrollTop += diff / 3;
        editor.animateCallback = callback;
        editor.animateTimeout = setTimeout(animate, 50);
      }
      else {
        // finished
        if (callback) {
          callback(true);
        }
        content.scrollTop = finalScrollTop;
        delete editor.animateTimeout;
        delete editor.animateCallback;
      }
    };
    animate();
  }
  else {
    if (callback) {
      callback(false);
    }
  }
};

/**
 * Create main frame
 * @private
 */
treemode._createFrame = function () {
  // create the frame
  this.frame = document.createElement('div');
  this.frame.className = 'jsoneditor jsoneditor-mode-' + this.options.mode;
  this.container.appendChild(this.frame);

  // create one global event listener to handle all events from all nodes
  var editor = this;
  function onEvent(event) {
    // when switching to mode "code" or "text" via the menu, some events
    // are still fired whilst the _onEvent methods is already removed.
    if (editor._onEvent) {
      editor._onEvent(event);
    }
  }
  this.frame.onclick = function (event) {
    var target = event.target;// || event.srcElement;

    onEvent(event);

    // prevent default submit action of buttons when editor is located
    // inside a form
    if (target.nodeName == 'BUTTON') {
      event.preventDefault();
    }
  };
  this.frame.oninput = onEvent;
  this.frame.onchange = onEvent;
  this.frame.onkeydown = onEvent;
  this.frame.onkeyup = onEvent;
  this.frame.oncut = onEvent;
  this.frame.onpaste = onEvent;
  this.frame.onmousedown = onEvent;
  this.frame.onmouseup = onEvent;
  this.frame.onmouseover = onEvent;
  this.frame.onmouseout = onEvent;
  // Note: focus and blur events do not propagate, therefore they defined
  // using an eventListener with useCapture=true
  // see http://www.quirksmode.org/blog/archives/2008/04/delegating_the.html
  util.addEventListener(this.frame, 'focus', onEvent, true);
  util.addEventListener(this.frame, 'blur', onEvent, true);
  this.frame.onfocusin = onEvent;  // for IE
  this.frame.onfocusout = onEvent; // for IE

  // create menu
  this.menu = document.createElement('div');
  this.menu.className = 'jsoneditor-menu';
  this.frame.appendChild(this.menu);

  // create expand all button
  var expandAll = document.createElement('button');
  expandAll.type = 'button';
  expandAll.className = 'jsoneditor-expand-all';
  expandAll.title = 'Expand all fields';
  expandAll.onclick = function () {
    editor.expandAll();
  };
  this.menu.appendChild(expandAll);

  // create expand all button
  var collapseAll = document.createElement('button');
  collapseAll.type = 'button';
  collapseAll.title = 'Collapse all fields';
  collapseAll.className = 'jsoneditor-collapse-all';
  collapseAll.onclick = function () {
    editor.collapseAll();
  };
  this.menu.appendChild(collapseAll);

  // create undo/redo buttons
  if (this.history) {
    // create undo button
    var undo = document.createElement('button');
    undo.type = 'button';
    undo.className = 'jsoneditor-undo jsoneditor-separator';
    undo.title = 'Undo last action (Ctrl+Z)';
    undo.onclick = function () {
      editor._onUndo();
    };
    this.menu.appendChild(undo);
    this.dom.undo = undo;

    // create redo button
    var redo = document.createElement('button');
    redo.type = 'button';
    redo.className = 'jsoneditor-redo';
    redo.title = 'Redo (Ctrl+Shift+Z)';
    redo.onclick = function () {
      editor._onRedo();
    };
    this.menu.appendChild(redo);
    this.dom.redo = redo;

    // register handler for onchange of history
    this.history.onChange = function () {
      undo.disabled = !editor.history.canUndo();
      redo.disabled = !editor.history.canRedo();
    };
    this.history.onChange();
  }

  // create mode box
  if (this.options && this.options.modes && this.options.modes.length) {
    var me = this;
    this.modeSwitcher = new ModeSwitcher(this.menu, this.options.modes, this.options.mode, function onSwitch(mode) {
      me.modeSwitcher.destroy();

      // switch mode and restore focus
      me.setMode(mode);
      me.modeSwitcher.focus();
    });
  }

  // create search box
  if (this.options.search) {
    this.searchBox = new SearchBox(this, this.menu);
  }
};

/**
 * Perform an undo action
 * @private
 */
treemode._onUndo = function () {
  if (this.history) {
    // undo last action
    this.history.undo();

    // fire change event
    this._onChange();
  }
};

/**
 * Perform a redo action
 * @private
 */
treemode._onRedo = function () {
  if (this.history) {
    // redo last action
    this.history.redo();

    // fire change event
    this._onChange();
  }
};

/**
 * Event handler
 * @param event
 * @private
 */
treemode._onEvent = function (event) {
  if (event.type == 'keydown') {
    this._onKeyDown(event);
  }

  if (event.type == 'focus') {
    this.focusTarget = event.target;
  }

  if (event.type == 'mousedown') {
    this._startDragDistance(event);
  }
  if (event.type == 'mousemove' || event.type == 'mouseup' || event.type == 'click') {
    this._updateDragDistance(event);
  }

  var node = Node.getNodeFromTarget(event.target);

  if (node && node.selected) {
    if (event.type == 'click') {
      if (event.target == node.dom.menu) {
        this.showContextMenu(event.target);

        // stop propagation (else we will open the context menu of a single node)
        return;
      }

      // deselect a multi selection
      if (!event.hasMoved) {
        this.deselect();
      }
    }

    if (event.type == 'mousedown') {
      // drag multiple nodes
      Node.onDragStart(this.multiselection.nodes, event);
    }
  }
  else {
    if (event.type == 'mousedown') {
      this.deselect();

      if (node && event.target == node.dom.drag) {
        // drag a singe node
        Node.onDragStart(node, event);
      }
      else if (!node || (event.target != node.dom.field && event.target != node.dom.value && event.target != node.dom.select)) {
        // select multiple nodes
        this._onMultiSelectStart(event);
      }
    }
  }

  if (node) {
    node.onEvent(event);
  }
};

treemode._startDragDistance = function (event) {
  this.dragDistanceEvent = {
    initialTarget: event.target,
    initialPageX: event.pageX,
    initialPageY: event.pageY,
    dragDistance: 0,
    hasMoved: false
  };
};

treemode._updateDragDistance = function (event) {
  if (!this.dragDistanceEvent) {
    this._startDragDistance(event);
  }

  var diffX = event.pageX - this.dragDistanceEvent.initialPageX;
  var diffY = event.pageY - this.dragDistanceEvent.initialPageY;

  this.dragDistanceEvent.dragDistance = Math.sqrt(diffX * diffX + diffY * diffY);
  this.dragDistanceEvent.hasMoved =
      this.dragDistanceEvent.hasMoved || this.dragDistanceEvent.dragDistance > 10;

  event.dragDistance = this.dragDistanceEvent.dragDistance;
  event.hasMoved = this.dragDistanceEvent.hasMoved;

  return event.dragDistance;
};

/**
 * Start multi selection of nodes by dragging the mouse
 * @param event
 * @private
 */
treemode._onMultiSelectStart = function (event) {
  var node = Node.getNodeFromTarget(event.target);

  if (this.options.mode !== 'tree' || this.options.onEditable !== undefined) {
    // dragging not allowed in modes 'view' and 'form'
    // TODO: allow multiselection of items when option onEditable is specified
    return;
  }

  this.multiselection = {
    start: node || null,
    end: null,
    nodes: []
  };

  this._startDragDistance(event);

  var editor = this;
  if (!this.mousemove) {
    this.mousemove = util.addEventListener(window, 'mousemove', function (event) {
      editor._onMultiSelect(event);
    });
  }
  if (!this.mouseup) {
    this.mouseup = util.addEventListener(window, 'mouseup', function (event ) {
      editor._onMultiSelectEnd(event);
    });
  }

};

/**
 * Multiselect nodes by dragging
 * @param event
 * @private
 */
treemode._onMultiSelect = function (event) {
  event.preventDefault();

  this._updateDragDistance(event);
  if (!event.hasMoved) {
    return;
  }

  var node = Node.getNodeFromTarget(event.target);

  if (node) {
    if (this.multiselection.start == null) {
      this.multiselection.start = node;
    }
    this.multiselection.end = node;
  }

  // deselect previous selection
  this.deselect();

  // find the selected nodes in the range from first to last
  var start = this.multiselection.start;
  var end = this.multiselection.end || this.multiselection.start;
  if (start && end) {
    // find the top level childs, all having the same parent
    this.multiselection.nodes = this._findTopLevelNodes(start, end);
    this.select(this.multiselection.nodes);
  }
};

/**
 * End of multiselect nodes by dragging
 * @param event
 * @private
 */
treemode._onMultiSelectEnd = function (event) {
  // set focus to the context menu button of the first node
  if (this.multiselection.nodes[0]) {
    this.multiselection.nodes[0].dom.menu.focus();
  }

  this.multiselection.start = null;
  this.multiselection.end = null;

  // cleanup global event listeners
  if (this.mousemove) {
    util.removeEventListener(window, 'mousemove', this.mousemove);
    delete this.mousemove;
  }
  if (this.mouseup) {
    util.removeEventListener(window, 'mouseup', this.mouseup);
    delete this.mouseup;
  }
};

/**
 * deselect currently selected nodes
 * @param {boolean} [clearStartAndEnd=false]  If true, the `start` and `end`
 *                                            state is cleared too.
 */
treemode.deselect = function (clearStartAndEnd) {
  this.multiselection.nodes.forEach(function (node) {
    node.setSelected(false);
  });
  this.multiselection.nodes = [];

  if (clearStartAndEnd) {
    this.multiselection.start = null;
    this.multiselection.end = null;
  }
};

/**
 * select nodes
 * @param {Node[] | Node} nodes
 */
treemode.select = function (nodes) {
  if (!Array.isArray(nodes)) {
    return this.select([nodes]);
  }

  if (nodes) {
    this.deselect();

    this.multiselection.nodes = nodes.slice(0);

    var first = nodes[0];
    nodes.forEach(function (node) {
      node.setSelected(true, node === first);
    });
  }
};

/**
 * From two arbitrary selected nodes, find their shared parent node.
 * From that parent node, select the two child nodes in the brances going to
 * nodes `start` and `end`, and select all childs in between.
 * @param {Node} start
 * @param {Node} end
 * @return {Array.<Node>} Returns an ordered list with child nodes
 * @private
 */
treemode._findTopLevelNodes = function (start, end) {
  var startPath = start.getNodePath();
  var endPath = end.getNodePath();
  var i = 0;
  while (i < startPath.length && startPath[i] === endPath[i]) {
    i++;
  }
  var root = startPath[i - 1];
  var startChild = startPath[i];
  var endChild = endPath[i];

  if (!startChild || !endChild) {
    if (root.parent) {
      // startChild is a parent of endChild or vice versa
      startChild = root;
      endChild = root;
      root = root.parent
    }
    else {
      // we have selected the root node (which doesn't have a parent)
      startChild = root.childs[0];
      endChild = root.childs[root.childs.length - 1];
    }
  }

  if (root && startChild && endChild) {
    var startIndex = root.childs.indexOf(startChild);
    var endIndex = root.childs.indexOf(endChild);
    var firstIndex = Math.min(startIndex, endIndex);
    var lastIndex = Math.max(startIndex, endIndex);

    return root.childs.slice(firstIndex, lastIndex + 1);
  }
  else {
    return [];
  }
};

/**
 * Event handler for keydown. Handles shortcut keys
 * @param {Event} event
 * @private
 */
treemode._onKeyDown = function (event) {
  var keynum = event.which || event.keyCode;
  var ctrlKey = event.ctrlKey;
  var shiftKey = event.shiftKey;
  var handled = false;

  if (keynum == 9) { // Tab or Shift+Tab
    var me = this;
    setTimeout(function () {
      // select all text when moving focus to an editable div
      util.selectContentEditable(me.focusTarget);
    }, 0);
  }

  if (this.searchBox) {
    if (ctrlKey && keynum == 70) { // Ctrl+F
      this.searchBox.dom.search.focus();
      this.searchBox.dom.search.select();
      handled = true;
    }
    else if (keynum == 114 || (ctrlKey && keynum == 71)) { // F3 or Ctrl+G
      var focus = true;
      if (!shiftKey) {
        // select next search result (F3 or Ctrl+G)
        this.searchBox.next(focus);
      }
      else {
        // select previous search result (Shift+F3 or Ctrl+Shift+G)
        this.searchBox.previous(focus);
      }

      handled = true;
    }
  }

  if (this.history) {
    if (ctrlKey && !shiftKey && keynum == 90) { // Ctrl+Z
      // undo
      this._onUndo();
      handled = true;
    }
    else if (ctrlKey && shiftKey && keynum == 90) { // Ctrl+Shift+Z
      // redo
      this._onRedo();
      handled = true;
    }
  }

  if (handled) {
    event.preventDefault();
    event.stopPropagation();
  }
};

/**
 * Create main table
 * @private
 */
treemode._createTable = function () {
  var contentOuter = document.createElement('div');
  contentOuter.className = 'jsoneditor-outer';
  this.contentOuter = contentOuter;

  this.content = document.createElement('div');
  this.content.className = 'jsoneditor-tree';
  contentOuter.appendChild(this.content);

  this.table = document.createElement('table');
  this.table.className = 'jsoneditor-tree';
  this.content.appendChild(this.table);

  // create colgroup where the first two columns don't have a fixed
  // width, and the edit columns do have a fixed width
  var col;
  this.colgroupContent = document.createElement('colgroup');
  if (this.options.mode === 'tree') {
    col = document.createElement('col');
    col.width = "24px";
    this.colgroupContent.appendChild(col);
  }
  col = document.createElement('col');
  col.width = "24px";
  this.colgroupContent.appendChild(col);
  col = document.createElement('col');
  this.colgroupContent.appendChild(col);
  this.table.appendChild(this.colgroupContent);

  this.tbody = document.createElement('tbody');
  this.table.appendChild(this.tbody);

  this.frame.appendChild(contentOuter);
};

/**
 * Show a contextmenu for this node.
 * Used for multiselection
 * @param {HTMLElement} anchor   Anchor element to attache the context menu to.
 * @param {function} [onClose]   Callback method called when the context menu
 *                               is being closed.
 */
treemode.showContextMenu = function (anchor, onClose) {
  var items = [];
  var editor = this;

  // create duplicate button
  items.push({
    text: 'Duplicate',
    title: 'Duplicate selected fields (Ctrl+D)',
    className: 'jsoneditor-duplicate',
    click: function () {
      Node.onDuplicate(editor.multiselection.nodes);
    }
  });

  // create remove button
  items.push({
    text: 'Remove',
    title: 'Remove selected fields (Ctrl+Del)',
    className: 'jsoneditor-remove',
    click: function () {
      Node.onRemove(editor.multiselection.nodes);
    }
  });

  var menu = new ContextMenu(items, {close: onClose});
  menu.show(anchor, this.content);
};


// define modes
module.exports = [
  {
    mode: 'tree',
    mixin: treemode,
    data: 'json'
  },
  {
    mode: 'view',
    mixin: treemode,
    data: 'json'
  },
  {
    mode: 'form',
    mixin: treemode,
    data: 'json'
  }
];


/***/ }),
/* 107 */
/***/ (function(module, exports, __webpack_require__) {

exports.parse = __webpack_require__(108);
exports.stringify = __webpack_require__(109);


/***/ }),
/* 108 */
/***/ (function(module, exports) {

var at, // The index of the current character
    ch, // The current character
    escapee = {
        '"':  '"',
        '\\': '\\',
        '/':  '/',
        b:    '\b',
        f:    '\f',
        n:    '\n',
        r:    '\r',
        t:    '\t'
    },
    text,

    error = function (m) {
        // Call error when something is wrong.
        throw {
            name:    'SyntaxError',
            message: m,
            at:      at,
            text:    text
        };
    },
    
    next = function (c) {
        // If a c parameter is provided, verify that it matches the current character.
        if (c && c !== ch) {
            error("Expected '" + c + "' instead of '" + ch + "'");
        }
        
        // Get the next character. When there are no more characters,
        // return the empty string.
        
        ch = text.charAt(at);
        at += 1;
        return ch;
    },
    
    number = function () {
        // Parse a number value.
        var number,
            string = '';
        
        if (ch === '-') {
            string = '-';
            next('-');
        }
        while (ch >= '0' && ch <= '9') {
            string += ch;
            next();
        }
        if (ch === '.') {
            string += '.';
            while (next() && ch >= '0' && ch <= '9') {
                string += ch;
            }
        }
        if (ch === 'e' || ch === 'E') {
            string += ch;
            next();
            if (ch === '-' || ch === '+') {
                string += ch;
                next();
            }
            while (ch >= '0' && ch <= '9') {
                string += ch;
                next();
            }
        }
        number = +string;
        if (!isFinite(number)) {
            error("Bad number");
        } else {
            return number;
        }
    },
    
    string = function () {
        // Parse a string value.
        var hex,
            i,
            string = '',
            uffff;
        
        // When parsing for string values, we must look for " and \ characters.
        if (ch === '"') {
            while (next()) {
                if (ch === '"') {
                    next();
                    return string;
                } else if (ch === '\\') {
                    next();
                    if (ch === 'u') {
                        uffff = 0;
                        for (i = 0; i < 4; i += 1) {
                            hex = parseInt(next(), 16);
                            if (!isFinite(hex)) {
                                break;
                            }
                            uffff = uffff * 16 + hex;
                        }
                        string += String.fromCharCode(uffff);
                    } else if (typeof escapee[ch] === 'string') {
                        string += escapee[ch];
                    } else {
                        break;
                    }
                } else {
                    string += ch;
                }
            }
        }
        error("Bad string");
    },

    white = function () {

// Skip whitespace.

        while (ch && ch <= ' ') {
            next();
        }
    },

    word = function () {

// true, false, or null.

        switch (ch) {
        case 't':
            next('t');
            next('r');
            next('u');
            next('e');
            return true;
        case 'f':
            next('f');
            next('a');
            next('l');
            next('s');
            next('e');
            return false;
        case 'n':
            next('n');
            next('u');
            next('l');
            next('l');
            return null;
        }
        error("Unexpected '" + ch + "'");
    },

    value,  // Place holder for the value function.

    array = function () {

// Parse an array value.

        var array = [];

        if (ch === '[') {
            next('[');
            white();
            if (ch === ']') {
                next(']');
                return array;   // empty array
            }
            while (ch) {
                array.push(value());
                white();
                if (ch === ']') {
                    next(']');
                    return array;
                }
                next(',');
                white();
            }
        }
        error("Bad array");
    },

    object = function () {

// Parse an object value.

        var key,
            object = {};

        if (ch === '{') {
            next('{');
            white();
            if (ch === '}') {
                next('}');
                return object;   // empty object
            }
            while (ch) {
                key = string();
                white();
                next(':');
                if (Object.hasOwnProperty.call(object, key)) {
                    error('Duplicate key "' + key + '"');
                }
                object[key] = value();
                white();
                if (ch === '}') {
                    next('}');
                    return object;
                }
                next(',');
                white();
            }
        }
        error("Bad object");
    };

value = function () {

// Parse a JSON value. It could be an object, an array, a string, a number,
// or a word.

    white();
    switch (ch) {
    case '{':
        return object();
    case '[':
        return array();
    case '"':
        return string();
    case '-':
        return number();
    default:
        return ch >= '0' && ch <= '9' ? number() : word();
    }
};

// Return the json_parse function. It will have access to all of the above
// functions and variables.

module.exports = function (source, reviver) {
    var result;
    
    text = source;
    at = 0;
    ch = ' ';
    result = value();
    white();
    if (ch) {
        error("Syntax error");
    }

    // If there is a reviver function, we recursively walk the new structure,
    // passing each name/value pair to the reviver function for possible
    // transformation, starting with a temporary root object that holds the result
    // in an empty key. If there is not a reviver function, we simply return the
    // result.

    return typeof reviver === 'function' ? (function walk(holder, key) {
        var k, v, value = holder[key];
        if (value && typeof value === 'object') {
            for (k in value) {
                if (Object.prototype.hasOwnProperty.call(value, k)) {
                    v = walk(value, k);
                    if (v !== undefined) {
                        value[k] = v;
                    } else {
                        delete value[k];
                    }
                }
            }
        }
        return reviver.call(holder, key, value);
    }({'': result}, '')) : result;
};


/***/ }),
/* 109 */
/***/ (function(module, exports) {

var cx = /[\u0000\u00ad\u0600-\u0604\u070f\u17b4\u17b5\u200c-\u200f\u2028-\u202f\u2060-\u206f\ufeff\ufff0-\uffff]/g,
    escapable = /[\\\"\x00-\x1f\x7f-\x9f\u00ad\u0600-\u0604\u070f\u17b4\u17b5\u200c-\u200f\u2028-\u202f\u2060-\u206f\ufeff\ufff0-\uffff]/g,
    gap,
    indent,
    meta = {    // table of character substitutions
        '\b': '\\b',
        '\t': '\\t',
        '\n': '\\n',
        '\f': '\\f',
        '\r': '\\r',
        '"' : '\\"',
        '\\': '\\\\'
    },
    rep;

function quote(string) {
    // If the string contains no control characters, no quote characters, and no
    // backslash characters, then we can safely slap some quotes around it.
    // Otherwise we must also replace the offending characters with safe escape
    // sequences.
    
    escapable.lastIndex = 0;
    return escapable.test(string) ? '"' + string.replace(escapable, function (a) {
        var c = meta[a];
        return typeof c === 'string' ? c :
            '\\u' + ('0000' + a.charCodeAt(0).toString(16)).slice(-4);
    }) + '"' : '"' + string + '"';
}

function str(key, holder) {
    // Produce a string from holder[key].
    var i,          // The loop counter.
        k,          // The member key.
        v,          // The member value.
        length,
        mind = gap,
        partial,
        value = holder[key];
    
    // If the value has a toJSON method, call it to obtain a replacement value.
    if (value && typeof value === 'object' &&
            typeof value.toJSON === 'function') {
        value = value.toJSON(key);
    }
    
    // If we were called with a replacer function, then call the replacer to
    // obtain a replacement value.
    if (typeof rep === 'function') {
        value = rep.call(holder, key, value);
    }
    
    // What happens next depends on the value's type.
    switch (typeof value) {
        case 'string':
            return quote(value);
        
        case 'number':
            // JSON numbers must be finite. Encode non-finite numbers as null.
            return isFinite(value) ? String(value) : 'null';
        
        case 'boolean':
        case 'null':
            // If the value is a boolean or null, convert it to a string. Note:
            // typeof null does not produce 'null'. The case is included here in
            // the remote chance that this gets fixed someday.
            return String(value);
            
        case 'object':
            if (!value) return 'null';
            gap += indent;
            partial = [];
            
            // Array.isArray
            if (Object.prototype.toString.apply(value) === '[object Array]') {
                length = value.length;
                for (i = 0; i < length; i += 1) {
                    partial[i] = str(i, value) || 'null';
                }
                
                // Join all of the elements together, separated with commas, and
                // wrap them in brackets.
                v = partial.length === 0 ? '[]' : gap ?
                    '[\n' + gap + partial.join(',\n' + gap) + '\n' + mind + ']' :
                    '[' + partial.join(',') + ']';
                gap = mind;
                return v;
            }
            
            // If the replacer is an array, use it to select the members to be
            // stringified.
            if (rep && typeof rep === 'object') {
                length = rep.length;
                for (i = 0; i < length; i += 1) {
                    k = rep[i];
                    if (typeof k === 'string') {
                        v = str(k, value);
                        if (v) {
                            partial.push(quote(k) + (gap ? ': ' : ':') + v);
                        }
                    }
                }
            }
            else {
                // Otherwise, iterate through all of the keys in the object.
                for (k in value) {
                    if (Object.prototype.hasOwnProperty.call(value, k)) {
                        v = str(k, value);
                        if (v) {
                            partial.push(quote(k) + (gap ? ': ' : ':') + v);
                        }
                    }
                }
            }
            
        // Join all of the member texts together, separated with commas,
        // and wrap them in braces.

        v = partial.length === 0 ? '{}' : gap ?
            '{\n' + gap + partial.join(',\n' + gap) + '\n' + mind + '}' :
            '{' + partial.join(',') + '}';
        gap = mind;
        return v;
    }
}

module.exports = function (value, replacer, space) {
    var i;
    gap = '';
    indent = '';
    
    // If the space parameter is a number, make an indent string containing that
    // many spaces.
    if (typeof space === 'number') {
        for (i = 0; i < space; i += 1) {
            indent += ' ';
        }
    }
    // If the space parameter is a string, it will be used as the indent string.
    else if (typeof space === 'string') {
        indent = space;
    }

    // If there is a replacer, it must be a function or an array.
    // Otherwise, throw an error.
    rep = replacer;
    if (replacer && typeof replacer !== 'function'
    && (typeof replacer !== 'object' || typeof replacer.length !== 'number')) {
        throw new Error('JSON.stringify');
    }
    
    // Make a fake root object containing our value under the key of ''.
    // Return the result of stringifying the value.
    return str('', {'': value});
};


/***/ }),
/* 110 */,
/* 111 */,
/* 112 */
/***/ (function(module, exports, __webpack_require__) {

/* WEBPACK VAR INJECTION */(function(module, global) {var __WEBPACK_AMD_DEFINE_RESULT__;/*! https://mths.be/punycode v1.4.1 by @mathias */
;(function(root) {

	/** Detect free variables */
	var freeExports = typeof exports == 'object' && exports &&
		!exports.nodeType && exports;
	var freeModule = typeof module == 'object' && module &&
		!module.nodeType && module;
	var freeGlobal = typeof global == 'object' && global;
	if (
		freeGlobal.global === freeGlobal ||
		freeGlobal.window === freeGlobal ||
		freeGlobal.self === freeGlobal
	) {
		root = freeGlobal;
	}

	/**
	 * The `punycode` object.
	 * @name punycode
	 * @type Object
	 */
	var punycode,

	/** Highest positive signed 32-bit float value */
	maxInt = 2147483647, // aka. 0x7FFFFFFF or 2^31-1

	/** Bootstring parameters */
	base = 36,
	tMin = 1,
	tMax = 26,
	skew = 38,
	damp = 700,
	initialBias = 72,
	initialN = 128, // 0x80
	delimiter = '-', // '\x2D'

	/** Regular expressions */
	regexPunycode = /^xn--/,
	regexNonASCII = /[^\x20-\x7E]/, // unprintable ASCII chars + non-ASCII chars
	regexSeparators = /[\x2E\u3002\uFF0E\uFF61]/g, // RFC 3490 separators

	/** Error messages */
	errors = {
		'overflow': 'Overflow: input needs wider integers to process',
		'not-basic': 'Illegal input >= 0x80 (not a basic code point)',
		'invalid-input': 'Invalid input'
	},

	/** Convenience shortcuts */
	baseMinusTMin = base - tMin,
	floor = Math.floor,
	stringFromCharCode = String.fromCharCode,

	/** Temporary variable */
	key;

	/*--------------------------------------------------------------------------*/

	/**
	 * A generic error utility function.
	 * @private
	 * @param {String} type The error type.
	 * @returns {Error} Throws a `RangeError` with the applicable error message.
	 */
	function error(type) {
		throw new RangeError(errors[type]);
	}

	/**
	 * A generic `Array#map` utility function.
	 * @private
	 * @param {Array} array The array to iterate over.
	 * @param {Function} callback The function that gets called for every array
	 * item.
	 * @returns {Array} A new array of values returned by the callback function.
	 */
	function map(array, fn) {
		var length = array.length;
		var result = [];
		while (length--) {
			result[length] = fn(array[length]);
		}
		return result;
	}

	/**
	 * A simple `Array#map`-like wrapper to work with domain name strings or email
	 * addresses.
	 * @private
	 * @param {String} domain The domain name or email address.
	 * @param {Function} callback The function that gets called for every
	 * character.
	 * @returns {Array} A new string of characters returned by the callback
	 * function.
	 */
	function mapDomain(string, fn) {
		var parts = string.split('@');
		var result = '';
		if (parts.length > 1) {
			// In email addresses, only the domain name should be punycoded. Leave
			// the local part (i.e. everything up to `@`) intact.
			result = parts[0] + '@';
			string = parts[1];
		}
		// Avoid `split(regex)` for IE8 compatibility. See #17.
		string = string.replace(regexSeparators, '\x2E');
		var labels = string.split('.');
		var encoded = map(labels, fn).join('.');
		return result + encoded;
	}

	/**
	 * Creates an array containing the numeric code points of each Unicode
	 * character in the string. While JavaScript uses UCS-2 internally,
	 * this function will convert a pair of surrogate halves (each of which
	 * UCS-2 exposes as separate characters) into a single code point,
	 * matching UTF-16.
	 * @see `punycode.ucs2.encode`
	 * @see <https://mathiasbynens.be/notes/javascript-encoding>
	 * @memberOf punycode.ucs2
	 * @name decode
	 * @param {String} string The Unicode input string (UCS-2).
	 * @returns {Array} The new array of code points.
	 */
	function ucs2decode(string) {
		var output = [],
		    counter = 0,
		    length = string.length,
		    value,
		    extra;
		while (counter < length) {
			value = string.charCodeAt(counter++);
			if (value >= 0xD800 && value <= 0xDBFF && counter < length) {
				// high surrogate, and there is a next character
				extra = string.charCodeAt(counter++);
				if ((extra & 0xFC00) == 0xDC00) { // low surrogate
					output.push(((value & 0x3FF) << 10) + (extra & 0x3FF) + 0x10000);
				} else {
					// unmatched surrogate; only append this code unit, in case the next
					// code unit is the high surrogate of a surrogate pair
					output.push(value);
					counter--;
				}
			} else {
				output.push(value);
			}
		}
		return output;
	}

	/**
	 * Creates a string based on an array of numeric code points.
	 * @see `punycode.ucs2.decode`
	 * @memberOf punycode.ucs2
	 * @name encode
	 * @param {Array} codePoints The array of numeric code points.
	 * @returns {String} The new Unicode string (UCS-2).
	 */
	function ucs2encode(array) {
		return map(array, function(value) {
			var output = '';
			if (value > 0xFFFF) {
				value -= 0x10000;
				output += stringFromCharCode(value >>> 10 & 0x3FF | 0xD800);
				value = 0xDC00 | value & 0x3FF;
			}
			output += stringFromCharCode(value);
			return output;
		}).join('');
	}

	/**
	 * Converts a basic code point into a digit/integer.
	 * @see `digitToBasic()`
	 * @private
	 * @param {Number} codePoint The basic numeric code point value.
	 * @returns {Number} The numeric value of a basic code point (for use in
	 * representing integers) in the range `0` to `base - 1`, or `base` if
	 * the code point does not represent a value.
	 */
	function basicToDigit(codePoint) {
		if (codePoint - 48 < 10) {
			return codePoint - 22;
		}
		if (codePoint - 65 < 26) {
			return codePoint - 65;
		}
		if (codePoint - 97 < 26) {
			return codePoint - 97;
		}
		return base;
	}

	/**
	 * Converts a digit/integer into a basic code point.
	 * @see `basicToDigit()`
	 * @private
	 * @param {Number} digit The numeric value of a basic code point.
	 * @returns {Number} The basic code point whose value (when used for
	 * representing integers) is `digit`, which needs to be in the range
	 * `0` to `base - 1`. If `flag` is non-zero, the uppercase form is
	 * used; else, the lowercase form is used. The behavior is undefined
	 * if `flag` is non-zero and `digit` has no uppercase form.
	 */
	function digitToBasic(digit, flag) {
		//  0..25 map to ASCII a..z or A..Z
		// 26..35 map to ASCII 0..9
		return digit + 22 + 75 * (digit < 26) - ((flag != 0) << 5);
	}

	/**
	 * Bias adaptation function as per section 3.4 of RFC 3492.
	 * https://tools.ietf.org/html/rfc3492#section-3.4
	 * @private
	 */
	function adapt(delta, numPoints, firstTime) {
		var k = 0;
		delta = firstTime ? floor(delta / damp) : delta >> 1;
		delta += floor(delta / numPoints);
		for (/* no initialization */; delta > baseMinusTMin * tMax >> 1; k += base) {
			delta = floor(delta / baseMinusTMin);
		}
		return floor(k + (baseMinusTMin + 1) * delta / (delta + skew));
	}

	/**
	 * Converts a Punycode string of ASCII-only symbols to a string of Unicode
	 * symbols.
	 * @memberOf punycode
	 * @param {String} input The Punycode string of ASCII-only symbols.
	 * @returns {String} The resulting string of Unicode symbols.
	 */
	function decode(input) {
		// Don't use UCS-2
		var output = [],
		    inputLength = input.length,
		    out,
		    i = 0,
		    n = initialN,
		    bias = initialBias,
		    basic,
		    j,
		    index,
		    oldi,
		    w,
		    k,
		    digit,
		    t,
		    /** Cached calculation results */
		    baseMinusT;

		// Handle the basic code points: let `basic` be the number of input code
		// points before the last delimiter, or `0` if there is none, then copy
		// the first basic code points to the output.

		basic = input.lastIndexOf(delimiter);
		if (basic < 0) {
			basic = 0;
		}

		for (j = 0; j < basic; ++j) {
			// if it's not a basic code point
			if (input.charCodeAt(j) >= 0x80) {
				error('not-basic');
			}
			output.push(input.charCodeAt(j));
		}

		// Main decoding loop: start just after the last delimiter if any basic code
		// points were copied; start at the beginning otherwise.

		for (index = basic > 0 ? basic + 1 : 0; index < inputLength; /* no final expression */) {

			// `index` is the index of the next character to be consumed.
			// Decode a generalized variable-length integer into `delta`,
			// which gets added to `i`. The overflow checking is easier
			// if we increase `i` as we go, then subtract off its starting
			// value at the end to obtain `delta`.
			for (oldi = i, w = 1, k = base; /* no condition */; k += base) {

				if (index >= inputLength) {
					error('invalid-input');
				}

				digit = basicToDigit(input.charCodeAt(index++));

				if (digit >= base || digit > floor((maxInt - i) / w)) {
					error('overflow');
				}

				i += digit * w;
				t = k <= bias ? tMin : (k >= bias + tMax ? tMax : k - bias);

				if (digit < t) {
					break;
				}

				baseMinusT = base - t;
				if (w > floor(maxInt / baseMinusT)) {
					error('overflow');
				}

				w *= baseMinusT;

			}

			out = output.length + 1;
			bias = adapt(i - oldi, out, oldi == 0);

			// `i` was supposed to wrap around from `out` to `0`,
			// incrementing `n` each time, so we'll fix that now:
			if (floor(i / out) > maxInt - n) {
				error('overflow');
			}

			n += floor(i / out);
			i %= out;

			// Insert `n` at position `i` of the output
			output.splice(i++, 0, n);

		}

		return ucs2encode(output);
	}

	/**
	 * Converts a string of Unicode symbols (e.g. a domain name label) to a
	 * Punycode string of ASCII-only symbols.
	 * @memberOf punycode
	 * @param {String} input The string of Unicode symbols.
	 * @returns {String} The resulting Punycode string of ASCII-only symbols.
	 */
	function encode(input) {
		var n,
		    delta,
		    handledCPCount,
		    basicLength,
		    bias,
		    j,
		    m,
		    q,
		    k,
		    t,
		    currentValue,
		    output = [],
		    /** `inputLength` will hold the number of code points in `input`. */
		    inputLength,
		    /** Cached calculation results */
		    handledCPCountPlusOne,
		    baseMinusT,
		    qMinusT;

		// Convert the input in UCS-2 to Unicode
		input = ucs2decode(input);

		// Cache the length
		inputLength = input.length;

		// Initialize the state
		n = initialN;
		delta = 0;
		bias = initialBias;

		// Handle the basic code points
		for (j = 0; j < inputLength; ++j) {
			currentValue = input[j];
			if (currentValue < 0x80) {
				output.push(stringFromCharCode(currentValue));
			}
		}

		handledCPCount = basicLength = output.length;

		// `handledCPCount` is the number of code points that have been handled;
		// `basicLength` is the number of basic code points.

		// Finish the basic string - if it is not empty - with a delimiter
		if (basicLength) {
			output.push(delimiter);
		}

		// Main encoding loop:
		while (handledCPCount < inputLength) {

			// All non-basic code points < n have been handled already. Find the next
			// larger one:
			for (m = maxInt, j = 0; j < inputLength; ++j) {
				currentValue = input[j];
				if (currentValue >= n && currentValue < m) {
					m = currentValue;
				}
			}

			// Increase `delta` enough to advance the decoder's <n,i> state to <m,0>,
			// but guard against overflow
			handledCPCountPlusOne = handledCPCount + 1;
			if (m - n > floor((maxInt - delta) / handledCPCountPlusOne)) {
				error('overflow');
			}

			delta += (m - n) * handledCPCountPlusOne;
			n = m;

			for (j = 0; j < inputLength; ++j) {
				currentValue = input[j];

				if (currentValue < n && ++delta > maxInt) {
					error('overflow');
				}

				if (currentValue == n) {
					// Represent delta as a generalized variable-length integer
					for (q = delta, k = base; /* no condition */; k += base) {
						t = k <= bias ? tMin : (k >= bias + tMax ? tMax : k - bias);
						if (q < t) {
							break;
						}
						qMinusT = q - t;
						baseMinusT = base - t;
						output.push(
							stringFromCharCode(digitToBasic(t + qMinusT % baseMinusT, 0))
						);
						q = floor(qMinusT / baseMinusT);
					}

					output.push(stringFromCharCode(digitToBasic(q, 0)));
					bias = adapt(delta, handledCPCountPlusOne, handledCPCount == basicLength);
					delta = 0;
					++handledCPCount;
				}
			}

			++delta;
			++n;

		}
		return output.join('');
	}

	/**
	 * Converts a Punycode string representing a domain name or an email address
	 * to Unicode. Only the Punycoded parts of the input will be converted, i.e.
	 * it doesn't matter if you call it on a string that has already been
	 * converted to Unicode.
	 * @memberOf punycode
	 * @param {String} input The Punycoded domain name or email address to
	 * convert to Unicode.
	 * @returns {String} The Unicode representation of the given Punycode
	 * string.
	 */
	function toUnicode(input) {
		return mapDomain(input, function(string) {
			return regexPunycode.test(string)
				? decode(string.slice(4).toLowerCase())
				: string;
		});
	}

	/**
	 * Converts a Unicode string representing a domain name or an email address to
	 * Punycode. Only the non-ASCII parts of the domain name will be converted,
	 * i.e. it doesn't matter if you call it with a domain that's already in
	 * ASCII.
	 * @memberOf punycode
	 * @param {String} input The domain name or email address to convert, as a
	 * Unicode string.
	 * @returns {String} The Punycode representation of the given domain name or
	 * email address.
	 */
	function toASCII(input) {
		return mapDomain(input, function(string) {
			return regexNonASCII.test(string)
				? 'xn--' + encode(string)
				: string;
		});
	}

	/*--------------------------------------------------------------------------*/

	/** Define the public API */
	punycode = {
		/**
		 * A string representing the current Punycode.js version number.
		 * @memberOf punycode
		 * @type String
		 */
		'version': '1.4.1',
		/**
		 * An object of methods to convert from JavaScript's internal character
		 * representation (UCS-2) to Unicode code points, and back.
		 * @see <https://mathiasbynens.be/notes/javascript-encoding>
		 * @memberOf punycode
		 * @type Object
		 */
		'ucs2': {
			'decode': ucs2decode,
			'encode': ucs2encode
		},
		'decode': decode,
		'encode': encode,
		'toASCII': toASCII,
		'toUnicode': toUnicode
	};

	/** Expose `punycode` */
	// Some AMD build optimizers, like r.js, check for specific condition patterns
	// like the following:
	if (
		true
	) {
		!(__WEBPACK_AMD_DEFINE_RESULT__ = function() {
			return punycode;
		}.call(exports, __webpack_require__, exports, module),
				__WEBPACK_AMD_DEFINE_RESULT__ !== undefined && (module.exports = __WEBPACK_AMD_DEFINE_RESULT__));
	} else if (freeExports && freeModule) {
		if (module.exports == freeExports) {
			// in Node.js, io.js, or RingoJS v0.8.0+
			freeModule.exports = punycode;
		} else {
			// in Narwhal or RingoJS v0.7.0-
			for (key in punycode) {
				punycode.hasOwnProperty(key) && (freeExports[key] = punycode[key]);
			}
		}
	} else {
		// in Rhino or a web browser
		root.punycode = punycode;
	}

}(this));

/* WEBPACK VAR INJECTION */}.call(exports, __webpack_require__(7)(module), __webpack_require__(6)))

/***/ }),
/* 113 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";
// Copyright Joyent, Inc. and other Node contributors.
//
// Permission is hereby granted, free of charge, to any person obtaining a
// copy of this software and associated documentation files (the
// "Software"), to deal in the Software without restriction, including
// without limitation the rights to use, copy, modify, merge, publish,
// distribute, sublicense, and/or sell copies of the Software, and to permit
// persons to whom the Software is furnished to do so, subject to the
// following conditions:
//
// The above copyright notice and this permission notice shall be included
// in all copies or substantial portions of the Software.
//
// THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS
// OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF
// MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN
// NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM,
// DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR
// OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE
// USE OR OTHER DEALINGS IN THE SOFTWARE.



// If obj.hasOwnProperty has been overridden, then calling
// obj.hasOwnProperty(prop) will break.
// See: https://github.com/joyent/node/issues/1707
function hasOwnProperty(obj, prop) {
  return Object.prototype.hasOwnProperty.call(obj, prop);
}

module.exports = function(qs, sep, eq, options) {
  sep = sep || '&';
  eq = eq || '=';
  var obj = {};

  if (typeof qs !== 'string' || qs.length === 0) {
    return obj;
  }

  var regexp = /\+/g;
  qs = qs.split(sep);

  var maxKeys = 1000;
  if (options && typeof options.maxKeys === 'number') {
    maxKeys = options.maxKeys;
  }

  var len = qs.length;
  // maxKeys <= 0 means that we should not limit keys count
  if (maxKeys > 0 && len > maxKeys) {
    len = maxKeys;
  }

  for (var i = 0; i < len; ++i) {
    var x = qs[i].replace(regexp, '%20'),
        idx = x.indexOf(eq),
        kstr, vstr, k, v;

    if (idx >= 0) {
      kstr = x.substr(0, idx);
      vstr = x.substr(idx + 1);
    } else {
      kstr = x;
      vstr = '';
    }

    k = decodeURIComponent(kstr);
    v = decodeURIComponent(vstr);

    if (!hasOwnProperty(obj, k)) {
      obj[k] = v;
    } else if (isArray(obj[k])) {
      obj[k].push(v);
    } else {
      obj[k] = [obj[k], v];
    }
  }

  return obj;
};

var isArray = Array.isArray || function (xs) {
  return Object.prototype.toString.call(xs) === '[object Array]';
};


/***/ }),
/* 114 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";
// Copyright Joyent, Inc. and other Node contributors.
//
// Permission is hereby granted, free of charge, to any person obtaining a
// copy of this software and associated documentation files (the
// "Software"), to deal in the Software without restriction, including
// without limitation the rights to use, copy, modify, merge, publish,
// distribute, sublicense, and/or sell copies of the Software, and to permit
// persons to whom the Software is furnished to do so, subject to the
// following conditions:
//
// The above copyright notice and this permission notice shall be included
// in all copies or substantial portions of the Software.
//
// THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS
// OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF
// MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN
// NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM,
// DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR
// OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE
// USE OR OTHER DEALINGS IN THE SOFTWARE.



var stringifyPrimitive = function(v) {
  switch (typeof v) {
    case 'string':
      return v;

    case 'boolean':
      return v ? 'true' : 'false';

    case 'number':
      return isFinite(v) ? v : '';

    default:
      return '';
  }
};

module.exports = function(obj, sep, eq, name) {
  sep = sep || '&';
  eq = eq || '=';
  if (obj === null) {
    obj = undefined;
  }

  if (typeof obj === 'object') {
    return map(objectKeys(obj), function(k) {
      var ks = encodeURIComponent(stringifyPrimitive(k)) + eq;
      if (isArray(obj[k])) {
        return map(obj[k], function(v) {
          return ks + encodeURIComponent(stringifyPrimitive(v));
        }).join(sep);
      } else {
        return ks + encodeURIComponent(stringifyPrimitive(obj[k]));
      }
    }).join(sep);

  }

  if (!name) return '';
  return encodeURIComponent(stringifyPrimitive(name)) + eq +
         encodeURIComponent(stringifyPrimitive(obj));
};

var isArray = Array.isArray || function (xs) {
  return Object.prototype.toString.call(xs) === '[object Array]';
};

function map (xs, f) {
  if (xs.map) return xs.map(f);
  var res = [];
  for (var i = 0; i < xs.length; i++) {
    res.push(f(xs[i], i));
  }
  return res;
}

var objectKeys = Object.keys || function (obj) {
  var res = [];
  for (var key in obj) {
    if (Object.prototype.hasOwnProperty.call(obj, key)) res.push(key);
  }
  return res;
};


/***/ }),
/* 115 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


exports.decode = exports.parse = __webpack_require__(113);
exports.encode = exports.stringify = __webpack_require__(114);


/***/ }),
/* 116 */,
/* 117 */,
/* 118 */,
/* 119 */,
/* 120 */,
/* 121 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";
// Copyright Joyent, Inc. and other Node contributors.
//
// Permission is hereby granted, free of charge, to any person obtaining a
// copy of this software and associated documentation files (the
// "Software"), to deal in the Software without restriction, including
// without limitation the rights to use, copy, modify, merge, publish,
// distribute, sublicense, and/or sell copies of the Software, and to permit
// persons to whom the Software is furnished to do so, subject to the
// following conditions:
//
// The above copyright notice and this permission notice shall be included
// in all copies or substantial portions of the Software.
//
// THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS
// OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF
// MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN
// NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM,
// DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR
// OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE
// USE OR OTHER DEALINGS IN THE SOFTWARE.



var punycode = __webpack_require__(112);
var util = __webpack_require__(122);

exports.parse = urlParse;
exports.resolve = urlResolve;
exports.resolveObject = urlResolveObject;
exports.format = urlFormat;

exports.Url = Url;

function Url() {
  this.protocol = null;
  this.slashes = null;
  this.auth = null;
  this.host = null;
  this.port = null;
  this.hostname = null;
  this.hash = null;
  this.search = null;
  this.query = null;
  this.pathname = null;
  this.path = null;
  this.href = null;
}

// Reference: RFC 3986, RFC 1808, RFC 2396

// define these here so at least they only have to be
// compiled once on the first module load.
var protocolPattern = /^([a-z0-9.+-]+:)/i,
    portPattern = /:[0-9]*$/,

    // Special case for a simple path URL
    simplePathPattern = /^(\/\/?(?!\/)[^\?\s]*)(\?[^\s]*)?$/,

    // RFC 2396: characters reserved for delimiting URLs.
    // We actually just auto-escape these.
    delims = ['<', '>', '"', '`', ' ', '\r', '\n', '\t'],

    // RFC 2396: characters not allowed for various reasons.
    unwise = ['{', '}', '|', '\\', '^', '`'].concat(delims),

    // Allowed by RFCs, but cause of XSS attacks.  Always escape these.
    autoEscape = ['\''].concat(unwise),
    // Characters that are never ever allowed in a hostname.
    // Note that any invalid chars are also handled, but these
    // are the ones that are *expected* to be seen, so we fast-path
    // them.
    nonHostChars = ['%', '/', '?', ';', '#'].concat(autoEscape),
    hostEndingChars = ['/', '?', '#'],
    hostnameMaxLen = 255,
    hostnamePartPattern = /^[+a-z0-9A-Z_-]{0,63}$/,
    hostnamePartStart = /^([+a-z0-9A-Z_-]{0,63})(.*)$/,
    // protocols that can allow "unsafe" and "unwise" chars.
    unsafeProtocol = {
      'javascript': true,
      'javascript:': true
    },
    // protocols that never have a hostname.
    hostlessProtocol = {
      'javascript': true,
      'javascript:': true
    },
    // protocols that always contain a // bit.
    slashedProtocol = {
      'http': true,
      'https': true,
      'ftp': true,
      'gopher': true,
      'file': true,
      'http:': true,
      'https:': true,
      'ftp:': true,
      'gopher:': true,
      'file:': true
    },
    querystring = __webpack_require__(115);

function urlParse(url, parseQueryString, slashesDenoteHost) {
  if (url && util.isObject(url) && url instanceof Url) return url;

  var u = new Url;
  u.parse(url, parseQueryString, slashesDenoteHost);
  return u;
}

Url.prototype.parse = function(url, parseQueryString, slashesDenoteHost) {
  if (!util.isString(url)) {
    throw new TypeError("Parameter 'url' must be a string, not " + typeof url);
  }

  // Copy chrome, IE, opera backslash-handling behavior.
  // Back slashes before the query string get converted to forward slashes
  // See: https://code.google.com/p/chromium/issues/detail?id=25916
  var queryIndex = url.indexOf('?'),
      splitter =
          (queryIndex !== -1 && queryIndex < url.indexOf('#')) ? '?' : '#',
      uSplit = url.split(splitter),
      slashRegex = /\\/g;
  uSplit[0] = uSplit[0].replace(slashRegex, '/');
  url = uSplit.join(splitter);

  var rest = url;

  // trim before proceeding.
  // This is to support parse stuff like "  http://foo.com  \n"
  rest = rest.trim();

  if (!slashesDenoteHost && url.split('#').length === 1) {
    // Try fast path regexp
    var simplePath = simplePathPattern.exec(rest);
    if (simplePath) {
      this.path = rest;
      this.href = rest;
      this.pathname = simplePath[1];
      if (simplePath[2]) {
        this.search = simplePath[2];
        if (parseQueryString) {
          this.query = querystring.parse(this.search.substr(1));
        } else {
          this.query = this.search.substr(1);
        }
      } else if (parseQueryString) {
        this.search = '';
        this.query = {};
      }
      return this;
    }
  }

  var proto = protocolPattern.exec(rest);
  if (proto) {
    proto = proto[0];
    var lowerProto = proto.toLowerCase();
    this.protocol = lowerProto;
    rest = rest.substr(proto.length);
  }

  // figure out if it's got a host
  // user@server is *always* interpreted as a hostname, and url
  // resolution will treat //foo/bar as host=foo,path=bar because that's
  // how the browser resolves relative URLs.
  if (slashesDenoteHost || proto || rest.match(/^\/\/[^@\/]+@[^@\/]+/)) {
    var slashes = rest.substr(0, 2) === '//';
    if (slashes && !(proto && hostlessProtocol[proto])) {
      rest = rest.substr(2);
      this.slashes = true;
    }
  }

  if (!hostlessProtocol[proto] &&
      (slashes || (proto && !slashedProtocol[proto]))) {

    // there's a hostname.
    // the first instance of /, ?, ;, or # ends the host.
    //
    // If there is an @ in the hostname, then non-host chars *are* allowed
    // to the left of the last @ sign, unless some host-ending character
    // comes *before* the @-sign.
    // URLs are obnoxious.
    //
    // ex:
    // http://a@b@c/ => user:a@b host:c
    // http://a@b?@c => user:a host:c path:/?@c

    // v0.12 TODO(isaacs): This is not quite how Chrome does things.
    // Review our test case against browsers more comprehensively.

    // find the first instance of any hostEndingChars
    var hostEnd = -1;
    for (var i = 0; i < hostEndingChars.length; i++) {
      var hec = rest.indexOf(hostEndingChars[i]);
      if (hec !== -1 && (hostEnd === -1 || hec < hostEnd))
        hostEnd = hec;
    }

    // at this point, either we have an explicit point where the
    // auth portion cannot go past, or the last @ char is the decider.
    var auth, atSign;
    if (hostEnd === -1) {
      // atSign can be anywhere.
      atSign = rest.lastIndexOf('@');
    } else {
      // atSign must be in auth portion.
      // http://a@b/c@d => host:b auth:a path:/c@d
      atSign = rest.lastIndexOf('@', hostEnd);
    }

    // Now we have a portion which is definitely the auth.
    // Pull that off.
    if (atSign !== -1) {
      auth = rest.slice(0, atSign);
      rest = rest.slice(atSign + 1);
      this.auth = decodeURIComponent(auth);
    }

    // the host is the remaining to the left of the first non-host char
    hostEnd = -1;
    for (var i = 0; i < nonHostChars.length; i++) {
      var hec = rest.indexOf(nonHostChars[i]);
      if (hec !== -1 && (hostEnd === -1 || hec < hostEnd))
        hostEnd = hec;
    }
    // if we still have not hit it, then the entire thing is a host.
    if (hostEnd === -1)
      hostEnd = rest.length;

    this.host = rest.slice(0, hostEnd);
    rest = rest.slice(hostEnd);

    // pull out port.
    this.parseHost();

    // we've indicated that there is a hostname,
    // so even if it's empty, it has to be present.
    this.hostname = this.hostname || '';

    // if hostname begins with [ and ends with ]
    // assume that it's an IPv6 address.
    var ipv6Hostname = this.hostname[0] === '[' &&
        this.hostname[this.hostname.length - 1] === ']';

    // validate a little.
    if (!ipv6Hostname) {
      var hostparts = this.hostname.split(/\./);
      for (var i = 0, l = hostparts.length; i < l; i++) {
        var part = hostparts[i];
        if (!part) continue;
        if (!part.match(hostnamePartPattern)) {
          var newpart = '';
          for (var j = 0, k = part.length; j < k; j++) {
            if (part.charCodeAt(j) > 127) {
              // we replace non-ASCII char with a temporary placeholder
              // we need this to make sure size of hostname is not
              // broken by replacing non-ASCII by nothing
              newpart += 'x';
            } else {
              newpart += part[j];
            }
          }
          // we test again with ASCII char only
          if (!newpart.match(hostnamePartPattern)) {
            var validParts = hostparts.slice(0, i);
            var notHost = hostparts.slice(i + 1);
            var bit = part.match(hostnamePartStart);
            if (bit) {
              validParts.push(bit[1]);
              notHost.unshift(bit[2]);
            }
            if (notHost.length) {
              rest = '/' + notHost.join('.') + rest;
            }
            this.hostname = validParts.join('.');
            break;
          }
        }
      }
    }

    if (this.hostname.length > hostnameMaxLen) {
      this.hostname = '';
    } else {
      // hostnames are always lower case.
      this.hostname = this.hostname.toLowerCase();
    }

    if (!ipv6Hostname) {
      // IDNA Support: Returns a punycoded representation of "domain".
      // It only converts parts of the domain name that
      // have non-ASCII characters, i.e. it doesn't matter if
      // you call it with a domain that already is ASCII-only.
      this.hostname = punycode.toASCII(this.hostname);
    }

    var p = this.port ? ':' + this.port : '';
    var h = this.hostname || '';
    this.host = h + p;
    this.href += this.host;

    // strip [ and ] from the hostname
    // the host field still retains them, though
    if (ipv6Hostname) {
      this.hostname = this.hostname.substr(1, this.hostname.length - 2);
      if (rest[0] !== '/') {
        rest = '/' + rest;
      }
    }
  }

  // now rest is set to the post-host stuff.
  // chop off any delim chars.
  if (!unsafeProtocol[lowerProto]) {

    // First, make 100% sure that any "autoEscape" chars get
    // escaped, even if encodeURIComponent doesn't think they
    // need to be.
    for (var i = 0, l = autoEscape.length; i < l; i++) {
      var ae = autoEscape[i];
      if (rest.indexOf(ae) === -1)
        continue;
      var esc = encodeURIComponent(ae);
      if (esc === ae) {
        esc = escape(ae);
      }
      rest = rest.split(ae).join(esc);
    }
  }


  // chop off from the tail first.
  var hash = rest.indexOf('#');
  if (hash !== -1) {
    // got a fragment string.
    this.hash = rest.substr(hash);
    rest = rest.slice(0, hash);
  }
  var qm = rest.indexOf('?');
  if (qm !== -1) {
    this.search = rest.substr(qm);
    this.query = rest.substr(qm + 1);
    if (parseQueryString) {
      this.query = querystring.parse(this.query);
    }
    rest = rest.slice(0, qm);
  } else if (parseQueryString) {
    // no query string, but parseQueryString still requested
    this.search = '';
    this.query = {};
  }
  if (rest) this.pathname = rest;
  if (slashedProtocol[lowerProto] &&
      this.hostname && !this.pathname) {
    this.pathname = '/';
  }

  //to support http.request
  if (this.pathname || this.search) {
    var p = this.pathname || '';
    var s = this.search || '';
    this.path = p + s;
  }

  // finally, reconstruct the href based on what has been validated.
  this.href = this.format();
  return this;
};

// format a parsed object into a url string
function urlFormat(obj) {
  // ensure it's an object, and not a string url.
  // If it's an obj, this is a no-op.
  // this way, you can call url_format() on strings
  // to clean up potentially wonky urls.
  if (util.isString(obj)) obj = urlParse(obj);
  if (!(obj instanceof Url)) return Url.prototype.format.call(obj);
  return obj.format();
}

Url.prototype.format = function() {
  var auth = this.auth || '';
  if (auth) {
    auth = encodeURIComponent(auth);
    auth = auth.replace(/%3A/i, ':');
    auth += '@';
  }

  var protocol = this.protocol || '',
      pathname = this.pathname || '',
      hash = this.hash || '',
      host = false,
      query = '';

  if (this.host) {
    host = auth + this.host;
  } else if (this.hostname) {
    host = auth + (this.hostname.indexOf(':') === -1 ?
        this.hostname :
        '[' + this.hostname + ']');
    if (this.port) {
      host += ':' + this.port;
    }
  }

  if (this.query &&
      util.isObject(this.query) &&
      Object.keys(this.query).length) {
    query = querystring.stringify(this.query);
  }

  var search = this.search || (query && ('?' + query)) || '';

  if (protocol && protocol.substr(-1) !== ':') protocol += ':';

  // only the slashedProtocols get the //.  Not mailto:, xmpp:, etc.
  // unless they had them to begin with.
  if (this.slashes ||
      (!protocol || slashedProtocol[protocol]) && host !== false) {
    host = '//' + (host || '');
    if (pathname && pathname.charAt(0) !== '/') pathname = '/' + pathname;
  } else if (!host) {
    host = '';
  }

  if (hash && hash.charAt(0) !== '#') hash = '#' + hash;
  if (search && search.charAt(0) !== '?') search = '?' + search;

  pathname = pathname.replace(/[?#]/g, function(match) {
    return encodeURIComponent(match);
  });
  search = search.replace('#', '%23');

  return protocol + host + pathname + search + hash;
};

function urlResolve(source, relative) {
  return urlParse(source, false, true).resolve(relative);
}

Url.prototype.resolve = function(relative) {
  return this.resolveObject(urlParse(relative, false, true)).format();
};

function urlResolveObject(source, relative) {
  if (!source) return relative;
  return urlParse(source, false, true).resolveObject(relative);
}

Url.prototype.resolveObject = function(relative) {
  if (util.isString(relative)) {
    var rel = new Url();
    rel.parse(relative, false, true);
    relative = rel;
  }

  var result = new Url();
  var tkeys = Object.keys(this);
  for (var tk = 0; tk < tkeys.length; tk++) {
    var tkey = tkeys[tk];
    result[tkey] = this[tkey];
  }

  // hash is always overridden, no matter what.
  // even href="" will remove it.
  result.hash = relative.hash;

  // if the relative url is empty, then there's nothing left to do here.
  if (relative.href === '') {
    result.href = result.format();
    return result;
  }

  // hrefs like //foo/bar always cut to the protocol.
  if (relative.slashes && !relative.protocol) {
    // take everything except the protocol from relative
    var rkeys = Object.keys(relative);
    for (var rk = 0; rk < rkeys.length; rk++) {
      var rkey = rkeys[rk];
      if (rkey !== 'protocol')
        result[rkey] = relative[rkey];
    }

    //urlParse appends trailing / to urls like http://www.example.com
    if (slashedProtocol[result.protocol] &&
        result.hostname && !result.pathname) {
      result.path = result.pathname = '/';
    }

    result.href = result.format();
    return result;
  }

  if (relative.protocol && relative.protocol !== result.protocol) {
    // if it's a known url protocol, then changing
    // the protocol does weird things
    // first, if it's not file:, then we MUST have a host,
    // and if there was a path
    // to begin with, then we MUST have a path.
    // if it is file:, then the host is dropped,
    // because that's known to be hostless.
    // anything else is assumed to be absolute.
    if (!slashedProtocol[relative.protocol]) {
      var keys = Object.keys(relative);
      for (var v = 0; v < keys.length; v++) {
        var k = keys[v];
        result[k] = relative[k];
      }
      result.href = result.format();
      return result;
    }

    result.protocol = relative.protocol;
    if (!relative.host && !hostlessProtocol[relative.protocol]) {
      var relPath = (relative.pathname || '').split('/');
      while (relPath.length && !(relative.host = relPath.shift()));
      if (!relative.host) relative.host = '';
      if (!relative.hostname) relative.hostname = '';
      if (relPath[0] !== '') relPath.unshift('');
      if (relPath.length < 2) relPath.unshift('');
      result.pathname = relPath.join('/');
    } else {
      result.pathname = relative.pathname;
    }
    result.search = relative.search;
    result.query = relative.query;
    result.host = relative.host || '';
    result.auth = relative.auth;
    result.hostname = relative.hostname || relative.host;
    result.port = relative.port;
    // to support http.request
    if (result.pathname || result.search) {
      var p = result.pathname || '';
      var s = result.search || '';
      result.path = p + s;
    }
    result.slashes = result.slashes || relative.slashes;
    result.href = result.format();
    return result;
  }

  var isSourceAbs = (result.pathname && result.pathname.charAt(0) === '/'),
      isRelAbs = (
          relative.host ||
          relative.pathname && relative.pathname.charAt(0) === '/'
      ),
      mustEndAbs = (isRelAbs || isSourceAbs ||
                    (result.host && relative.pathname)),
      removeAllDots = mustEndAbs,
      srcPath = result.pathname && result.pathname.split('/') || [],
      relPath = relative.pathname && relative.pathname.split('/') || [],
      psychotic = result.protocol && !slashedProtocol[result.protocol];

  // if the url is a non-slashed url, then relative
  // links like ../.. should be able
  // to crawl up to the hostname, as well.  This is strange.
  // result.protocol has already been set by now.
  // Later on, put the first path part into the host field.
  if (psychotic) {
    result.hostname = '';
    result.port = null;
    if (result.host) {
      if (srcPath[0] === '') srcPath[0] = result.host;
      else srcPath.unshift(result.host);
    }
    result.host = '';
    if (relative.protocol) {
      relative.hostname = null;
      relative.port = null;
      if (relative.host) {
        if (relPath[0] === '') relPath[0] = relative.host;
        else relPath.unshift(relative.host);
      }
      relative.host = null;
    }
    mustEndAbs = mustEndAbs && (relPath[0] === '' || srcPath[0] === '');
  }

  if (isRelAbs) {
    // it's absolute.
    result.host = (relative.host || relative.host === '') ?
                  relative.host : result.host;
    result.hostname = (relative.hostname || relative.hostname === '') ?
                      relative.hostname : result.hostname;
    result.search = relative.search;
    result.query = relative.query;
    srcPath = relPath;
    // fall through to the dot-handling below.
  } else if (relPath.length) {
    // it's relative
    // throw away the existing file, and take the new path instead.
    if (!srcPath) srcPath = [];
    srcPath.pop();
    srcPath = srcPath.concat(relPath);
    result.search = relative.search;
    result.query = relative.query;
  } else if (!util.isNullOrUndefined(relative.search)) {
    // just pull out the search.
    // like href='?foo'.
    // Put this after the other two cases because it simplifies the booleans
    if (psychotic) {
      result.hostname = result.host = srcPath.shift();
      //occationaly the auth can get stuck only in host
      //this especially happens in cases like
      //url.resolveObject('mailto:local1@domain1', 'local2@domain2')
      var authInHost = result.host && result.host.indexOf('@') > 0 ?
                       result.host.split('@') : false;
      if (authInHost) {
        result.auth = authInHost.shift();
        result.host = result.hostname = authInHost.shift();
      }
    }
    result.search = relative.search;
    result.query = relative.query;
    //to support http.request
    if (!util.isNull(result.pathname) || !util.isNull(result.search)) {
      result.path = (result.pathname ? result.pathname : '') +
                    (result.search ? result.search : '');
    }
    result.href = result.format();
    return result;
  }

  if (!srcPath.length) {
    // no path at all.  easy.
    // we've already handled the other stuff above.
    result.pathname = null;
    //to support http.request
    if (result.search) {
      result.path = '/' + result.search;
    } else {
      result.path = null;
    }
    result.href = result.format();
    return result;
  }

  // if a url ENDs in . or .., then it must get a trailing slash.
  // however, if it ends in anything else non-slashy,
  // then it must NOT get a trailing slash.
  var last = srcPath.slice(-1)[0];
  var hasTrailingSlash = (
      (result.host || relative.host || srcPath.length > 1) &&
      (last === '.' || last === '..') || last === '');

  // strip single dots, resolve double dots to parent dir
  // if the path tries to go above the root, `up` ends up > 0
  var up = 0;
  for (var i = srcPath.length; i >= 0; i--) {
    last = srcPath[i];
    if (last === '.') {
      srcPath.splice(i, 1);
    } else if (last === '..') {
      srcPath.splice(i, 1);
      up++;
    } else if (up) {
      srcPath.splice(i, 1);
      up--;
    }
  }

  // if the path is allowed to go above the root, restore leading ..s
  if (!mustEndAbs && !removeAllDots) {
    for (; up--; up) {
      srcPath.unshift('..');
    }
  }

  if (mustEndAbs && srcPath[0] !== '' &&
      (!srcPath[0] || srcPath[0].charAt(0) !== '/')) {
    srcPath.unshift('');
  }

  if (hasTrailingSlash && (srcPath.join('/').substr(-1) !== '/')) {
    srcPath.push('');
  }

  var isAbsolute = srcPath[0] === '' ||
      (srcPath[0] && srcPath[0].charAt(0) === '/');

  // put the host back
  if (psychotic) {
    result.hostname = result.host = isAbsolute ? '' :
                                    srcPath.length ? srcPath.shift() : '';
    //occationaly the auth can get stuck only in host
    //this especially happens in cases like
    //url.resolveObject('mailto:local1@domain1', 'local2@domain2')
    var authInHost = result.host && result.host.indexOf('@') > 0 ?
                     result.host.split('@') : false;
    if (authInHost) {
      result.auth = authInHost.shift();
      result.host = result.hostname = authInHost.shift();
    }
  }

  mustEndAbs = mustEndAbs || (result.host && srcPath.length);

  if (mustEndAbs && !isAbsolute) {
    srcPath.unshift('');
  }

  if (!srcPath.length) {
    result.pathname = null;
    result.path = null;
  } else {
    result.pathname = srcPath.join('/');
  }

  //to support request.http
  if (!util.isNull(result.pathname) || !util.isNull(result.search)) {
    result.path = (result.pathname ? result.pathname : '') +
                  (result.search ? result.search : '');
  }
  result.auth = relative.auth || result.auth;
  result.slashes = result.slashes || relative.slashes;
  result.href = result.format();
  return result;
};

Url.prototype.parseHost = function() {
  var host = this.host;
  var port = portPattern.exec(host);
  if (port) {
    port = port[0];
    if (port !== ':') {
      this.port = port.substr(1);
    }
    host = host.substr(0, host.length - port.length);
  }
  if (host) this.hostname = host;
};


/***/ }),
/* 122 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


module.exports = {
  isString: function(arg) {
    return typeof(arg) === 'string';
  },
  isObject: function(arg) {
    return typeof(arg) === 'object' && arg !== null;
  },
  isNull: function(arg) {
    return arg === null;
  },
  isNullOrUndefined: function(arg) {
    return arg == null;
  }
};


/***/ }),
/* 123 */,
/* 124 */,
/* 125 */,
/* 126 */,
/* 127 */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(27);


/***/ })
/******/ ]);