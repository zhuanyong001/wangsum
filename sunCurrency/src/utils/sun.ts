import { showRechange } from '@/components/Application'
import { sunRecharge } from '@/api/currency'
import { message } from 'ant-design-vue'
import { getAddress } from '@/api/currency'
import { getAccessToken } from '@/utils/cache/local-storage'
import sumLoading from '@/components/SunLoading'
const userStore = useUser()
const walletStore = wallet()

// 充值弹窗
export const openRechargeModel = (item: Currency, t) => {
  if (!getAccessToken()) {
    return message.error(t('message.NotLoggedIn'))
  }
  showRechange(
    {
      title: `${t('index.deposit')} ${item.name}`,
    },
    (e: string, resolvePopup, rejectPopup) => {
      if (e !== undefined) {
        console.log('====>>e', e)
        let order_id = 0
        sumLoading.show()
        getAddress({ currency_id: item.id, amount: e })
          .then((resAddress) => {
            order_id = resAddress.data.order_id as number
            if (item.contract_address === 'TRX') {
              return walletStore.tradeRtx(e, resAddress.data.address).catch((err) => {
                // message.error(err)
              })
            } else {
              return walletStore.trade(e, resAddress.data.address, item.contract_address)
            }
          })
          .then((tx_id) => {
            // console.log('和钱包交易成功',res)
            if (!tx_id) {
              sumLoading.hide()
              return Promise.reject('message.recharge_fail')
            }
            return sunRecharge({
              currency_id: item.id,
              amount: e,
              tx_id,
              order_id,
            })
          })
          .then((res) => {
            // 重新获取用户信息,刷新拥有货币
            userStore.getUserMessage()
            if (res.code == 200) {
              message.success(t('message.RechargeSuccessful'))
            } else {
              message.error(t('message.fail'))
            }
          })
          .catch((err) => {
            message.error(t('message.recharge_fail'))
            sumLoading.hide()
            // message.error(err.message);
          })
          .finally(() => {
            sumLoading.hide()
          })
        resolvePopup()
      }
      rejectPopup()
    },
    () => {
      console.log('取消')
    },
  )
}
