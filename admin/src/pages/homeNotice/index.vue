<script setup>
  import { useTable } from '@/composables/useTable';
  import { useEdit } from './useEdit';
  const { columns, dataSource, getData, params, loading, pagination, changeTable, delRow } = useTable({
    url: '/web3/notices/index',
    del_url: '/web3/notices',
    cols: [
      { title: '公告id', dataIndex: 'id' },
      { title: '公告标题', dataIndex: 'title_lang_key' },
      // { title: '公告类型', dataIndex: 'type' },
      { title: '状态', dataIndex: 'status' },
      { title: '排序', dataIndex: 'sort' },
      { title: '创建时间', dataIndex: 'created_at' },
      { title: '操作', dataIndex: 'action', width: 200, fixed: 'right' },
    ],
  });
  const { showEditModal } = useEdit({ getData });
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
        <a-button type="primary" @click="showEditModal()">
          <template #icon>
            <PlusOutlined />
          </template>
          新增</a-button
        >
      </div>
    </template>
    <template #bodyCell="{ column, text, record }">
      <template v-if="column.dataIndex === 'title_lang_key'">
        <p>{{ record.title.msgs[0].msg || '' }}</p>
        <!-- <p v-for="item in record.title.msgs">{{ item.msg }}</p> -->
      </template>
      <template v-if="column.dataIndex == 'status'">
        <a-tag :color="record.status === 1 ? 'green' : 'red'">{{ record.status === 1 ? '启用' : '禁用' }}</a-tag>
      </template>

      <template v-if="column.dataIndex === 'action'">
        <a-space>
          <a-button type="link" @click="showEditModal(record)">编辑</a-button>
          <a-popconfirm title="确定要删除吗" ok-text="确定" cancel-text="取消" @confirm="delRow(record.id)">
            <a-button type="link">删除</a-button>
          </a-popconfirm>
        </a-space>
      </template>
    </template>
  </a-table>
</template>
