<script setup>
  import { useTable } from '@/composables/useTable';
  import { useEdit } from './useEdit';
  import { useSelectStore } from '@/store/selectOpts';
  const store = useSelectStore();
  const { Withdrawal_status } = store;

  const { columns, dataSource, getData, params, loading, pagination, changeTable, delRow } = useTable({
    url: '/web3/withdrawal-orders/index',
    del_url: '/web3/withdrawal-orders/index',
    cols: [
      { title: 'ID', dataIndex: 'id' },
      { title: '订单号', dataIndex: 'order_no' },
      { title: '用户ID', dataIndex: 'user_id' },
      { title: '币种', dataIndex: 'currency' },
      { title: '金额', dataIndex: 'amount' },
      { title: '手续费', dataIndex: 'fee' },
      { title: '目标地址', dataIndex: 'destination_address' },
      { title: '状态', dataIndex: 'status' },
      { title: '创建时间', dataIndex: 'created_at' },
      { title: '更新时间', dataIndex: 'updated_at' },
      { title: '处理时间', dataIndex: 'processed_at' },
      { title: '交易ID', dataIndex: 'transaction_id' },
    ],
  });
  const { addNew } = useEdit({ getData });
  getData();
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
      <template v-if="column.dataIndex === 'status'">
        <a-space>
          {{ Withdrawal_status[text].description }}
        </a-space>
      </template>
    </template>
  </a-table>
</template>
