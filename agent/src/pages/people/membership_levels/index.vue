<script setup>
  import { useTable } from '@/composables/useTable';
  import { useEdit } from './useEdit';
  const { columns, dataSource, getData, params, loading, pagination, changeTable, delRow } = useTable({
    url: '/web3/membership_levels/index',
    del_url: '/web3/membership_levels/index',
    cols: [
      { title: '会员名称', dataIndex: 'name_cn' },
      { title: '多语言名称', dataIndex: 'name' },
      { title: '等级', dataIndex: 'level' },
      { title: '最低矿池金额', dataIndex: 'pool_amount_usdt' },
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
