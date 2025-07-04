import type { App } from 'vue'
import * as AntDesignIcons from '@ant-design/icons-vue'

export default (app: App) => {
  for (const [key, component] of Object.entries(AntDesignIcons)) {
    app.component(key, component)
  }
}
