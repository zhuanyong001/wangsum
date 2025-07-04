import { createApp, DefineComponent } from 'vue'
import { i18n } from '@/locales/setupI18n'
import Vant from 'vant'

export function showModal<T>(component: DefineComponent<T>, props: T, handleOk?: Function, handleCancel?: Function) {
  const div = document.createElement('div')
  document.body.appendChild(div)
  // console.log('props',props)
  const app = createApp(component, {
    attrs: props,
    handleOk(e: unknown) {
      const close = () => {
        app.unmount()
        div.remove()
      }
      return new Promise((resolve, reject) => {
        handleOk(
          e,
          (data) => {
            resolve(data)
            close()
          },
          reject,
        )
      })
    },
    handleCancel() {
      handleCancel && handleCancel()
      app.unmount()
      div.remove()
    },
  })
  app.use(i18n)
  app.use(Vant)
  app.mount(div)
}
