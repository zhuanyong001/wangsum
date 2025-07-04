<template>
  <div class="PoolItem">
    <div class="PoolItem-top flex-row">
      <div class="av-group">
        <img v-for="(img, index) in imgs" :key="index" :src="img?.icon" alt="User 1" class="avatar" />
      </div>
      <div class="name">{{ names() }}</div>
      <div class="tag-group flex-row">
        <div :class="['tag1', 'tag']">{{$t('loan.label_deposit')}}</div>
      </div>
    </div>
    <div class="PoolItem-middle">    
      <div class="flex-row PoolItem-middle-tb">
        <div class="title">{{$t('loan.estimate_deposit_ratio')}}</div>
        <div class="num">{{ calculateAnnualRateRange(item.cycles) }}</div>
      </div>
      <div class="flex-row PoolItem-middle-tb">
        <div class="title">{{$t('deposit.all_deposit')}}</div>
        <div class="num">${{compluteAmount()}}</div>
      </div>
    </div>
    <div class="PoolItem-bottom">
      <SunButton class="onbtn" type="primary" shape="round" size="large" 
      @click="myMining(item)">{{$t('loan.active_deposit_btn')}}</SunButton>
    </div>
    <div class="center" v-if="item.my_pool&&item.my_pool.length>0">
      <ToggleContent>
        <div class="flex-row PoolItem-middle-tb" v-for="poolItem in item.my_pool">
          <div class="title">{{$t('app.popup_deposit_title')}} {{poolItem.currency.name}}</div>
          <div class="num">{{poolItem.amount}}</div>
        </div>
      </ToggleContent>
    </div>
  </div>
</template>
  
<script setup lang="ts">
import { PropType } from 'vue'
import { pledgePost } from '@/api/pool'
import { message } from 'ant-design-vue'
import ToggleContent from './ToggleContent.vue'
import SunButton from '@/components/SunButton/index.vue'
import { showDeposit } from '@/components/Application'
const props = defineProps({
  item: {
    type: Object as PropType<Pool>,
    required: true,
  },
  tab:{
    type: Object,
    required: true
  }
})
const useStore = useUser()
const {t} = useI18n()
// 币种列表
const currencyStore = currency()
const currencyList = computed(() => currencyStore.currency_list)

// 质押挖矿表单
const myMining = (item: Pool) => {
  console.log('存款',item)
  showDeposit(item, async(e: pledgePost, resolvePopup, rejectPopup) => {
    try{
      if (e !== undefined) {
        const data = await props.tab.postFun(e)
        message.success(t('deposit.deposit_success'))
        useStore.getUserMessage()
        resolvePopup(data)
      }
      rejectPopup()
    }catch(e){
      console.error(e)
      rejectPopup()
    }
  })
}

// 矿币图片
const imgs = computed(() => {
  const coins = props.item.coin
  return coins.map((item) => {
    return currencyList.value.find((currency: Currency) => currency.id === item)
  })
})
// 名字
function names() {
  const coins = props.item.coins
  const nameArr = coins.map((item) => item.name)
  return nameArr.join('/')
}
// 赚钱名字
function MyPoolnames() {
  const my_pool = props.item.my_pool
  if(my_pool&&my_pool.length>0){
    const nameArr = my_pool.map((item) => item.currency.name)
    return nameArr.join('/')
  }
  return '--'
}
// 定期cycle
const periodicals = computed(() => {
  return props.item.cycles.filter((item) => item.type === 2)
})
// 利息范围
function calculateAnnualRateRange(cycles: Cycle[]) {
  // Convert daily_rate to numbers and find the min and max
  const dailyRates = cycles.map((cycle) => parseFloat(cycle.daily_rate) + parseFloat(cycle?.df_rate||0))
  const minRate = Math.min(...dailyRates)
  const maxRate = Math.max(...dailyRates)
  
  // Calculate annualized rates
  const minAnnualRate = minRate
  const maxAnnualRate = maxRate

  if (minRate === maxRate) {
    return `${(minAnnualRate * 100).toFixed(2)}%`
  } else {
    return `${(minAnnualRate * 100).toFixed(2)}%`
    // return `${(minAnnualRate * 100).toFixed(2)}%~${(maxAnnualRate * 100).toFixed(2)}%`
  }
}

function compluteAmount(){
  const sumAmount = props.item.sumAmount
  return sumAmount.reduce((acc: number, asset: Asset) => {
    const amount = parseFloat(asset.amount)
    const price = parseFloat(asset.currency.price)
    return acc + amount * price
  }, 0).toFixed(2)
}

</script>
  
  <style lang="scss" scoped>
.PoolItem {
  background: var(--bgColor3);
  padding: 1.67rem 1.25rem;
  box-sizing: border-box;
  width: 100%;
  border: 1px solid var(--borderColor);
  border-radius: 0.83rem;
  .center{
    display: flex;
    justify-content: center;
  }
  &-top {
    .av-group {
      display: flex;
      gap: 0.5rem;

      .avatar {
        width: 2.08rem;
        height: 2.08rem;
        border-radius: 50%;
        object-fit: cover;
        border: none;
        position: relative;
        transition: margin-left 0.3s;
        &:not(:first-child) {
          margin-left: -1.2rem; /* Adjust this value to control overlap */
          z-index: 0;
        }
      }
      &:hover .avatar {
        margin-left: 0 !important;
      }
    }
    .name {
      color: var(--textColor);
      font-size: 1.17rem;
      margin-left: 0.58rem;
      margin-right: auto;
    }
    .tag-group {
      .tag {
        padding: 0.25rem 1rem;
        border-radius: 1.125rem;
        font-size: 1.08rem;
        &:not(:last-child) {
          margin-right: 0.42rem;
        }
      }
      .tag1 {
        color: #8c51ff;
        background: #c8b4ef;
      }
      .tag2 {
        color: #1fbb85;
        background: #a6edd5;
      }
    }
  }
  &-middle {
    margin-top: 1.67rem;
    &-tb {
      width: 100%;
      font-size: 1.17rem;
      margin-bottom: 0.2rem;
      .title {
        color: var(--textColor2);
      }
      .num {
        color: var(--textColor);
      }
    }
  }
  &-bottom {
    display: flex;
    justify-content: center;
    margin-top: 1.67rem;
    .onbtn {
      font-size: 1.2rem;
      width: 100%;
      color: var(--textColor);
      background: var(--btnColor);
    }
  }
}
</style>