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
/******/ 	return __webpack_require__(__webpack_require__.s = 30);
/******/ })
/************************************************************************/
/******/ ({

/***/ 3:
/***/ (function(module, exports) {

var canvas;
var context;
var screenWidth;
var screenHeight;

var plotWidth = 720;

var waves = [];

/**
 * COLOURS
 */
var primary = Soda.colours[Soda.theme].primary;
var secondary = Soda.colours[Soda.theme].secondary;

var Wave = {
    create: function create(waveColor, waveQuality, offsetA, amplitudeA, frequencyA, speedA, offsetB, amplitudeB, frequencyB, speedB, offsetY) {
        var obj = Object.create(this);

        obj.waveColor = waveColor;
        obj.waveQuality = waveQuality;
        obj.amplitudeA = amplitudeA;
        obj.frequencyA = frequencyA;
        obj.speedA = speedA;
        obj.offsetA = offsetA;
        obj.amplitudeB = amplitudeB;
        obj.frequencyB = frequencyB;
        obj.speedB = speedB;
        obj.offsetB = offsetB;
        obj.offsetY = offsetY;

        return obj;
    },

    update: function update() {
        var i = 0;
        var angleA = 0;
        var angleB = 0;
        var norm = 0;

        //context.lineWidth = 0;
        //context.strokeStyle = waveColor;
        context.fillStyle = this.waveColor;
        context.beginPath();

        for (i; i < this.waveQuality; ++i) {
            norm = i / this.waveQuality;
            angleA = norm * this.frequencyA;
            angleB = norm * this.frequencyB;

            c = (screenHeight >> 1) + 200 - this.offsetY;
            x = 0;
            y = Math.sin(angleA + this.offsetA * this.speedA) * this.amplitudeA + Math.sin(angleB + this.offsetB * this.speedB) * this.amplitudeB + c;

            if (i === 0) {
                context.moveTo(x, y);
            } else {
                x = norm * plotWidth;
                context.lineTo(x, y);
            }
        }

        context.lineTo(x, canvas.height);
        context.lineTo(0, canvas.height);
        context.lineTo(0, c);

        context.fill();
        context.closePath();

        this.offsetA += this.speedA;
        this.offsetB += this.speedB;
    }
};

window.onload = function () {
    canvas = document.getElementById('wave');
    context = canvas.getContext('2d');

    window.onresize = function () {
        screenWidth = window.innerWidth;
        screenHeight = window.innerHeight;

        canvas.width = screenWidth;
        canvas.height = screenHeight;

        plotWidth = screenWidth;
    };

    window.onresize();

    waves.push(Wave.create(primary, 200, 0, 58, 12, 0.08, 0, 40, 25, 0.08, 100));
    waves.push(Wave.create(secondary, 200, 250, 85, 10, 0.08, 310, 40, 20, 0.065, 265));

    loop();
};

function loop() {
    requestAnimationFrame(loop);

    context.fillStyle = '#f0f0f0';
    context.fillRect(0, 0, screenWidth, screenHeight);

    var i = waves.length - 1;

    for (i; i > -1; --i) {
        var wave = waves[i];
        wave.update();
    }
}

/***/ }),

/***/ 30:
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(3);


/***/ })

/******/ });