<script setup>
  import { useTable } from '@/composables/useTable';
  //import {userDisplayed} from '@/utils/displayed'
  // import { useEdit } from './useEdit';
  import { useUserDetail } from '@/pages/people/userDetails.js';
  const { details, renderUser } = useUserDetail();
  const { columns, dataSource, getData, params, loading, pagination, changeTable, delRow } = useTable({
    url: '/web3/mining-pools/order_award_list',
    del_url: '/web3/mining-pools/order_award_list',
    cols: [
      { title: '交易号', dataIndex: 'trade_no' },
      { title: '用户', dataIndex: ['user', 'share_code'], customRender: renderUser },
      { title: '金额', dataIndex: 'amount' },
      { title: '创建时间', dataIndex: 'created_at' },
    ],
  });
  //const { details } = userDisplayed({ getData });
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
      <template v-if="column.dataIndex === 'action'">
        <a-space>
          <a-button type="link" @click="details(record)">详情</a-button>
          <!-- <a-popconfirm title="确定要删除吗" ok-text="确定" cancel-text="取消" @confirm="delRow(record.id)">
            <a-button type="link">删除</a-button>
          </a-popconfirm> -->
        </a-space>
      </template>
    </template>
  </a-table>
</template>
