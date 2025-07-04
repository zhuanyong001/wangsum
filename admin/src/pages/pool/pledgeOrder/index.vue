<script setup>
  import { useTable } from '@/composables/useTable';
  import { pledgeTypes } from './useEdit';
  import { useUserDetail } from '@/pages/people/userDetails.js';
  import { useSelectStore } from '@/store/selectOpts';
  const { details, renderUser } = useUserDetail();
  const { queryParams, columns, dataSource, getData, params, loading, pagination, changeTable, delRow } = useTable({
    url: '/web3/mining-pools/order_list',
    del_url: '/web3/mining-pools/order_list',
    cols: [
      { title: '订单号', dataIndex: 'order_no' },
      { title: '用户', dataIndex: ['user', 'share_code'], customRender: renderUser },
      { title: '币种代码', dataIndex: 'coin_code' },
      { title: '金额', dataIndex: 'amount' },
      { title: '日利率', dataIndex: 'daily_rate' },
      { title: '结算基数', dataIndex: 'settlement_base' },
      { title: '总奖励', dataIndex: 'total_award' },
      { title: '周期', dataIndex: 'cycle' },
      { title: '状态', dataIndex: 'status' },
      { title: '类型', dataIndex: 'type' },
      { title: '复利', dataIndex: 'compound' },
      { title: '到期时间', dataIndex: 'expire_time' },
      { title: '创建时间', dataIndex: 'created_at' },
      // { title: '更新时间', dataIndex: 'updated_at' }
    ],
  });
  // const { addNew } = useEdit({ getData });

  const props = defineProps({
    user_id: {
      type: Number,
    },
  });
  if (props.user_id) {
    params.user_id = props.user_id;
  }
  getData({ ...props, ...queryParams });
  const formConfig = computed(() => [
    {
      label: '质押币种',
      key: 'coin_code',
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
        {
          label: 'DGFY',
          value: 'DGFY',
        },
      ],
    },
    {
      label: '质押周期',
      key: 'cycle',
      type: 'select',
      opts: [
        {
          label: '活期',
          value: 1,
        },
        {
          label: '10天',
          value: 10,
        },
        {
          label: '30天',
          value: 30,
        },

        {
          label: '90天',
          value: 90,
        },
        {
          label: '180天',
          value: 180,
        },
        {
          label: '365天',
          value: 365,
        },
      ],
    },
    {
      label: '订单状态',
      key: 'status',
      type: 'select',
      opts: [
        {
          label: '进行中',
          value: 1,
        },
        {
          label: '已完成',
          value: 2,
        },
      ],
    },
    {
      label: '内部号',
      key: 'is_internal',
      type: 'select',
      opts: [
        {
          label: '是',
          value: 1,
        },
        {
          label: '否',
          value: 0,
        },
      ],
    },
    // { label: '日期选择', key: 'date', type: 'datePickerRange' },
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
      <template v-if="column.dataIndex === 'status'">
        <a-space>
          {{ { 1: '进行中', 2: '已完成' }[text] || '-' }}
        </a-space>
      </template>
      <template v-if="column.dataIndex === 'compound'">
        <a-space>
          {{ text ? '是' : '否' }}
        </a-space>
      </template>
      <template v-if="column.dataIndex === 'type'">
        <a-space>
          {{ pledgeTypes[+text - 1].label }}
        </a-space>
      </template>
      <!-- <template v-if="column.dataIndex === 'user'">
        <a-space>
          {{ text.tron_address }}
        </a-space>
      </template> -->
      <!-- <template v-if="column.dataIndex === 'action'">
        <a-space>
          <a-button type="link" @click="addNew(record)">编辑</a-button>
          <a-popconfirm title="确定要删除吗" ok-text="确定" cancel-text="取消" @confirm="delRow(record.id)">
            <a-button type="link">删除</a-button>
          </a-popconfirm>
        </a-space>
      </template> -->
    </template>
  </a-table>
</template>
