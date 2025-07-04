import { App } from 'vue';
import { login } from './click';

export default {
  install(app: App) {
    app.directive('login', login);
  },
};