export const PoolListMix = (item)=>{
    const currencyStore = currency()
    const currencyList = computed(() => currencyStore.currency_list)
    // 矿币图片
    const img = computed(() => {
        const coin = item.coin_code
        return currencyList.value.find((item) => item.name === coin)?.icon
    })
    return {
        img
    }
}