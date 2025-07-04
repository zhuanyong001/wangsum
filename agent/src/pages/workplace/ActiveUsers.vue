<template>
  <div class="active-users card p-lg">
    <OverviewTitle :title="title" subtitle="" change="" up />
    <!-- <bar-chart
      :color="['#ffffff','#FF00FF']"
      :list="list1Data"
      :list2="list2Data"
      :title="title"
    /> -->
    <div class="grid-2">
      <div class="details">
        <OverviewTitle :title="'总' + title.substring(0, 2)" />
        <div class="content">${{ amountAll }}</div>
        <!-- <div class="statistics">
          <a-statistic :title="item.title" :value="item.value" v-for="(item, i) in statisticList" :key="i" />
        </div> -->
      </div>
      <div class="details">
        <OverviewTitle :title="'总' + title.substring(2)" />
        <div class="content">${{ amountAll2 }}</div>
        <!-- <div class="statistics">
          <a-statistic :title="item.title" :value="item.value" v-for="(item, i) in statisticList" :key="i" />
        </div> -->
      </div>
      <!-- <div class="details" v-if="isRechange">
        <OverviewTitle :title="'充提差'"/>
        <div class="content">{{ recharge_withdrawal_Difference }} USDT</div>
      </div> -->
      <div class="details" v-if="isRechange">
        <OverviewTitle :title="'总充提差'" />
        <div class="content">${{ (amountAll - amountAll2).toFixed(2) }}</div>
      </div>
    </div>
  </div>
</template>
<script lang="ts">
  import { defineComponent, computed, watch } from 'vue';
  import BarChart from '@/components/chart/BarChart.vue';
  import OverviewTitle from '@/components/statistic/OverviewTitle.vue';
  import { amount, currency } from '@/types';
  import { useSelectStore } from '@/store/selectOpts';

  // import { watch } from 'fs';
  export default defineComponent({
    name: 'ActiveUsers',
    props: {
      title: {
        type: String,
      },
      list1: {
        type: Array as () => amount[],
        default: () => [],
        required: true,
      },
      list2: {
        type: Array as () => amount[],
        default: () => [],
        required: true,
      },
      isRechange: {
        type: Boolean,
      },
      USDTPrice: {
        default: '1',
      },
      recharge_withdrawal_Difference: {},
    },
    components: { BarChart, OverviewTitle },
    methods: {},
    setup(props, { attrs, slots, emit }) {
      const storeSelect = useSelectStore();
      const USDTPrice = computed(() => storeSelect.USDTPrice);
      // const processData = (data: amount[]):[String, Number,Number][] => {
      //   return data?.map(item => [
      //     item.currency.name,
      //     Number(item.amount),
      //     (parseFloat(item.currency.price) * parseFloat(item.amount) * parseFloat(props.USDTPrice)),
      //   ])||[['TRX',0,0]]
      //   ;
      // };
      function calculateTotalAmount(dataArray): Number {
        // 初始化总金额为0
        let totalAmount = 0;

        // 遍历数组中的每个对象
        dataArray.forEach((item) => {
          const amount = parseFloat(item.amount); // 转换amount为浮点数
          const price = parseFloat(item.currency.price); // 转换currency price为浮点数
          const USTDhuilv = 1; // parseFloat(USDTPrice.value as string);
          // 累加每个item的总价
          totalAmount += amount * price * USTDhuilv;
        });

        // 返回格式化为美元符号开头的字符串
        return +totalAmount.toFixed(2);
      }
      // const list1Data = computed(() => processData(props.list1));
      // const list2Data = computed(() => processData(props.list2));
      // const amountAll = computed(() =>
      //   list1Data.value.reduce((acc, item) => acc + (item[1] || 0), 0)
      // )
      // const amountAll2 = computed(() =>
      //   list2Data.value.reduce((acc, item) => acc + (item[1] || 0), 0)
      // )
      const amountAll = computed(() => calculateTotalAmount(props.list1));
      const amountAll2 = computed(() => calculateTotalAmount(props.list2));
      return { amountAll, amountAll2 };
    },
  });
</script>
<style scoped lang="less">
  .active-users {
    .details {
      @apply mt-lg;
    }
    .content {
      @apply mt-md text-title;
    }
    .statistics {
      @apply flex mt-md;
      :deep(.ant-statistic) {
        @apply flex-1 flex flex-col-reverse;
        .ant-statistic-title {
          @apply text-xs -mt-1;
        }
        .ant-statistic-content {
          @apply font-bold;
        }
      }
    }
  }
  .grid-2 {
    display: grid;
    grid-template-columns: 1fr 1fr;
  }
  .flex-row {
    display: flex;
  }
</style>
