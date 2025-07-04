import type { App } from 'vue'
import VueDOMPurifyHTML from 'vue-dompurify-html'
import registerAntDesignIcons from './ant-design-icon'

export function setupPlugins(app: App) {
  /** 解决 v-html 指令潜在的 xss 攻击 v-dompurify-html */
  app.use(VueDOMPurifyHTML)

  /** 注册 Ant Design Vue 提供的常用图标集合 */
  app.use(registerAntDesignIcons)
}
