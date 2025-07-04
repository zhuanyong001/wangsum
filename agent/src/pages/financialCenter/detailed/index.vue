<script setup>
  import { useTable } from '@/composables/useTable';
  import { transaction_type, useEdit, Withdrawal_status_tag } from './useEdit';
  import QueryFrom from '@/components/dy/QueryFrom/index.vue';
  import { useUserDetail } from '@/pages/people/userDetails.js';
  const { details, renderUser } = useUserDetail();
  const { queryParams, columns, dataSource, getData, params, loading, pagination, changeTable, delRow } = useTable(
    {
      url: '/web3/financial/user_asset_log',
      del_url: '',
      cols: [
        { title: '来源', dataIndex: 'description' },
        {
          title: '用户',
          dataIndex: ['user', 'share_code'],
          customRender: renderUser,
        },
        { title: '金额', dataIndex: 'amount' },
        { title: '类型', dataIndex: 'type' },
        { title: '币种', dataIndex: ['user_asset', 'currency', 'name'] },
        { title: '创建时间', dataIndex: 'created_at' },
      ],
    },
    'DELETE',
    'POST_JSON'
  );

  //const { queryParams } = useEdit({ getData });

  const props = defineProps({
    user_id: {
      type: Number,
    },
  });
  if (props.user_id) {
    params.user_id = props.user_id;
  }
  getData(queryParams.value);

  const formConfig = computed(() => [
    {
      label: '订单状态',
      key: 'type',
      type: 'select',
      opts: transaction_type.value,
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
        <template v-if="column.dataIndex === 'type'">
          <a-tag>{{ transaction_type[text]?.label || '无' }}</a-tag>
        </template>
      </template>
    </a-table>
  </div>
</template>
