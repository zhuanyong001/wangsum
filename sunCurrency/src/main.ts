import { createApp } from 'vue'
import App from './App.vue'
import { setupStore } from './store'
import { setupRouter } from './router'
import { setupPlugins } from './plugins'
import { setupI18n } from '@/locales/setupI18n'
import Vant from 'vant';
import directives from '@/directives'
import 'vant/lib/index.css';
import 'virtual:svg-icons-register'
import 'virtual:uno.css'
import './styles/index.scss'
async function main() {
  /** 创建 Vue 应用实例 */
  const app = createApp(App)

  /** 自定义插件 */
  app.use(Vant);
  app.use(directives)
  setupPlugins(app)

  /** 配置 Store 状态管理 https://pinia.web3doc.top */
  setupStore(app)
  await setupI18n(app)
  /** 配置 Router https://router.vuejs.org/zh */
  setupRouter(app)

  /** 挂载应用 */
  app.mount('#app')

  console.log(`系统初始化完成`)
}

main()
