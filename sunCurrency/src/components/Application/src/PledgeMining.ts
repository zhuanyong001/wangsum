export const PledgeMining = (attrs: Pool) => {
  const { t } = useI18n()
  const unrefAttrs = computed(() => unref(attrs))

  const Coins = computed(() => {
    const { coins } = unrefAttrs.value
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

  const pledgeMethod = computed(() => {
    const { cycles } = unrefAttrs.value
    const hasType1 = cycles.some((cycle) => cycle.type === 1)
    const hasType2 = cycles.some((cycle) => cycle.type === 2)
    const options = []
    if (hasType1) {
      options.push({ name: t('index.current_1'), value: 1 })
    }
    if (hasType2) {
      options.push({ name: t('index.regular_1'), value: 2 })
    }
    return options
  })

  const CurrentId = computed(() => {
    const { cycles } = unrefAttrs.value
    return cycles.find((item) => item.type === 1)?.id || -1
  })

  return { Coins, Times, pledgeMethod, CurrentId }
}

export const FinancialCalculations = (params: Ref<pledgePost>, attrs: Pool,Times) => {
  const userStore = useUser()
  const userCurrencyList = computed(() => userStore.user.assets)
  const unrefAttrs = computed(() => unref(attrs))
  const balance = computed(() => {
    return userCurrencyList.value.find((item) => item.currency.id === params.value.currency_id)?.amount || '0.00000000'
  })

  const APY = computed(() => {
    const { cycles } = unrefAttrs.value
    const cycle = cycles.find((item) => item.id === params.value.cycle)
    if (cycle) {
      const { daily_rate } = cycle

      const timeDay = Times.value.find(({value})=>{return value===params.value.cycle})?.name||1
      return `${(parseFloat(daily_rate) * 100 * Number(timeDay) ).toFixed(2)}%`
    } else {
      return '0.00%'
    }
  })


  const baseDayRate = computed(() => {
    const { amount, currency_id } = params.value
    const { cycles, coins } = unrefAttrs.value
    const cycle = cycles.find((item) => item.id === params.value.cycle)
    const timeDayRate = parseFloat(cycle?.daily_rate) || 0
    const currencyCoin = coins.find((item) => item.id === currency_id)
  
    if (currencyCoin) {
      const dailyRate = (+amount * timeDayRate).toFixed(8)
      return `${dailyRate} ${currencyCoin.name}`
    } else {
      return '-'
    }
  })
  const extraRate = computed(() => {
    const { amount, currency_id } = params.value
    const { cycles, coins } = unrefAttrs.value
    const cycle = cycles.find((item) => item.id === params.value.cycle)
    const currencyCoin = coins.find((item) => item.id === currency_id)
    let extraRate = ''
  
    if (cycle?.df_currency && currencyCoin) {
      const dfRate = parseFloat(cycle?.df_rate) || 0
      const dfCurrency = cycle.df_currency
      extraRate = `${((+amount * dfRate * parseFloat(currencyCoin.price)) / parseFloat(dfCurrency.price)).toFixed(8)} ${dfCurrency.name}`
    }
  
    return extraRate
  })
  
  return {
    balance,
    APY,
    baseDayRate,
    extraRate
  }
}
