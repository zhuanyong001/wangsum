import { ref, computed, watch, onMounted, onUnmounted } from 'vue';
import systemCofig from '@/store/modules/config'
import { message } from 'ant-design-vue';
const currencyStore = currency()
const currencyList = computed(() => currencyStore.currency_list)

const userStore = useUser()
const userCurrencyList = computed(() => userStore.user.assets)

const useSystem = systemCofig()
const RESTRICTED_EXCHANGE = computed(()=>{
  if(useSystem.config_list.RESTRICTED_EXCHANGE){
    return useSystem.config_list.RESTRICTED_EXCHANGE.split(',')
  }
  return []
})
function divideAndFloorToEightDecimals(numerator: string, denominator: string, to_price: string,serviceCharge:number): string {
  
  const num = parseFloat(numerator);
  const denom = parseFloat(denominator);
  const to_priceNum = parseFloat(to_price);
  if (isNaN(num) || isNaN(denom) || denom === 0) {
    return '';
  }

  const result = ((num - serviceCharge)* to_priceNum) / denom;
  return result.toFixed(8);
}

// 货币兑换组合式函数
export function useCurrencyExchange() {
  const {t} = useI18n()
  const fristCurrency = computed(()=>{
    return currencyList.value[0]
  })
  const lastCurrency = computed(()=>{
    return currencyList.value[1]
  })
  // 当前货币和金额
  const currencySelect = ref<Currency>(fristCurrency.value);
  const currencyAmount = ref<string>('');

  // 计算当前最大金额
  const currencyMaxAmount = computed(() => {
    try {
      const foundItem = userCurrencyList.value.find((item: Asset) => {
        return currencySelect.value.name === item.currency.name;
      });
      return foundItem ? foundItem.amount : '0.00000000';
    } catch (error) {
      return '0.00000000';
    }
  });

  const onMaxTo = () => {
    currencyAmount.value = currencyMaxAmount.value;
    onInputFrom(currencyMaxAmount.value);
  };

  // 被兑换货币和金额
  const from_currencySelect = ref<Currency>(lastCurrency.value);
  const from_currencyAmount = ref<string>('');

  const from_hasPrice = computed(() => {
    try {
      const foundItem = userCurrencyList.value.find((item: Asset) => {
        return from_currencySelect.value.name === item.currency.name
      })
      return foundItem ? foundItem.amount : '0.00000000'
    } catch (error) {
      return '0.00000000'
    }
  })

  // 计算被兑换货币的最大金额
  const from_currencyMaxAmount = computed(() => {
    return divideAndFloorToEightDecimals(
      currencyMaxAmount.value,
      from_currencySelect.value?.price,
      currencySelect.value?.price,
      serviceCharge.value
    );
  });

  const onMaxFrom = () => {
    from_currencyAmount.value = from_currencyMaxAmount.value;
    onInputTo(from_currencyMaxAmount.value);
  };


  const onInputFrom = (e:string) =>{
    const newCurrencyAmount = e
    // if(parseFloat(e) > parseFloat(currencyMaxAmount.value)){
    //   onInputFrom(currencyMaxAmount.value)
    // }
    // else 
    if(newCurrencyAmount != ''){
      from_currencyAmount.value = divideAndFloorToEightDecimals(
        newCurrencyAmount,
        from_currencySelect.value?.price,
        currencySelect.value.price,
        serviceCharge.value
      )
    }
    else{
      from_currencyAmount.value = ''
    }
  }
  const onInputTo = (e:string) => {
    const newFromCurrencyAmount = e
    // if(parseFloat(e) > parseFloat(from_currencyMaxAmount.value)){
    //   onInputTo(from_currencyMaxAmount.value)
    // }
    // else 
    if(newFromCurrencyAmount != ''){
      currencyAmount.value = divideAndFloorToEightDecimals(
        newFromCurrencyAmount,
        currencySelect.value?.price,
        from_currencySelect.value.price,
        serviceCharge.value
      )
    }
    else{
      currencyAmount.value = ''
    }
  }

  const serviceCharge = computed(()=>{
    return parseFloat(currencyAmount.value||0) * 
    (parseFloat(currencySelect.value?.percentage_fee)) +
    parseFloat(currencySelect.value?.fixed_fee)||0
  })
  // 监听 currencyAmount 和 currencySelect 的变化
  watch(
    [currencyAmount,currencySelect],
    ([newCurrencyAmount,newCurrencySelect], [oldCurrencyAmount, oldCurrencySelect]) => {
      if(newCurrencyAmount==currencyMaxAmount.value){
        onInputFrom(currencyMaxAmount.value);
      }else 
      if (newCurrencySelect && (!oldCurrencySelect || newCurrencySelect.id !== oldCurrencySelect.id)) {
        // console.log('currencySelect变', newCurrencySelect,currencyAmount.value);
        // onInputFrom(Math.min(parseFloat(currencyAmount.value),parseFloat(currencyMaxAmount.value)) + '');
        currencyAmount.value = ''
        from_currencyAmount.value = ''
      }
    }
  );
  
  // 监听 from_currencySelect 的变化
  watch(
    [from_currencySelect],
    ([newFromCurrencySelect], [oldFromCurrencySelect]) => { 
      if (newFromCurrencySelect && (!oldFromCurrencySelect || newFromCurrencySelect.id !== oldFromCurrencySelect.id)) {
        // console.log('from_currencySelect变', newFromCurrencySelect);
        // onInputFrom(Math.min(parseFloat(currencyAmount.value),parseFloat(currencyMaxAmount.value)) + '');
        currencyAmount.value = ''
        from_currencyAmount.value = ''
      }
    }
  );

  watch(
    ()=>currencyList.value.length,
    (newValue)=>{
      if(newValue>0){
        if(!currencySelect.value) currencySelect.value = currencyList.value[0]
        if(!from_currencySelect.value) from_currencySelect.value = currencyList.value[1]
      }
    },
    { immediate: true }
  )
  const sunChangeParams = computed(() => {
    return {
      to_currency_id: from_currencySelect.value?.id,
      from_currency_id: currencySelect.value?.id,
      amount: currencyAmount.value,
    };
  });
  function change(){
    if(RESTRICTED_EXCHANGE.value.includes(currencySelect.value?.name))return message.error(t('msg.no_exchange'))
    const changeVal = currencySelect.value
    currencySelect.value = from_currencySelect.value
    from_currencySelect.value = changeVal
  }
  const exchangeRate = computed(()=>{
    return parseFloat(currencySelect.value?.price)/parseFloat(from_currencySelect.value?.price) || 0
  })

  

  // 返回暴露的状态和方法
  return {
    currencySelect,
    currencyAmount,
    currencyMaxAmount,
    onMaxTo,
    from_currencySelect,
    from_hasPrice,
    from_currencyAmount,
    from_currencyMaxAmount,
    onMaxFrom,
    sunChangeParams,
    change,
    onInputFrom,
    onInputTo,
    exchangeRate,
    serviceCharge
  };
}
