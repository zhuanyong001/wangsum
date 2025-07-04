import { LoginService } from '@/api'
import { getAccessToken, removeAccessToken, setAccessToken, getUserProfile, removeUserProfile, setUserProfile } from '@/utils/cache/local-storage'
import { i18n } from '@/locales/setupI18n'
import { message } from 'ant-design-vue'
/** 第一个参数是该 store 的唯一 id */
export default defineStore('user', () => {
  const { t } = i18n.global
  const token = ref<string>(getAccessToken())
  const avatar = ref<string>('@/assets/new/avatar.png')
  const user = ref<User>({
    ...getUserProfile(),
    assets: [],
  })
  // 登录状态
  const loading = ref<boolean>(false)
  /** 登录 */
  function login(loginParams: LoginEntity.LoginParams) {
    loading.value = true
    return new Promise((resolve, reject) => {
      LoginService.login(loginParams)
        .then((response: ApiResponseData) => {
          if (response.code === -416) {
            // @ts-ignore
            message.error(t('message.Invitation_code_required'))
            reject(response.code)
          } else {
            const { data } = response
            token.value = data.token
            user.value = data.user
            setAccessToken(unref(token))
            setUserProfile(unref(user))
            getUserMessage()
            // 获取币种
            // const currencyStore = currency()
            // currencyStore.getCurList()
            // @ts-ignore
            resolve(t('index.successful_1'))
          }
        })
        .catch((error) => {
          // 错误处理
          // @ts-ignore
          reject(t('index.failed_11'))
        })
        .finally(() => {
          loading.value = false
        })
    })
  }
  // 获取用户信息
  async function getUserMessage() {
    const { data: userMessage } = await LoginService.getUserMessage()
    let address = wallet().getTronWeb()?.defaultAddress.base58
    console.log('====>>address', address)
    if (address !== userMessage.tron_address) {
      logout()
      return Promise.reject(false)
    }
    user.value = {
      ...user.value,
      ...userMessage,
    }
    
    return Promise.resolve(true)
  }
  /** 退出登录 */
  function logout() {
    token.value = ''
    // @ts-ignore
    user.value = {
      balance: '0.00',
      assets: [],
    }
    removeAccessToken()
    removeUserProfile()
  }

  // 总资产
  function getAllPrice(): string {
    if (user.value && user.value.assets) {
      const { assets } = user.value
      const totalValue = assets.reduce((acc: number, asset: Asset) => {
        const amount = parseFloat(asset.amount)
        const price = parseFloat(asset.currency.price)
        return acc + amount * price
      }, 0)
      return totalValue.toLocaleString('en-US', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2,
      })
    } else {
      return '0.00'
    }
  }

  // 按钮文本信息
  function btnText(num: number) {
    if (token.value) {
      if (user.value?.tron_address) {
        const str = user.value.tron_address
        if (str.length <= num * 3) {
          return str
        }
        const start = str.slice(0, num)
        const end = str.slice(-num)
        return `${start}...${end}`
      }
      // @ts-ignore
      return i18n.global.t(t('index.wallet_23'))
    } else {
      // @ts-ignore
      return i18n.global.t(t('index.wallet_23'))
    }
  }
  // 初始化时，如果存在 token，获取用户信息
  if (token.value) {
    getUserMessage()
  }
  return { token, avatar, user, loading, login, logout, btnText, getAllPrice, getUserMessage }
})
