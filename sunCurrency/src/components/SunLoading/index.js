import { createApp } from 'vue'
import Loading from './Loading.vue' // 假设你把 Loading.vue 放在 components 文件夹

let loadingInstance = null

const sunLoading = {
  show() {
    if (!loadingInstance) {
      const loadingApp = createApp(Loading)
      const div = document.createElement('div')
      document.body.appendChild(div)
      loadingInstance = loadingApp.mount(div)
    }
    loadingInstance.show()
  },
  hide() {
    if (loadingInstance) {
      loadingInstance.hide()
    }
  },
}

export default sunLoading
