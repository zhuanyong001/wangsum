<script setup>
  import { useTable } from '@/composables/useTable';
  import { useEdit } from './useEdit';
  const { columns, dataSource, getData, params, loading, pagination, changeTable, delRow } = useTable({
    url: '/web3/system_configs/index',
    del_url: '/web3/system_configs/index',
    cols: [
      { title: '名称', dataIndex: 'name' },
      { title: '类型', dataIndex: 'type' },
      { title: '值', dataIndex: 'value' },
      { title: '是否私有', dataIndex: 'is_private' },
      { title: '分类', dataIndex: 'category' },
      { title: '描述', dataIndex: 'description' },
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
      <template v-if="column.dataIndex === 'value'">
        <div class="ellipsis">
          {{ text }}
        </div>
      </template>
      <template v-if="column.dataIndex === 'is_private'">
        <a-space>
          {{ text ? '是' : '否' }}
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
<style>
  .ellipsis {
    width: 200px;
    white-space: nowrap; /* 禁止文本换行 */
    overflow: hidden; /* 超出部分隐藏 */
    text-overflow: ellipsis; /* 显示省略号 */
  }
</style>
