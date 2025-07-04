import useCurrency from '@/store/modules/currency'

export const PledgeMining = (attrs) => {
  const { t } = useI18n()
  const unrefAttrs = computed(() => unref(attrs))

  const Coins = computed(() => {
    const { loan_coins: coins } = unrefAttrs.value
    return coins.map((item) => {
      return {
        name: item.name,
        value: item.id,
      }
    })
  })

  const pledge_coins = computed(() => {
    const { pledge_coins: coins } = unrefAttrs.value
    return coins.map((item) => {
      return {
        name: item.name,
        value: item.id,
      }
    })
  })
  const Times = computed(() => {
    const { cycles } = unrefAttrs.value
    const cycleFilter = cycles.filter((item) => item.type === 2)
    return cycleFilter.map((item) => {
      return {
        name: item.days,
        value: item.id,
      }
    })
  })

  const CurrentId = computed(() => {
    const { cycles } = unrefAttrs.value
    return cycles.find((item) => item.type === 1)?.id || -1
  })

  return { Coins, pledge_coins, Times, CurrentId }
}
function floorTo8Decimals(value) {
  const factor = Math.pow(10, 6);
  return Math.floor(value * factor) / factor;
}
function ceilTo8Decimals(value) {
  const factor = Math.pow(10, 8);
  return Math.ceil(value * factor) / factor;
}
export const FinancialCalculations = (params, attrs) => {
  const userStore = useUser()
  
  const userCurrencyList = computed(() => userStore.user.assets)
  const unrefAttrs = computed(() => unref(attrs))
  
  const balance = computed(() => {
    return userCurrencyList.value.find((item) => item.currency.id === params.value.pledge_coin_id)?.amount || '0.00000000'
  })

  const dateRate = computed(() => {
    const { loan_rate } = unrefAttrs.value
    return `${(parseFloat(loan_rate) * 100).toFixed(2)}%`
  })

  const needCoins = computed(() => {
      const { loan_ratio, loan_coins, pledge_coins } = unrefAttrs.value
      const { loan_coin_id, pledge_coin_id, loan_amount } = params.value
      if(loan_amount > 0){
        const loan_amount_price = loan_coins.find((item) => item.id === loan_coin_id)?.price || 0
        const pledge_coin_price = pledge_coins.find((item) => item.id === pledge_coin_id)?.price || 0
        return ceilTo8Decimals((loan_amount * parseFloat(loan_amount_price)) / (parseFloat(pledge_coin_price) * parseFloat(loan_ratio)))
      }
      return 0
  })

  const maxCoin = computed(() => {
    const { loan_ratio, loan_coins, pledge_coins } = unrefAttrs.value
    const { loan_coin_id, pledge_coin_id } = params.value
    const mount = balance.value
    if(pledge_coin_id&&loan_coin_id){
      const loan_amount_price = loan_coins.find((item) => item.id === loan_coin_id)?.price || 0
      const pledge_coin_price = pledge_coins.find((item) => item.id === pledge_coin_id)?.price || 0
      console.log('maxCoin',mount,pledge_coin_price,loan_ratio,loan_amount_price)
      return floorTo8Decimals((parseFloat(mount) * parseFloat(pledge_coin_price) * parseFloat(loan_ratio)) / parseFloat(loan_amount_price))
    }
    return 0
  })

  const APY = computed(()=>{
    const { loan_rate, loan_coins, pledge_coins } = unrefAttrs.value
    const { loan_coin_id, loan_amount } = params.value
    if(loan_coin_id){
      const coin = loan_coins.find((item) => item.id === loan_coin_id)
      return (loan_amount * parseFloat(loan_rate)).toFixed(2) + coin.name
    }
    return '-'
  })
  return {
    balance,dateRate, needCoins, maxCoin, APY
  }
}
