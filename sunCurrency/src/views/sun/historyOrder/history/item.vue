<template>
    <div class="PoolItem">
        <div class="PoolItem-top flex-row">
            <div class="av-group">
                <img :src="item.currency?.icon || item.loan_coin?.icon" alt="User 1" class="avatar" />
            </div>
            <div class="name">{{ item.currency?.name || item.loan_coin?.name }}</div>
            <div class="tag-group flex-row">
                <div class="tag1 tag" v-if="item.type === 1">{{ $t('index.current_1') }}</div>
                <div class="tag2 tag" v-else-if="item.type === 2">{{ $t('index.regular_2') }}</div>
                <div class="tag1 tag" v-else-if="item.order_type === 'loan'">{{ $t('loan.repayment_success') }}</div>
                <div v-else-if="item.order_type === 'loan'">
                    <div class="tag1 tag" v-if="item.status===2">{{ $t('loan.repayment_success') }}</div>
                    <div class="tag1 tag" v-if="item.status===3">{{ $t('loan.be_overdue') }}</div>
                    <div class="tag1 tag" v-if="item.status===4">{{ $t('loan.loan_closeposition') }}</div>
                </div>
            </div>
        </div>
        <div class="PoolItem-middle">
            <div class="flex-row PoolItem-middle-tb" v-for="(value, key) in labelT(item.order_type,item.cate).value" :key="key">
                <div class="title">{{ fieldMapping[key] }}</div>
                <div :class="['num',{'gradient-text':key == 'status'}]">{{ value }}</div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { PropType } from 'vue'
import { PoolListMix } from '../PoolListMix'
import {labelI18n} from '../fieldMapping.js'
const { t } = useI18n()
const {fieldMapping} = labelI18n()
const props = defineProps({
    item: {
        type: Object,
        required: true,
    }
})
const emit = defineEmits(['redeem'])

const currencyStore = currency()
const currency_list = computed(() => currencyStore.currency_list)

const { img } = PoolListMix(props.item)

const labelT = (type,cate) => {
    if(type === 'loan'){
        return computed(() => {
            const { loan_coin_id, pledge_coin_id, interest , interest_times, loan_amount} = props.item
            const loan_coin = currency_list.value.find((item) => item.id === loan_coin_id)
            const pledge_coin = currency_list.value.find((item) => item.id === pledge_coin_id)
            return {
                order_no: props.item.order_no,
                loan_amount: props.item.loan_amount + ' ' +loan_coin?.name,
                pledge_amount: props.item.pledge_amount + ' ' + pledge_coin.name,
                loan_rate: `${(parseFloat(props.item.loan_rate) * 100).toFixed(2)}%`,
                loan_ratio: (parseFloat(interest) + parseFloat(loan_amount)).toFixed(8) + ' ' + loan_coin.name,
                created_at: props.item.created_at
            }
        })
    }else if(cate === 2){
        return computed(() => {
            // const { loan_coin_id, pledge_coin_id, interest, interest_times, loan_amount } = props.item
            return {
                status:props.item.status === 1 ? `${t('index.Mining_1')}` : `${t('index.Redeemed_1')}`,
                order_no: props.item.order_no,
                pledge_amount: props.item.amount + ' ' + props.item.currency.name,
                total_award: props.item.total_award,
                created_at: props.item.created_at
            }
        })
    }else{
        return computed(() => {
            // const { loan_coin_id, pledge_coin_id, interest, interest_times, loan_amount } = props.item
            return {
                status:props.item.status === 1 ? `${t('index.Mining_1')}` : `${t('index.Redeemed_1')}`,
                order_no: props.item.order_no,
                pledge_amount: props.item.amount + ' ' + props.item.currency.name,
                total_award: props.item.total_award
            }
        })
    }
}
</script>

<style lang="scss" scoped>
 @import '../common.scss';
</style>