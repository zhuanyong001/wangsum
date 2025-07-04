<template>
  <div
    class="mini-statistic-card overflow-hidden relative min-h-[112px] bg-container items-center justify-between drop-shadow-sm p-md border-border rounded-lg"
  >
    <div class="statistic-main flex-1">
      <div class="statistic-title text-subtext text-xs">{{ title }}</div>
      <div class="statistic-content flex items-baseline">
        <div class="value text-title text-xxl font-bold flex-1"><slot name="default"></slot></div>
        <span class="suffix ml-1 text-xs text-green-500 font-bold"></span>
      </div>
    </div>
    <div class="statistic-icon absolute bottom-0 right-0"><slot name="icon"></slot></div>
  </div>
</template>

<script lang="ts">
  import { defineComponent, PropType } from 'vue';

  export default defineComponent({
    props: {
      title: String,
      value: [String, Number] as PropType<string | number>,
    },
    name: 'MiniStatisticCard',
  });
</script>

<style lang="less" scoped>
  .mini-statistic-card {
  }
  .statistic-icon {
    z-index: -1;
  }
</style>
