<script setup>
  import { useTable } from '@/composables/useTable';
  import { useEdit } from './useEdit';
  import { Recharge, useMember } from '../rechange.js';
  import { freezes, Setinternalnumber } from '../freeze.js';
  import PeopleProxy from '../proxy/index.vue';
  import PeopleupProxy from '../upProxy/index.vue';
  import QueryFrom from '@/components/dy/QueryFrom/index.vue';
  import { useUserDetail } from '@/pages/people/userDetails.js';
  import { addAcount } from './addAcount.js';
  import { calculateTotalValue } from '@/utils';
  import { useFinancialCenter } from '@/pages/financialCenter/detailed/useModal';
  import emitter from '@/utils/bus';
  const { details, renderUser } = useUserDetail();
  const { queryParams, columns, dataSource, getData, params, loading, pagination, changeTable, delRow } = useTable({
    url: '/web3/members/list',
    del_url: '/web3/members/list',
    cols: [
      { title: '地址', dataIndex: 'tron_address' },
      {
        title: '用户',
        dataIndex: 'share_code',
        customRender: ({ text, record }) => renderUser({ text, record: { user: record } }),
      },
      { title: '余额', dataIndex: 'balance' },
      { title: '会员等级', dataIndex: 'membership_level', align: 'center' },
      // { title: '状态', dataIndex: 'status' },
      {
        title: '最后登录',
        dataIndex: 'last_login_ip',
        customRender: ({ text }) => {
          return text ? h('div', [h('p', text.ip), h('p', text.region)]) : '';
        },
      },
      { title: '创建时间', dataIndex: 'created_at' },
      { title: '操作', dataIndex: 'action', align: 'center' },
    ],
  });
  const { LookProxy, open, user_id, drawerStatus } = useEdit({ getData });
  const { recharge } = Recharge({ getData });
  const { setLevel } = useMember({ getData });
  const { freeze } = freezes({ getData });
  const { addAC } = addAcount({ getData });
  const { Setis_internal } = Setinternalnumber({ getData });
  getData(queryParams.value);

  const formConfig = computed(() => [
    {
      label: '用户地址',
      key: 'tron_address',
      type: 'text',
    },
    {
      label: '用户类型',
      key: 'is_internal',
      type: 'select',
      opts: [
        {
          label: '全部',
          value: -1,
        },
        {
          label: '非内部号',
          value: 0,
        },
        {
          label: '内部号',
          value: 1,
        },
      ],
      default: -1,
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

  const memberList = ['', '矿工', '高级矿工', '轻节点', '全节点'];

  const { showFinancialModal } = useFinancialCenter();
  onMounted(() => {
    emitter.on('new_u_message', (res) => {
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
      <template #bodyCell="{ column, text, record }">
        <template v-if="column.dataIndex === 'balance'">
          {{ calculateTotalValue(record.assets) }}
        </template>
        <template v-if="column.dataIndex === 'membership_level'">
          <a-tag>{{ memberList[text] || '无' }}</a-tag>
        </template>
        <template v-if="column.dataIndex === 'status'">
          <a-tag :color="text === 1 ? 'blue' : 'red'">{{ text === 1 ? '正常' : '冻结' }}</a-tag>
        </template>
        <template v-if="column.dataIndex === 'is_internal'">
          <a-tag :color="text === 1 ? 'blue' : 'red'">{{ text === 1 ? '是' : '否' }}</a-tag>
        </template>
        <template v-if="column.dataIndex === 'action'">
          <div style="max-width: 300px">
            <a-space>
              <a-button type="link" @click="showFinancialModal(record.id)"> 账变 </a-button>
              <a-button type="link" @click="LookProxy(record.id, 0)">下级</a-button>
              <a-button type="link" @click="LookProxy(record.id, 1)">上级</a-button>
            </a-space>
          </div>
        </template>
      </template>
    </a-table>
    <a-drawer
      v-model:visible="open"
      class="custom-class"
      root-class-name="root-class-name"
      :root-style="{ color: 'blue' }"
      style="color: red"
      :title="drawerStatus.name"
      placement="right"
      size="large"
    >
      <people-proxy v-if="drawerStatus.name === '下级'" :user_id="user_id" :drawerStatus="drawerStatus"></people-proxy>
      <peopleup-proxy v-else :user_id="user_id" :drawerStatus="drawerStatus"></peopleup-proxy>
    </a-drawer>
  </div>
</template>
