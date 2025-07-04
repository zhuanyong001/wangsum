<template>
    <div class="PoolItem">
        <div class="PoolItem-top flex-row">
            <div class="av-group">
                <img :src="item.currency.icon" alt="User 1" class="avatar" />
            </div>
            <div class="name">{{ item.currency.name }}</div>
            <div class="tag-group flex-row">
                <div class="tag1 tag" v-if="item.type === 1">{{ $t('index.current_1') }}</div>
                <div class="tag2 tag" v-else>{{ $t('index.regular_2') }}</div>
            </div>
        </div>
        <div class="PoolItem-middle">
            <div class="flex-row PoolItem-middle-tb" v-for="(value, key) in displayData" :key="key">
                <div class="title">{{ fieldMapping[key] }}</div>
                <div :class="['num',{'gradient-text':key == 'status'}]">{{ value }}</div>
            </div>
        </div>
        <div class="PoolItem-bottom" v-if="item.type === 1">
            <SunButton class="onbtn" type="primary" :loading="loading" shape="round" size="large"
                @click="rewithdrawPledge"> {{ $t('index.redemption_4') }} </SunButton>
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
    // const { loan_coin_id, pledge_coin_id, interest, interest_times, loan_amount } = props.item
    return {
        status:props.item.status === 1 ? `${t('index.Mining_1')}` : `${t('index.Redeemed_1')}`,
        order_no: props.item.order_no,
        pledge_amount: props.item.amount + ' ' + props.item.currency.name,
        total_award: props.item.total_award,
        created_at_zhiya: props.item.created_at,
        expire_time: props.item.expire_time,
    }
})
</script>

<style lang="scss" scoped>
 @import '../common.scss';
</style>