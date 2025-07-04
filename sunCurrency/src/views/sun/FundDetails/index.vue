<template>
  <div class="FundDetails">
    <sun-header-nav :title="$t('index.details_2')" :rightText="$t('index.filter_2')" @handleRight="handleRight"></sun-header-nav>
    
      <div class="detail">
        <van-pull-refresh v-model="loading" @refresh="onRefresh">
        <div class="detail-head">
          <div class="gradient-text title">{{$t('index.assets_3')}}</div>
          <div class="gradient-text num">{{priceAmount}}</div>
        </div>
        <div class="detail-list">
          <sun-page ref="paging" v-model="dataList" @query="queryList" :otherParams="params">
            <sunItem class="sunItem" v-for="item in dataList" :item="item" :key="item.id"></sunItem>
          </sun-page>
        </div>
      </van-pull-refresh>
      </div>
    
    <sun-drawer ref="SunDrawerRef" @handleSubmit="onSubmit"></sun-drawer>
  </div>
</template>
    
<script setup lang="ts">
import { ref } from 'vue'
import SunItem from './SunItem/index.vue'
import SunDrawer from './SunDrawer/index.vue'
import {getLog} from '@/api/currency'
import VanPullRefresh from '@/components/VanPullRefresh/index.vue'
const params = ref<PriveLog>({
  types:[],   // ['','充值','提现','兑换','奖励','手续费','质押冻结','质押解冻','质押解冻','质押收益','提现失败']
  times:[],
  currency_id:0,
  page:1,
  size:10
})
const loading = ref(false) // 加载状态

const router = useRoute()
const { path } = router
const num = path.replace(/\/FundDetails\//,'')
params.value.currency_id = +num
const onRefresh = () => {
    paging.value.reload()
};

const useStore = useUser()
const useAssets = computed(()=>useStore.user.assets)
const priceAmount = computed(()=>{
  if(currency){
    const currency = useAssets.value.find(item=>{
      return item.currency_id == params.value.currency_id
    })
    return currency?.amount + ' ' + currency?.currency.name
  }
  return ''
})




const SunDrawerRef = ref()
const handleRight = () => {
  SunDrawerRef.value?.showDrawer()
}

const dataList = ref([])

const onSubmit = (e: any) => {
  console.log('e',e)
  params.value = {
    ...params.value,
    ...e
  }
  setTimeout(()=>{
    paging.value.reload();
  },0)
}
const paging = ref()

const queryList = (paramRef: any) => {
  loading.value = true
  getLog(paramRef).then((res) => {
    paging.value.complete(res.data.list);
    SunDrawerRef.value?.colseDrawer()
  }).catch(err=>{
    paging.value.complete(false);
    SunDrawerRef.value?.colseDrawer()
  }).finally(()=>{
    loading.value = false
  })
}

const hasPrice = computed(()=>{
  dataList.value.reduce((acc: number, asset: Asset) => {
    const amount = parseFloat(asset.amount)
    const price = parseFloat(asset.currency.price)
    return acc + amount * price
  }, 0)
})
</script>
    
<style lang="scss" scoped>
.FundDetails {
  display: flex;
  flex-direction: column;
  .detail {
    width: 100%;
    border-radius: 0.83rem;
    @include abl-list;
    .detail-head {
      margin-bottom: 2.08rem;

      .title {
        font-size: 1.25rem;
      }
      .num {
        font-weight: 600;
        font-size: 2.42rem;
      }
    }

    .detail-list {
      width: 100%;
      display: flex;
      flex-direction: column;

      .sunItem {
        margin-bottom: 0.83rem;

        &:last-child {
          margin-bottom: 0;
        }
      }
    }
  }
}
</style>