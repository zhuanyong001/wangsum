<script setup>
  import { useTable } from '@/composables/useTable';
  import { useEdit, pledgeList, currencyList, types } from './useEdit';
  import QueryFrom from '@/components/dy/QueryFrom/index.vue';
  const { queryParams, columns, dataSource, getData, params, loading, pagination, changeTable, delRow } = useTable({
    url: '/web3/loan-pools/list',
    del_url: '/web3/loan-pools',
    cols: [
      { title: '借贷名称', dataIndex: 'name' },
      { title: '借贷币种', dataIndex: 'loan_coin_ids' },
      { title: '质押币种', dataIndex: 'pledge_coin_ids' },
      { title: '矿池状态', dataIndex: 'status' },
      { title: '操作', dataIndex: 'action' },
    ],
  });
  const { addNew } = useEdit({ getData });
  getData();

  function getPledgeNames(arr) {
    const Names = arr.map((id) => {
      return pledgeList.value.find((item) => item.value === id).label;
    });
    return Names.join('/');
  }
  function getCoinNames(arr) {
    const Names = arr.map((id) => {
      return currencyList.value.find((item) => item.value === id).label;
    });
    return Names.join('/');
  }

  const formConfig = computed(() => [
    {
      label: '类型',
      key: 'cate',
      type: 'select',
      opts: [{ value: 0, label: '全部' }, ...types.value],
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
      <query-from :formConfig="formConfig" v-model="queryParams" @finish="finish"></query-from>
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
      <template v-if="column.dataIndex === 'loan_coin_ids' || column.dataIndex === 'pledge_coin_ids'">
        <a-space>
          {{ getCoinNames(text) }}
        </a-space>
      </template>
      <template v-if="column.dataIndex === 'cycle'">
        <a-space>
          {{ getPledgeNames(text) }}
        </a-space>
      </template>
      <template v-if="column.dataIndex === 'status'">
        <a-space>
          {{ text ? '开启' : '关闭' }}
        </a-space>
      </template>
      <template v-if="column.dataIndex === 'cate'">
        <a-space>
          {{ types[text - 1].label }}
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
