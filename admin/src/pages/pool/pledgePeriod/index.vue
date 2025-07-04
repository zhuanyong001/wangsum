<script setup>
  import { useTable } from '@/composables/useTable';
  import { useEdit,pledgeTypes } from './useEdit';
  const { columns, dataSource, getData, params, loading, pagination, changeTable, delRow } = useTable({
    url: '/web3/mining-pools/cycle_item/list',
    del_url: '/web3/mining-pools/cycle_item/',
    cols: [
      { title: '周期名', dataIndex: 'name' },
      { title: '天数', dataIndex: 'days' },
      { title: '类型', dataIndex: 'type' },
      { title: '日利率', dataIndex: 'daily_rate' },
      { title: '复利', dataIndex: 'compound' },
      { title: '创建时间', dataIndex: 'created_at' },
      { title: '更新时间', dataIndex: 'updated_at' },
      { title: '操作', dataIndex: 'action' }
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
      <template v-if="column.dataIndex === 'type'">
        <a-space>
          {{ pledgeTypes[+text - 1].label }}
        </a-space>
      </template>
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
