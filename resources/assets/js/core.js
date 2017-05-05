try {
    window.$ = window.jQuery = require('soda-jquery');
    //require('jquery-ui-dist/jquery-ui');
    require('bootstrap/js/button.js');
    require('bootstrap/js/collapse.js');
    require('bootstrap/js/dropdown.js');
    require('bootstrap/js/modal.js');
    require('bootstrap/js/tab.js');
    require('bootstrap/js/tooltip.js');
    require('bootstrap/js/transition.js');
} catch (e) {}

window.Vue = require('vue');
