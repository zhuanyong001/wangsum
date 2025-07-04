<template>
    <a-drawer
      v-model:visible="open"
      class="custom-class"
      root-class-name="root-class-name"
      :root-style="{ color: 'blue' }"
      style="color: red"
      :title="title"
      placement="right"
      @after-open-change="afterOpenChange"
    >
        <div v-for="(item,key, index) in dataSource" :key="index">
            <div v-if="Array.isArray(item)">{{dirct[key]}}</div>
            <a-table v-if="Array.isArray(item)" :dataSource="item" :columns="columns" :pagination="false">
              <template #bodyCell="{ column, text, record }">
                <template v-if="column.dataIndex === 'price'">
                  <a-space>
                    ${{ (Number(record.amount) * Number(record.currency.price)).toFixed(2) }}
                  </a-space>
                </template>
              </template>
            </a-table>
        </div>
    </a-drawer>
  </template>
  <script lang="ts" setup>
  import { ref } from 'vue';
  const open = ref<boolean>(false);
  const props = defineProps({
    title:{
        default:'货币详情'
    }
  })
  const afterOpenChange = (bool: boolean) => {
    console.log('open', bool);
  };
  const dirct = {
    'huoqi_amounts':'活期币种',
    'dingqi_amounts':'定期货币',
    'first_recharge_amounts':'首充货币',
    'recharge_amount':'充值货币',
    'withdrawal_amount':'提现货币'

  }
  const columns = [
    {
        title: '币种',
        dataIndex: ['currency','name'],
        key: 'name',
    },
    {
        title: '数量',
        dataIndex: 'amount',
        key: 'amount',
    },
    {
        title: '价格',
        dataIndex: 'price',
        key: 'price',
    },
]
    const dataSource = ref()
  const showDrawer = (data) => {
    // dataSource.value = data
    // open.value = true;
  };
  defineExpose({
    showDrawer
  })
  </script>
  
  