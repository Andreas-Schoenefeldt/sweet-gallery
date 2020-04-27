import Widgets from 'js-widget-hooks';
import './widgets/wp-image-attachments';

window.onload = function () {

    Widgets.init(document.querySelector('body'), {
        widgetClass: 'js-widget'
    });
};
