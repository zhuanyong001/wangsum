<script setup>
  import { useTable } from '@/composables/useTable';
  import { useEdit } from './useEdit';
  import { useSelectStore } from '@/store/selectOpts';
  const { currency_list } = storeToRefs(useSelectStore());

  const { columns, dataSource, getData, params, loading, pagination, changeTable, delRow } = useTable({
    url: '/web3/air_drop/index',
    del_url: '/web3/air_drop/index',
    cols: [
      { title: '空投唯一标识', dataIndex: 'order_no' },
      { title: '最低参与金额($)', dataIndex: 'min_usd_amount' },
      { title: '空投金额', dataIndex: 'amount_value' },
      { title: '空投币种', dataIndex: ['currency', 'name'] },
      { title: '是否按比例', dataIndex: 'is_proportion', customRender: ({ text }) => (text ? '是' : '否') },
      { title: '开始时间', dataIndex: 'start_time' },
      { title: '结束时间', dataIndex: 'end_time' },
      { title: '状态', dataIndex: 'status', customRender: ({ text }) => (text ? '启动' : '关闭') },
      { title: '备注', dataIndex: 'remark' },
      { title: '操作', dataIndex: 'action' },
    ],
  });
  const { addNew } = useEdit({ getData, currency_list: currency_list });
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
