<script setup>
  import { useTable } from '@/composables/useTable';
  //import {userDisplayed} from '@/utils/displayed'
  // import { useEdit } from './useEdit';
  import { useSelectStore } from '@/store/selectOpts';

  import { useUserDetail } from '@/pages/people/userDetails.js';
  const { details, renderUser } = useUserDetail();
  const { currency_list } = storeToRefs(useSelectStore());
  const { queryParams, columns, dataSource, getData, params, loading, pagination, changeTable, delRow } = useTable(
    {
      url: '/web3/mining-pools/order_award_list',
      del_url: '/web3/mining-pools/order_award_list',
      cols: [
        { title: '交易号', dataIndex: 'trade_no' },
        { title: '用户', dataIndex: ['user', 'share_code'], customRender: renderUser },
        { title: '金额', dataIndex: 'amount' },
        {
          title: '来源类型',
          dataIndex: 'type',
          customRender: ({ text, record }) => ['', '自己', '直属下级', '间接下级'][record.type],
        },
        {
          title: '来源用户',
          dataIndex: ['from_user', 'share_code'],
          customRender: renderUser,
        },
        {
          title: '来源订单',
          dataIndex: ['mining_pool_order', 'order_no'],
        },
        {
          title: '订单币种',
          dataIndex: ['mining_pool_order', 'coin_code'],
        },
        { title: '创建时间', dataIndex: 'created_at' },
      ],
    },
    'DELETE',
    'POST_JSON'
  );

  const formConfig = computed(() => [
    {
      label: '币种',
      key: 'currency_id',
      type: 'select',
      opts: currency_list.value,
    },
    { label: '日期选择', key: 'date', type: 'datePickerRange' },
  ]);
  const props = defineProps({
    user_id: {
      type: Number,
    },
  });
  if (props.user_id) {
    params.user_id = props.user_id;
  }
  getData(queryParams.value);
  const finish = (e) => {
    console.log('====>>e', e);
    params.page = 1;
    queryParams.value = {
      ...queryParams.value,
      ...e,
    };
    getData(queryParams.value);
  };
  //const { details } = userDisplayed({ getData });
  //getData();
</script>
<template>
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
