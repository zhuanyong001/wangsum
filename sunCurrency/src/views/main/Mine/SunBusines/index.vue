<template>
  <div class="SunBusines flex-row-start">
    <div class="title">{{ title }}</div>
    <img class="img-fillet" :src="kIcon" alt="" sizes="3.67rem" srcset="" />
  </div>
</template>
    
<script setup lang="ts">
import { defineProps } from 'vue'
const props = defineProps({
  title: {
    type: String,
    required: true,
  },
  // 必传但可为空的字符串
  icon: {
    type: String,
    required: true,
  },
})

const kIcon = computed(()=>{
    const obj = new URL(`../../../../assets/icons/${props.icon}.png`,import.meta.url)
    return obj.pathname
})
</script>
    
    <style lang="scss" scoped>
.SunBusines {
  @include box-box;
  border-radius: 0.83rem;
  justify-content: space-between;
  .title {
    font-size: 1.25rem;
    color: var(--textColor);
  }
  .img-fillet{
    width: 3.67rem;
    height: 3.67rem;
  }
}
</style>