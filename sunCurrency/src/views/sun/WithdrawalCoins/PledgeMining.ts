import { unref, computed } from 'vue';

export const FinancialCalculations = (params:Ref<withdrawParams>) =>{
    const userStore = useUser()
    const userCurrencyList = computed(() => userStore.user.assets)
    const currencyStore = currency()
    const currencyList = computed(() => currencyStore.currency_list)
    const balance = computed(() => {
        return userCurrencyList.value.find(item => item.currency.id === unref(params).currency_id)?.amount || '0.00000000'
    })
    const coin = computed(() => {
        return userCurrencyList.value.find(item => item.currency.id === unref(params).currency_id)
    })
    const serviceCharge = computed(()=>{
        if(coin.value){
            return (parseFloat(params.value.amount||0) * 
            (parseFloat(coin.value.currency.percentage_fee)) +
            parseFloat(coin.value.currency.fixed_fee)||0).toFixed(8)
        }
        else{
            return '0.00'
        }
        
    })
    const actualReceipt = computed(()=>{
        const num = Math.max(parseFloat(params.value.amount)-parseFloat(serviceCharge.value),0)
        if(isNaN(num)){
            return '0.00'
        }
        return num
    })
    return {
        balance,
        userCurrencyList,
        currencyList,
        coin,
        serviceCharge,
        actualReceipt
    }
}