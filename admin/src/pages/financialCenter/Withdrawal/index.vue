<script setup>
  import { useTable } from '@/composables/useTable';
  import { Withdrawal_status, useEdit, Withdrawal_status_tag } from './useEdit';
  import QueryFrom from '@/components/dy/QueryFrom/index.vue';
  import { useUserDetail } from '@/pages/people/userDetails.js';
  const TRON_URL = import.meta.env.VITE_TRON_URL;
  const { details, renderUser } = useUserDetail();
  const { queryParams, columns, dataSource, getData, params, loading, pagination, changeTable, delRow } = useTable(
    {
      url: '/web3/financial/withdrawal_list',
      del_url: '',
      cols: [
        { title: '订单号', dataIndex: 'order_no' },
        { title: '用户', dataIndex: ['user', 'share_code'], customRender: renderUser },
        { title: '用户备注', dataIndex: ['user', 'remark'] },
        { title: '金额', dataIndex: 'amount' },
        { title: '币种', dataIndex: ['currency', 'name'] },
        { title: '状态', dataIndex: 'status' },
        { title: '创建时间', dataIndex: 'created_at' },
        { title: '操作', dataIndex: 'action' },
        // { title: '操作', dataIndex: 'action' }
      ],
    },
    'DELETE',
    'POST_JSON'
  );

  const props = defineProps({
    user_id: {
      type: Number,
    },
  });
  //const { queryParams } = useEdit({ getData });
  if (props.user_id) {
    params.user_id = props.user_id;
  }
  getData(queryParams.value);
  const formConfig = computed(() => [
    {
      label: '用户地址',
      key: 'tron_address',
      type: 'text',
    },
    {
      label: '订单状态',
      key: 'status',
      type: 'select',
      opts: Withdrawal_status.value,
    },
    { label: '日期选择', key: 'date', type: 'datePickerRange' },
    {
      label: '币种',
      key: 'currency',
      type: 'select',
      opts: [
        {
          label: 'USDT',
          value: 'USDT',
        },
        {
          label: 'TRX',
          value: 'TRX',
        },
      ],
    },
  ]);

  const finish = (e) => {
    params.page = 1;
    queryParams.value = {
      ...queryParams.value,
      ...e,
    };
    getData(queryParams.value);
  };
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
      <template #bodyCell="{ column, text, record }">
        <template v-if="column.dataIndex === 'status'">
          <a-tag :color="Withdrawal_status_tag[text].color">
            {{ Withdrawal_status_tag[text].description }}
          </a-tag>
        </template>
        <template v-if="column.dataIndex == 'action'">
          <a-button
            type="link"
            target="_blank"
            :href="`${TRON_URL}${record.transaction_id}`"
            v-if="record.transaction_id"
            >查看交易</a-button
          >
        </template>
      </template>
    </a-table>
  </div>
</template>
