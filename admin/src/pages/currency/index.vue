<script setup>
  import { useTable } from '@/composables/useTable';
  import { useEdit } from './useEdit';
  const { columns, dataSource, getData, params, loading, pagination, changeTable, delRow } = useTable({
    url: '/web3/currencies',
    del_url: '/web3/currencies',
    cols: [
      { title: '货币名称', dataIndex: 'name' },
      { title: '价格', dataIndex: 'price' },
      { title: '24小时变化率', dataIndex: 'change_24h' },
      { title: '百分比服务费', dataIndex: 'percentage_fee' },
      { title: '固定费率', dataIndex: 'fixed_fee' },
      { title: '操作', dataIndex: 'action' },
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
    <template #title>
      <div class="flex justify-between pr-4">
        <a-button type="primary" @click="addNew()">
          <template #icon>
            <PlusOutlined />
          </template>
          新增</a-button
        >
      </div>
    </template>
    <template #bodyCell="{ column, text, record }">
      <template v-if="column.dataIndex === 'action'">
        <a-space>
          <a-button type="link" @click="addNew(record)">编辑</a-button>
          <a-popconfirm title="确定要删除吗" ok-text="确定" cancel-text="取消" @confirm="delRow(record.id)">
            <a-button type="link">删除</a-button>
          </a-popconfirm>
        </a-space>
      </template>
    </template>
  </a-table>
</template>
