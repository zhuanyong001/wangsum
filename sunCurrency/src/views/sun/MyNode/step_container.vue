<template>
    <div class="step-container">
        <div class="step-item" v-for="(step, index) in steps" :key="index">
            <div class="circle">
                {{ index + 1 }}
                <div class="text">{{ step }}</div>
            </div>
            <div v-if="index < steps.length - 1" class="line"></div>
        </div>
    </div>
</template>
    
<script setup>
import { ref } from 'vue'
const { t } = useI18n()
const steps = ref([t('step.step1'), t('step.step2'), t('step.step3')])

</script>

<style lang="scss" scoped>
$primary-color: #c6ff00; // 圆圈颜色
$line-color: #dcdcdc; // 线条颜色
$circle-size: 2.5rem; // 圆圈直径
$line-length: 6rem; // 线条长度

.step-container {
    display: flex;
    align-items: center;
    justify-content: space-between; // 等距分布
    width: 22rem;
    padding-bottom: 4rem;
}

.step-item {
    display: flex;
    align-items: center;
    width: 100%;
    &:last-child{
        width: fit-content;
    }
    .circle {
        width: $circle-size;
        height: $circle-size;
        @include colour-background-2();
        border-radius: 50%;
        display: flex;
        justify-content: center;
        align-items: center;
        font-size: 1.5rem;
        font-weight: bold;
        color: var(--textColor4);
        position: relative;
        .text{
            position: absolute;
            color: var(--textColor);
            width: 6rem;
            font-size: 1rem;
            top: 3rem;
            text-align: center;
        }
    }

    .line {
        // width: $line-length;
        flex: 1;
        height: 0.2rem;
        @include colour-background-2();
        margin: 0 0.3rem;
    }
}
</style>