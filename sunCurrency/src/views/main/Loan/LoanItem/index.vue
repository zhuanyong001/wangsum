<template>
  <div class="PoolItem">
    <div class="PoolItem-top flex-row">
      <div class="av-group">
        <img v-for="(img, index) in imgs" :key="index" :src="img" alt="User 1" class="avatar" />
      </div>
      <div class="name">{{ names() }}</div>
      <div class="tag-group flex-row">
        <div :class="['tag2', 'tag']">{{ $t('loan.label_loan') }}</div>
      </div>
    </div>
    <div class="PoolItem-middle">
      <!-- <div class="flex-row PoolItem-middle-tb">
          <div class="title">{{$t('index.Your_position')}}</div>
          <div class="num">{{ calculateAnnualRateRange(item.cycles) }}</div>
        </div> -->
      <div class="flex-row PoolItem-middle-tb">
        <div class="title">{{ $t('loan.estimate_loan_rate') }}</div>
        <div class="num">{{ calculateAnnualRateRange(item.loan_rate) }}</div>
      </div>
      <div class="flex-row PoolItem-middle-tb">
        <div class="title">{{ $t('loan.estimate_loan_ratio') }}</div>
        <div class="num">{{ calculateAnnualRateRange(item.loan_ratio) }}</div>
      </div>
    </div>
    <div class="PoolItem-bottom">
      <SunButton class="onbtn" type="primary" shape="round" size="large" @click="myMining(item)">{{
          $t('loan.active_btn') }}</SunButton>
    </div>
  </div>
</template>

<script setup lang="ts">
import { PropType } from 'vue'
import { previewLoanOrder } from '@/api/loan'
import { message } from 'ant-design-vue'
import { showLoan, showLoanShow } from '@/components/Application'
import { showDialog } from 'vant';
import SunButton from '@/components/SunButton/index.vue'
import { showModalConfirm } from '@/components/Application'
const props = defineProps({
  item: {
    type: Object,
    required: true,
  },
  tab: {
    type: Object,
    required: true
  }
})
const useStore = useUser()
const { t } = useI18n()
// 币种列表
const currencyStore = currency()
const currencyList = computed(() => currencyStore.currency_list)

// 质押挖矿表单
const myMining = (item) => {
  showLoan(item, async(e, resolvePopup, rejectPopup) => {
    try{
      if (e !== undefined) {
        const res = await previewLoanOrder(e)
        console.log('借贷',res)
        resolvePopup(res)
        showLoanShow({
          ...e,
          ...item,
          ...res.data
        }, async (eve, resolvePopup2, rejectPopup2) => {
          await props.tab.postFun(e)
          resolvePopup2(res.data)
          useStore.getUserMessage()
          showModalConfirm(
            {
              title: t('loan.loan_success'),
              showCancelButton:false
            },
            async(eveve, resolvePopup3, rejectPopup3) => {
              resolvePopup3()
            },
          )
        })
      }
      rejectPopup()
    }catch(e){
      rejectPopup()
      console.error(e)
    }
  })
}

// 矿币图片
const imgs = computed(() => {
  const coins = props.item.loan_coins
  return coins.map((item) => {
    return item.icon
    // return currencyList.value.find((currency: Currency) => currency.id === item)
  })
})
// 名字
function names() {
  const coins = props.item.loan_coins
  const nameArr = coins.map((item) => item.name)
  return nameArr.join('/')
}
// 赚钱名字
function MyPoolnames() {
  const my_pool = props.item.my_pool
  if (my_pool && my_pool.length > 0) {
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
function calculateAnnualRateRange(rate: string) {
  return `${(+rate * 100).toFixed(2)}%`
}

function compluteAmount() {
  const sumAmount = props.item.sumAmount
  console.log(sumAmount.value, 'sumAmount')
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

  .center {
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
          margin-left: -1.2rem;
          /* Adjust this value to control overlap */
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