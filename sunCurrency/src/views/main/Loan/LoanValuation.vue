<template>
    <div class="LoanValuation">
        <div>
            <div class="title">{{ $t(title) }}</div>
            <div class="after-dollar-icon">{{ formatNumberWithCommas(num) }}</div>
        </div>
        <div class="history" @click="router.push({name:historyName,query: { cate: 'deposit' }})">{{ $t('loan.history_btn') }}</div>
    </div>
</template>

<script setup lang="ts">
import { formatNumberWithCommas, calculateTotalValue } from '@/utils/index.ts'
const props = defineProps({
    title:{
        type: String,
        required: true
    },
    historyName: {
        type: String,
        required: true
    },
    hasCoins: {
        type: Array,
        default: ()=>[]
    }
})
const emits = defineEmits(['getStat'])
const { t } = useI18n()
const useStore = useUser()
const router = useRouter()
const num = computed(()=>{
    return calculateTotalValue(props.hasCoins)
})
onMounted(()=>{
    emits('getStat')
})
</script>

<style lang="scss" scoped>
.LoanValuation{
    display: flex;
    align-items: center;
    justify-content: space-between;
    font-size: 1.6rem;
    background-color: var(--bgColor2);
    @include box-box;
    margin: 0.5rem 0 0 0;
    .title{
        color: var(--textColor2);
        font-size: 1.25rem;
        margin-bottom: 1.25rem;;
    }
}
</style>