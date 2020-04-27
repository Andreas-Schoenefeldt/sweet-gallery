import Widgets from 'js-widget-hooks';

window.onload = function () {

    Widgets.init(document.querySelector('body'), {
        widgetClass: 'js-widget'
    });
};