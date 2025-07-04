<script setup>
  import { useTable } from '@/composables/useTable';
  import { useEdit } from './useEdit';
  import { useSelectStore } from '@/store/selectOpts';
  import QueryFrom from '@/components/dy/QueryFrom/index.vue';
  import { useUserDetail } from '@/pages/people/userDetails.js';
  import emitter from '@/utils/bus';
  const { details, renderUser } = useUserDetail();
  const store = useSelectStore();
  const { Withdrawal_status } = store;
  const { queryParams, columns, dataSource, getData, params, loading, pagination, changeTable, delRow } = useTable(
    {
      url: '/web3/withdrawal-orders/pending_list',
      del_url: '/web3/withdrawal-orders/reject',
      cols: [
        { title: '订单号', dataIndex: 'order_no' },
        { title: '用户', dataIndex: ['user', 'share_code'], customRender: renderUser },
        { title: '用户备注', dataIndex: ['user', 'remark'] },
        { title: '货币', dataIndex: 'currency' },
        { title: '金额', dataIndex: 'amount' },
        { title: '手续费', dataIndex: 'fee' },
        { title: '实际到账', dataIndex: 'Actual_Receipt' },
        { title: '状态', dataIndex: 'status' },
        { title: '创建时间', dataIndex: 'created_at' },
        { title: '更新时间', dataIndex: 'updated_at' },
        { title: '操作', dataIndex: 'action' },
      ],
    },
    'POST_JSON',
    'POST_JSON'
  );
  const { submit } = useEdit({ getData });
  getData();

  const formConfig = computed(() => [
    {
      label: '用户',
      key: 'username',
      type: 'text',
    },
    { label: '日期选择', key: 'date', type: 'datePickerRange' },
  ]);
  const finish = (e) => {
    params.page = 1;
    queryParams.value = {
      ...queryParams.value,
      ...e,
    };
    getData(queryParams.value);
  };
  onMounted(() => {
    emitter.on('new_w_message', (res) => {
      getData();
    });
  });
</script>
<template>
  <div>
    <query-from :formConfig="formConfig" v-model="queryParams" @finish="finish"></query-from>
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
        <template v-if="column.dataIndex === 'Actual_Receipt'">
          <a-space>
            {{ parseFloat(record.amount) - parseFloat(record.fee) }}
          </a-space>
        </template>
        <template v-if="column.dataIndex === 'status'">
          <a-space>
            {{ Withdrawal_status[text].description }}
          </a-space>
        </template>
        <template v-if="column.dataIndex === 'action'">
          <!-- <a-space>
            <a-button type="link" @click="details(record)">用户详情</a-button>
          </a-space> -->
          <a-space>
            <a-popconfirm
              title="确定要同意吗"
              ok-text="确定"
              cancel-text="取消"
              @confirm="submit(record.id)"
              :disabled="record.status !== 1"
            >
              <a-button type="link" :disabled="record.status !== 1">同意</a-button>
            </a-popconfirm>
            <a-popconfirm
              title="确定要拒绝吗"
              ok-text="确定"
              cancel-text="取消"
              @confirm="delRow(record.id)"
              :disabled="record.status !== 1 && record.status !== 4"
            >
              <a-button type="link">拒绝</a-button>
            </a-popconfirm>
            <a-popconfirm title="确定要同意吗" ok-text="确定" cancel-text="取消" @confirm="submit(record.id)">
              <a-button type="link" v-if="record.status == 4">重新打款</a-button>
            </a-popconfirm>
          </a-space>
        </template>
      </template>
    </a-table>
  </div>
</template>
