<script setup>
  import { useTable } from '@/composables/useTable';
  import { pledgeTypes } from './useEdit';
  import { useUserDetail } from '@/pages/people/userDetails.js';
  import { useSelectStore } from '@/store/selectOpts';
  const store = useSelectStore();
  const currencyList = computed(() => store.currency_list);
  const { details, renderUser } = useUserDetail();
  const { columns, dataSource, getData, params, loading, pagination, changeTable, delRow } = useTable({
    url: '/web3/loan-pools/order_list',
    del_url: '/web3/mining-pools/order_list',
    cols: [
      { title: '订单号', dataIndex: 'order_no' },
      { title: '用户', dataIndex: ['user', 'share_code'], customRender: renderUser },
      { title: '借款金额', dataIndex: 'loan_amount' },
      { title: '借款币种', dataIndex: 'loan_coin_id' },
      { title: '抵押金额', dataIndex: 'pledge_amount' },
      { title: '抵押币种', dataIndex: 'pledge_coin_id' },
      { title: '日利率', dataIndex: 'loan_rate' },
      { title: '提款比例', dataIndex: 'loan_ratio' },
      { title: '状态', dataIndex: 'status' },
      { title: '创建时间', dataIndex: 'created_at' },
      // { title: '更新时间', dataIndex: 'updated_at' }
    ],
  });
  // const { addNew } = useEdit({ getData });

  const props = defineProps({
    user_id: {
      type: Number,
    },
  });
  if (props.user_id) {
    params.user_id = props.user_id;
  }
  getData({ ...props });
</script>
<template>
  <a-table
    size="small"
    :columns="columns"
    :dataSource="dataSource"
    :loading="loading"
    :pagination="pagination"
    :rowKey="(record) => record.id"
    @change="changeTable"
  >
    <!-- <template #title>
      <div class="flex justify-between pr-4">
        <a-button type="primary" @click="addNew()">
          <template #icon>
            <PlusOutlined />
          </template>
          新增</a-button
        >
      </div>
    </template> -->
    <template #bodyCell="{ column, text, record }">
      <template v-if="column.dataIndex === 'loan_coin_id'|| column.dataIndex === 'pledge_coin_id'">
        <a-space>
          {{ currencyList.find(item=>item.value === text).label || '-' }}
        </a-space>
      </template>
      <template v-if="column.dataIndex === 'status'">
        <a-space>
          {{ { 1: '进行中', 2: '已完成' }[text] || '-' }}
        </a-space>
      </template>
      <template v-if="column.dataIndex === 'compound'">
        <a-space>
          {{ text ? '是' : '否' }}
        </a-space>
      </template>
      <template v-if="column.dataIndex === 'type'">
        <a-space>
          {{ pledgeTypes[+text - 1].label }}
        </a-space>
      </template>
      <!-- <template v-if="column.dataIndex === 'user'">
        <a-space>
          {{ text.tron_address }}
        </a-space>
      </template> -->
      <!-- <template v-if="column.dataIndex === 'action'">
        <a-space>
          <a-button type="link" @click="addNew(record)">编辑</a-button>
          <a-popconfirm title="确定要删除吗" ok-text="确定" cancel-text="取消" @confirm="delRow(record.id)">
            <a-button type="link">删除</a-button>
          </a-popconfirm>
        </a-space>
      </template> -->
    </template>
  </a-table>
</template>
