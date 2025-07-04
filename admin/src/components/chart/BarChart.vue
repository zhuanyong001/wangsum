<template>
  <div style="height: 240px" ref="container" class="bar-chart"></div>
</template>

<script lang="ts" setup>
import { onBeforeUnmount, onMounted, PropType, ref, watch } from 'vue';
import { EChartsType, Color } from 'echarts';
import * as echarts from 'echarts';

const container = ref<HTMLElement>();

let chart: EChartsType | null = null;

const props = defineProps({
  color: Array as PropType<Color[]>,
  list: Array as PropType<[String, Number, Number][]> | undefined,
  list2: Array as PropType<[String, Number, Number][]> | undefined,
  title: String,
});

function resize() {
  chart?.resize();
}

function updateChart() {
  if (chart) {
    const list1Categories = props.list?.map(item => item[0]) || [];
    const list2Categories = props.list2?.map(item => item[0]) || [];
    const categories = list1Categories.length > list2Categories.length ? list1Categories : list2Categories;
    const firstTwoChars = props.title.substring(0, 2);
    const remainingChars = props.title.substring(2);
    chart.setOption({
      color: props.color ?? ['#ff0000', '#00ff00'],
      backgroundColor: {
        type: 'linear',
        x: 0,
        y: 0,
        x2: 1,
        y2: 0,
        colorStops: [
          {
            offset: 0,
            color: '#00369e',
          },
          {
            offset: 0.33,
            color: '#005cfd',
          },
          {
            offset: 1,
            color: '#a18dff',
          },
        ],
      },
      grid: {
        top: 40,
        left: 56,
        right: 20,
        bottom: 40,
      },
      xAxis: {
        name: '币种',
        nameTextStyle: { color: 'rgba(0, 0, 0, 0)' },
        type: 'category',
        data: categories,
        axisTick: { show: false },
        axisLine: { show: false },
        axisLabel: { color: '#fff' },
        splitLine: {
          show: false,
        },
      },
      darkMode: true,
      yAxis: {
        name: '数量',
        nameTextStyle: { color: 'rgba(0, 0, 0, 0)' },
        type: 'value',
        axisTick: { show: false },
        axisLine: { show: false },
        axisLabel: { color: '#fff' },
        splitLine: {
          lineStyle: {
            type: 'dashed',
            width: 2,
            color: 'rgba(255, 255, 255, 0.25)',
          },
        },
      },
      tooltip: {
        trigger: 'axis',
        axisPointer: {
          type: 'shadow',
        },
        confine:true,
        formatter: function (params: any) {
          const series1Data = props.list?.find(item => item[0] === params[0].name)||[];
          const series2Data = props.list2?.find(item => item[0] === params[0].name)||[];
          const series1Value = `数量: ${series1Data[1]||0}, 价值: ${series1Data[2]||0}美元` 
          const series2Value = `数量: ${series2Data[1]||0}, 价值: ${series2Data[2]||0}美元`
          return `
            <b>${params[0].name}</b><br/>
            <span >●</span> ${firstTwoChars}: ${series1Value}<br/>
            <span >●</span> ${remainingChars}: ${series2Value}
          `;
        },
      },
      series: [
        {
          name: firstTwoChars,
          type: 'bar',
          barWidth: 12,
          itemStyle: {
            borderRadius: 4,
          },
          data: props.list?.map(item => item[1]), // 取出数量数据
        },
        {
          name: remainingChars,
          type: 'bar',
          barWidth: 12,
          itemStyle: {
            borderRadius: 4,
          },
          data: props.list2?.map(item => item[1]), // 取出数量数据
        },
      ],
    });
  }
}

onMounted(() => {
  chart = echarts.init(container.value!);
  updateChart();
  window.addEventListener('resize', resize);
});

onBeforeUnmount(() => {
  window.removeEventListener('resize', resize);
  if (chart) {
    chart.dispose();
  }
});

// Watch for changes in the props and update the chart
watch(
  () => [props.list, props.list2],
  () => {
    updateChart();
  },
  { deep: true }
);
</script>

<style scoped lang="less">
.bar-chart {
  :deep(canvas) {
    @apply rounded-lg;
  }
}
</style>
