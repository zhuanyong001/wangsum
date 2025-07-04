<template>
    <sun-modal :title="$t('msg.loan_success')" @ok="onSubmit" @cancel="handleCancel" :show-footer="false">
        <div class="sunModal-middle">
            <!-- 使用 v-for 循环展示每个字段 -->
            <div class="sunModal-middle-tb" v-for="(value, key) in displayData" :key="key">
                <div class="title">{{ fieldMapping[key] }}</div>
                <div class="num">{{ value }}</div>
            </div>
            
        </div>
        <div class="btn">
        <SunButton class="onbtn" type="primary" 
        @click="onSubmit" shape="round" size="large" block> {{ $t('index.confirm_11') }} 
        </SunButton>
        </div>
    </sun-modal>
</template>

<script setup lang="ts">
import { defineProps, computed } from 'vue'
import useCurrency from '@/store/modules/currency'
import SunButton from '@/components/SunButton/index.vue'
const currencyStore = useCurrency()
const currency_list = computed(() => currencyStore.currency_list)
const props = defineProps({
    attrs: {
        type: Object,
        required: true,
    },
    handleOk: {
        type: Function,
    },
    handleCancel: {
        type: Function,
    },
})
const { t } = useI18n()
// 字段映射，手动映射字段名到中文
const fieldMapping = {
    order_no: t('index.order_no'),
    loan_amount: t('index.loan_amount'),
    pledge_amount: t('index.pledge_amount'),
    loan_rate: t('index.loan_rate'),
    loan_ratio: t('loan.day_income_rate'),
    created_at: t('index.created_at')
}

// 用于显示的字段名称及其值
const displayData = computed(() => {
    const { loan_coin_id, pledge_coin_id } = props.attrs
    const loan_coin = currency_list.value.find((item) => item.id === loan_coin_id)
    const pledge_coin = currency_list.value.find((item) => item.id === pledge_coin_id)
    return {
        loan_amount: props.attrs.loan_amount + ' ' +loan_coin.name,
        pledge_amount: props.attrs.order.pledge_amount + ' ' + pledge_coin.name,
        loan_rate: calculateAnnualRateRange(props.attrs.loan_rate),
        loan_ratio: (props.attrs.loan_amount * props.attrs.loan_rate).toFixed(8) + ' ' + loan_coin.name,
    }
})

// 取消按钮的处理
const onSubmit = async() => {
    if (typeof props.handleOk === 'function') {
        await props.handleOk()
    }
}

// 利息范围
function calculateAnnualRateRange(rate: string) {
  return `${(+rate * 100).toFixed(2)}%`
}
</script>

<style scoped lang="scss">
.sunModal {
    &-middle {
        margin-top: 1.67rem;
        display: grid;
        gap: 0.67rem;
        grid-template-columns: repeat(1, 1fr);
        &-tb {
            display: flex;
            justify-content: space-between;
            width: 100%;
            font-size: 1.17rem;

            .title {
                color: var(--textColor2);
            }

            .num {
                color: var(--textColor);
                font-weight: bold;
            }
        }
    }

    
}
.btn {
      display: flex;
      justify-content: center;
      margin-top: 2.5rem;
      .onbtn {
        color: var(--textColor);
        background: var(--btnColor);
      }
    }
</style>
