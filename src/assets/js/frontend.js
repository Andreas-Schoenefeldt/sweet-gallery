import './util/array-utils';
import Widgets from 'js-widget-hooks';
import './widgets/gallery-grid';

window.onload = function () {

    Widgets.init(document.querySelector('body'), {
        widgetClass: 'js-widget'
    });
};