<template>
    <div class="PoolItem">
      <div class="PoolItem-top flex-row">
        <div class="av-group">
          <img :src="item.loan_coin.icon" alt="User 1" class="avatar" />
        </div>
        <div class="name">{{ item.loan_coin.name }}</div>
        <div class="tag-group flex-row">
          <div class="tag2 tag" >{{ $t('loan.label_loan') }}</div>
        </div>
      </div>
      <div class="PoolItem-middle">
        <div class="flex-row PoolItem-middle-tb" v-for="(value, key) in displayData" :key="key">
            <div class="title">{{ fieldMapping[key] }}</div>
            <div class="num">{{ value }}</div>
        </div>
      </div>
      <div class="PoolItem-bottom">
        <SunButton class="onbtn" type="primary" :loading="loading" shape="round" size="large" 
        @click="rewithdrawPledge"> {{ $t('loan.repay') }} </SunButton>
      </div>
    </div>
  </template>
  
  <script setup lang="ts">
  import { PropType } from 'vue'
  import { PoolListMix } from '../PoolListMix'
  import {labelI18n} from '../fieldMapping.js'
  import SunButton from '@/components/SunButton/index.vue'
  const {fieldMapping} = labelI18n()
  const currencyStore = currency()
  const currency_list = computed(() => currencyStore.currency_list)
  const { t } = useI18n()
  const props = defineProps({
    item: {
      type: Object,
      required: true,
    }
  })
  const emit = defineEmits(['redeem'])
  
  const loading = ref<boolean>(false)
  const { img } = PoolListMix(props.item)
  
  const rewithdrawPledge = () => {
    emit('redeem', props.item)
  }
  
  // 用于显示的字段名称及其值
  const displayData = computed(() => {
      const { loan_coin_id, pledge_coin_id, interest , interest_times, loan_amount} = props.item
      const loan_coin = currency_list.value.find((item) => item.id === loan_coin_id)
      const pledge_coin = currency_list.value.find((item) => item.id === pledge_coin_id)
      return {
          order_no: props.item.order_no,
          loan_amount: props.item.loan_amount + ' ' +loan_coin.name,
          pledge_amount: props.item.pledge_amount + ' ' + pledge_coin.name,
          loan_rate: `${(parseFloat(props.item.loan_rate) * 100).toFixed(2)}%`,
          loan_ratio: (parseFloat(interest) + parseFloat(loan_amount)).toFixed(8) + ' ' + loan_coin.name,
          created_at: props.item.created_at
      }
  })
  </script>
  
  <style lang="scss" scoped>
 @import '../common.scss';
  </style>
  