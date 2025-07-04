import { createApp } from 'vue';
import App from './App.vue';
import router from './router';
import store from './store';
import Antd from 'ant-design-vue';
import 'ant-design-vue/dist/reset.css';
import directives from './directive';

import tool from './utils/tools/tool';
import './router/guard.js'
import '@/styles/auto.scss'
import '@/styles/my.css'
import Vant from 'vant';
import { setupI18n } from '@/locales/setupI18n';

async function bootstrap() {
    const app = createApp(App);

    await setupI18n(app);
    app.use(Vant);
    app.config.globalProperties.$tool = tool;
    app.use(store).use(router).use(Antd).use(directives).mount('#app');
}

bootstrap();

