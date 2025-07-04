import { DirectiveBinding } from 'vue'
import { getAccessToken } from '@/utils/cache/local-storage' // 从缓存读取 Token 的方法
import { showModalConfirm } from '@/components/Application'
import { message } from 'ant-design-vue'
import { setShareCode, getShareCode, removeShareCode } from '@/utils/cache/local-storage'
import { showShareCode } from '@/components/Application'
import { i18n } from '@/locales/setupI18n'
import { dump_log } from '@/api/index'
async function connect() {
  const walletStore = wallet()
  const { t } = i18n.global
  // walletStore.findAvailableWallets()
  // const { connect: walletConnect } = walletStore.availableWallets[0]
  try {
    // const wall = await walletConnect()
    if (!walletStore.loginObj.signature) {
      await walletStore.toHex()
      walletStore.address()
    }
    return true
  } catch (e: unknown) {
    message.error(t('index.wallet_connect_error'))
    return false
  }
}

const loginEvent = (binding) => {
  const { t } = i18n.global
  const store = useUser()
  const hasToken = getAccessToken()
  const walletStore = wallet()
  if (hasToken && !binding.value) {
    showModalConfirm(
      {
        // @ts-ignore
        title: t('index.want_1'),
      },
      async(e, resolvePopup, rejectPopup) => { 
        store.logout()
        resolvePopup()
      },
    )
  } else if (!hasToken) {
    connect().then((success) => {
      const share_code = getShareCode()
      if (success && walletStore.loginObj.address) {
        store
          .login({
            ...walletStore.loginObj,
            share_code,
          })
          .then((res) => {
            console.log(res)
          })
          .catch((err) => {
            if (err === -416) {
              removeShareCode()
              showShareCode(
                {
                  // @ts-ignore
                  title: t('index.lease_11'),
                },
                (e: string,resolvePopup, rejectPopup) => {
                  if (e !== undefined) {
                    if (e == '') {
                      // @ts-ignore
                      message.error(t('index.lease_11'))
                      rejectPopup()
                    }
                    setShareCode(e)
                    loginEvent(binding)
                    resolvePopup()
                  }
                  rejectPopup()
                },
                () => {
                  // @ts-ignore
                  message.error(t('index.lease_11'))
                },
              )
            }
          })
      }
    })
  }
}

// 定义一个事件处理函数
const loginEventHandler = (binding) => {
  loginEvent(binding)
}

export const login = {
  mounted(el: HTMLElement, binding: DirectiveBinding) {
    const handler = () => loginEventHandler(binding)
    ;(el as any).__loginEventHandler__ = handler
    el.addEventListener('click', handler)
  },
  unmounted(el: HTMLElement) {
    const handler = (el as any).__loginEventHandler__
    if (handler) {
      el.removeEventListener('click', handler)
      delete (el as any).__loginEventHandler__
    }
  },
}
