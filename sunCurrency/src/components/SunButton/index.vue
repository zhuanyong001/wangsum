<template>
    <div class="sun-btn btn-shadow">
        <Button v-bind="props" :loading="loading" @click="onClick" 
        round 
        :loading-text="$t('msg.loading')"
        color="linear-gradient(to bottom, #A0FF05, #C5D503)"
        >
            <slot></slot>
        </Button>
    </div>
</template>

<script setup lang="ts">
import { ref, PropType } from 'vue'
import type { ButtonProps } from 'vant';
import { Button } from 'vant';
const attrs = useAttrs()
const loading = ref(false)
const props = defineProps<Partial<Omit<ButtonProps, 'loading'>>>()
    
async function onClick() {
    loading.value = true
    try{
        // @ts-ignore
        await attrs?.onClick?.()
    }finally{
        loading.value = false
    }
}

defineOptions({
    inheritAttrs:false
})
</script>

<style lang="scss" scoped>
.sun-btn{
    width: 100%;
    flex: 1;
}
.btn-shadow{
    position: relative;
    z-index: 1;
    &::before{
        content:'';
        border-radius: 4rem;
        z-index: 0;
        display: block;
        width: 100%;
        height: 100%;
        background: #579b00;
        position: absolute;
        top: 0.5rem;
    }
}
</style>