<template>
    <div class="PoolItem">
        <div class="PoolItem-top flex-row">
            <div class="av-group">
                <img :src="item.currency.icon" alt="User 1" class="avatar" />
            </div>
            <div class="name">{{ item.currency.name }}</div>
            <div class="tag-group flex-row">
                <div class="tag1 tag" v-if="item.type === 1">{{ $t('index.current_1') }}</div>
                <div class="tag3 tag" v-else>
                    <span class="gradient-text">{{ item.cycle }}天</span>         
                </div>
            </div>
        </div>
        <div class="PoolItem-middle">
            <div class="flex-row PoolItem-middle-tb" v-for="(item, key) in displayData" :key="key">
                <div class="title">{{ fieldMapping[item.key] }}</div>
                <div :class="['num',{'gradient-text':item.key == 'status'}]">{{ item.value }}</div>
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
import { labelI18n } from '../fieldMapping.js'
import SunButton from '@/components/SunButton/index.vue'
const { fieldMapping } = labelI18n()
const { t } = useI18n()
const props = defineProps({
    item: {
        type: Object,
        required: true,
    },
})
const emit = defineEmits(['redeem'])

const loading = ref<boolean>(false)
const { img } = PoolListMix(props.item)

const rewithdrawPledge = () => {
    emit('redeem', props.item)
}

// 用于显示的字段名称及其值
const displayData = computed(() => {
    const displayArray = [
        {
            key: 'status',
            value: props.item.status === 1 ? `${t('deposit.Staking_23')}` : `${t('index.Redeemed_1')}`
        },
        {
            key: 'order_no',
            value: props.item.order_no
        },
        {
            key: 'deposit_amount',
            value: props.item.amount + ' ' + props.item.currency.name
        },
        {
            key: 'daily_rate',
            value: `${parseFloat(props.item.daily_rate * props.item.amount)}${props.item.currency.name}`
        },
        {
            key: 'deposit_time',
            value: props.item.created_at
        },
        {
            key: 'expire_time',
            value: props.item.expire_time
        }
    ];

    if (props.item.df_currency) {
        const dfAmountObj = {
            key: 'df_amount',
            value: props.item.df_amount + ' ' + props.item.df_currency.name
        };
        
        const dailyRateIndex = displayArray.findIndex(item => item.key === 'daily_rate');
        displayArray.splice(dailyRateIndex + 1, 0, dfAmountObj);
    }

    return displayArray;
})
</script>

<style lang="scss" scoped>
 @import '../common.scss';
</style>
