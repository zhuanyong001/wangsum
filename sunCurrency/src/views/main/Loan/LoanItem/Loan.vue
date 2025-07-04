<template>
    <div class="content">
      <!-- <LoanValuation v-if="token" historyName="MyLoan" :hasCoins="hasCoins" @getStat="getStat"
      title = "loan.LoanValuation_loan_title"
      ></LoanValuation> -->
      <!-- <van-pull-refresh v-model="loading" @refresh="onRefresh"> -->
        <!-- <sun-page ref="paging" v-model="dataList" @query="queryList" :otherParams="params"> -->
          <div v-for="(item, index) in dataList" :key="item.id" class="tabs-content-item">
            <LoanItem :item="item" :tab="tab"></LoanItem>
          </div>
        <!-- </sun-page> -->
      <!-- </van-pull-refresh> -->
    </div>
  </template>
  
  <script setup lang="ts">
  import LoanItem from './index.vue'
  import { ref } from 'vue'
  import { loanList, loan, loanStat } from '@/api/loan'
  import LoanValuation from '../LoanValuation.vue'
  const dataList = ref<Pool[]>([]) // 借款列表数据
  const loading = ref(false) // 加载状态
  const paging = ref()
  const params = ref<pageRequest>({
    page: 1,
    size: 10,
  })
  const userStore = useUser()
  const token = computed(() => userStore.token)
  const tab = ref({ getFun: loanList, postFun: loan })
  
  const hasCoins = ref([])
  const onRefresh = () => {
    paging.value.reload()
  };

  const queryList = (paramRef: any) => {
    loading.value = true // 请求开始时设置加载状态
    tab.value.getFun(paramRef)
      .then((res) => {
        dataList.value = res.data.list // 更新借款数据列表
        paging.value.complete(res.data.list)
        loading.value = false // 请求完成后取消加载状态
      })
      .catch((err) => {
        paging.value.complete(false)
        loading.value = false // 请求失败后也取消加载状态
        console.error('请求失败：', err)
      })
  }
  queryList(params)
  // const getStat = () => {
  //   loanStat()
  //     .then((res) => {
  //       hasCoins.value = res.data
  //     })
  //     .catch((err) => {
  //     })
  // }
  defineOptions({
  name: 'LoanLoan'
});
  </script>
  
  <style scoped lang="scss">
  @import '../common.scss';
  </style>