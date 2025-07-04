import { message } from 'ant-design-vue'
import { defineStore } from 'pinia'
import { ref } from 'vue'
import { i18n } from '@/locales/setupI18n'
import type { Composer } from 'vue-i18n'
import { dump_log } from '@/api/index'
// 定义钱包类型
interface Wallet {
  name: string
  connect: () => Promise<void>
}

export default defineStore('wallet', () => {
  const { t } = i18n.global as unknown as Composer
  const loginObj = ref<LoginEntity.LoginParams>({
    signature: '',
    address: '',
    message: 'Please sign this message to confirm your identity.',
    share_code: '',
  })

  const getTronWeb = () => {
    try {
      // @ts-ignore
      return window.tronWeb
    } catch (e) {
      message.error(t('index.install_22'))
    }
  }

  // 钱包列表
  const wallet_list = ref<Wallet[]>([
    {
      name: 'tronLink',
      connect: () => {
        if (window['tronLink' as any]) {
          // @ts-ignore
          return window['tronLink' as any].request({ method: 'tron_requestAccounts' })
        }
        return Promise.reject(new Error(t('index.tronLink_22')))
      },
    },
  ])

  // 获取签名
  const toHex = async (data: string = loginObj.value.message) => {
    // loginObj.value.signature = getTronWeb().toHex(data)
    // console.log('sign',getTronWeb().toHex(data))
    const signature = await getTronWeb().trx.sign(getTronWeb().toHex(data))

    if (signature) {
      loginObj.value.signature = signature
      return Promise.resolve(signature)
    }
    return Promise.reject()
  }

  // 获取地址
  const address = () => {
    const address = getTronWeb()?.defaultAddress.base58

    if (address) {
      loginObj.value.address = address
    } else {
      message.error(t('index.address_22'))
    }
  }
  const walletCanStart = () => {
    // if (availableWallets.value.length === 0) {
    //     return false
    // }else{
    //     return true
    // }
    return true
  }

  // RTX交易
  const tradeRtx = async (amount: string, to: string) => {
    if (!walletCanStart()) return Promise.reject(t('index.not_33'))
    // 获取钱包连接的帐号
    const tronWeb = getTronWeb()
    const from = tronWeb.defaultAddress.base58
    const balance = await tronWeb.trx.getBalance(from)

    // 单位转换
    const trx = tronWeb.toSun(amount)
    if (balance < trx) return Promise.reject('message.recharge_fail')

    // 生成转帐
    const tx = await tronWeb.transactionBuilder.sendTrx(to, trx, from)
    // document.write("转帐中请稍候...")
    // 签名，弹窗用户确认
    const signedTx = await tronWeb.trx.sign(tx)
    // 广播已签名交易
    const broastTx = await tronWeb.trx.sendRawTransaction(signedTx)

    return Promise.resolve(broastTx.txid)
  }

  //合约地址交易
  const trade = async (amount: string, to: string, usdtContractAddress: string) => {
    if (!walletCanStart()) return Promise.reject(t('index.not_33'))
    console.log(amount, to, usdtContractAddress)
    const tronWeb = getTronWeb()
    try {
      const from = tronWeb.defaultAddress.base58
      const contract = await tronWeb.contract().at(usdtContractAddress)

      const balance = await contract.balanceOf(from).call()
      const usdtBalance = tronWeb.toBigNumber(balance).div(1e6).toString(10)

      if (usdtBalance < amount) return Promise.reject(t('message.recharge_fail'))

      // balance 是以 Sun 为单位的
      const transaction = await contract.transfer(to, tronWeb.toSun(amount)).send()
      dump_log(transaction)
      return Promise.resolve(transaction)
    } catch (error) {
      dump_log(error)
      return Promise.reject(t('index.not_33'))
    }
  }
  // 查找符合钱包列表name的对象
  const availableWallets = ref<Wallet[]>([
    {
      name: 'tronLink',
      connect: () => {
        if (window['tronLink' as any]) {
          // @ts-ignore
          return window['tronLink' as any].request({ method: 'tron_requestAccounts' })
        }
        return Promise.reject(new Error(t('index.tronLink_22')))
      },
    },
  ])

  const findAvailableWallets = () => {
    availableWallets.value = wallet_list.value.filter((wallet: Wallet) => window[wallet.name as any])
    if (availableWallets.value.length === 0) {
      message.error(t('index.availabl_44'))
    }
  }
  // address()
  // findAvailableWallets()
  return { getTronWeb, wallet_list, toHex, address, availableWallets, findAvailableWallets, loginObj, trade, tradeRtx }
})
