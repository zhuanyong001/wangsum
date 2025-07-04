import {mouseListener} from './mouse.js';
import {slide,eraser} from './slide.js';
import clickOutside from './click-outside.js'
export default {
    install(app) {
        app.directive('mouse-listener', mouseListener);
        app.directive('slide-in', slide);
        app.directive('eraser', eraser);
        app.directive('click-outside', clickOutside);
    },
};