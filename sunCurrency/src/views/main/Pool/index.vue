<template>
  <div class="Pool">
    <TopFixed></TopFixed>
    <div class="Pool-body margin-bothSides-20">
      <div class="tabs">
        <div class="tabs-nav">
          <div class="flex-row">
            <div class="pool-title flex-row">
              <div class="pool-title1 gradient-text" @click="$router.push('upIncome')">{{$t('pool.pool_title')}}</div>
              <div class="pool-title2">
                <span class="gradient-text">{{$t('pool.can_up')}}</span>
              </div>
            </div>
            <img src="@/assets/images/pool_header.png" alt="">
          </div>
          <div class="flex-row">
            <TriStateSwitch :value="params.type" :states="tabs" @change="changeTab" />
            <div class="flex-row">
              <div class="margin-right-20">{{$t('index.ViewPledge')}}</div>
              <van-switch class="sun-switch" v-model="params.my" @change="paging.reload()" :active-value="1" :inactive-value="0" :disabled="!Boolean(useStore.token)"/>
            </div>
          </div>
        </div> 

        <div class="tabs-content">
          <sun-page ref="paging" v-model="dataList" @query="queryList" :otherParams="params">
            <pool-item class="tabs-content-item" v-for="(item, index) in dataList" :key="index" :item="item"></pool-item>
          </sun-page>
        </div>
      </div>
    </div>
    <sun-tab-bar selectedPagePath="/pool" />
  </div>
</template>

<script setup lang="ts">
import PoolItem from './PoolItem/index.vue'
import TriStateSwitch from './TriStateSwitch.vue'
import { ref } from 'vue'
import { getPoolList } from '@/api/pool'
// const currencyStore = currency()
// const currencyList = computed(() => currencyStore.currency_list)

const {t} = useI18n()
const useStore = useUser()
let dataList = ref<Pool[]>([])
const tabs = ref([
  { key: '0', title: t('index.all_1') },
  { key: '1', title: t('index.current_1') },
  { key: '2', title: t('index.regular_1') },
])
const params = ref<pageRequest>({
  page: 0,
  size: 10,
  type: '0',
  my:0
})

const changeTab = (key: string) => {
  if(params.value.type!==key){
    params.value.type = key
    paging.value.reload();
  }
}

const paging = ref()
const queryList = (paramRef: any) => {
  getPoolList(paramRef).then((res) => {
    paging.value.complete(res.data.list);
  }).catch(err=>{
    paging.value.complete(false);
  })
}

defineOptions({
  name: 'Pool'
});
</script>

<style lang="scss" scoped>
.Pool {
  &-body {
    position: relative;
    .tabs {
      min-height:50vh;
      .tabs-nav{
        position:sticky;
        top: var(--nav-top-height);
        z-index: 1;
        .pool-title{
          .pool-title1{
            font-weight: 600;
            font-size: 1.5rem;
          }
          .pool-title2{
            @include box-box;
            padding: 0.5rem;
            margin-left:0.5rem;
          }
          
        }
        img{
          width: 5rem;
          margin-right: 0.5rem;
        }
      }
      &-content {
        @include abl-list(15rem);
        width: 100%;
        &-item {
          margin-bottom: 0.83rem;
        }
      }
    }
  }
}
</style>
