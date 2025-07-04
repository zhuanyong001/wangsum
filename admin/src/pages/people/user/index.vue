<script setup>
  import { useTable } from '@/composables/useTable';
  import { useEdit } from './useEdit';
  import { Recharge, useMember } from '../rechange.js';
  import { freezes, useChangeState } from '../freeze.js';
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
      {
        title: '备注',
        dataIndex: 'remark',
        width: 200,
      },
      { title: '余额', dataIndex: 'balance' },
      { title: '会员等级', dataIndex: 'membership_level', align: 'center' },
      { title: '状态', dataIndex: 'status' },
      { title: '是否内部号', dataIndex: 'is_internal' },
      { title: '兑换', dataIndex: 'can_exchange' },
      { title: '存款返利', dataIndex: 'is_ln_rebate' },
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
  const { setLevel, setRemark } = useMember({ getData });
  const { freeze } = freezes({ getData });
  const { addAC } = addAcount({ getData });
  const { Set_can_exchange, Set_is_ln_rebate, Setis_internal } = useChangeState({ getData });
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
    <a-button type="primary" @click="addAC">添加虚拟用户</a-button>
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
          <a-tag>{{ record.membership.name || '无' }}</a-tag>
        </template>
        <template v-if="column.dataIndex === 'status'">
          <a-tag :color="text === 1 ? 'blue' : 'red'">{{ text === 1 ? '正常' : '冻结' }}</a-tag>
        </template>
        <template v-if="column.dataIndex === 'is_internal'">
          <a-popconfirm
            :title="`确定要${record.is_internal === 0 ? '设置' : '解除'}内部号吗`"
            ok-text="确定"
            cancel-text="取消"
            @confirm="Setis_internal({ user_id: record.id, type: record.is_internal === 0 ? 1 : 0 })"
          >
            <a-button type="link"> {{ text === 1 ? '是' : '否' }}</a-button>
          </a-popconfirm>
        </template>
        <template v-if="column.dataIndex === 'can_exchange'">
          <a-popconfirm
            :title="`确定要${record.can_exchange === 1 ? '设置' : '解除'}禁止兑换`"
            ok-text="确定"
            cancel-text="取消"
            @confirm="Set_can_exchange({ user_id: record.id, type: record.can_exchange === 0 ? 1 : 0 })"
          >
            <a-button type="link" :danger="text === 0"> {{ text === 0 ? '禁止' : '开启' }}</a-button>
          </a-popconfirm>
        </template>
        <template v-if="column.dataIndex === 'is_ln_rebate'">
          <a-popconfirm
            :title="`确定要${record.is_ln_rebate === 0 ? '开启' : '关闭'}多级存款生息返利`"
            ok-text="确定"
            cancel-text="取消"
            @confirm="Set_is_ln_rebate({ user_id: record.id, type: record.is_ln_rebate === 0 ? 1 : 0 })"
          >
            <a-button type="link" :danger="text === 0"> {{ text === 0 ? '关闭' : '开启' }}</a-button>
          </a-popconfirm>
        </template>
        <template v-if="column.dataIndex === 'action'">
          <div style="max-width: 300px">
            <a-space>
              <!-- <a-button type="link" @click="details(record)">详情</a-button> -->
              <a-button type="link" @click="recharge(record)">充值</a-button>
              <a-button type="link" @click="setLevel(record)">设置会员等级</a-button>
              <a-button type="link" @click="setRemark(record)">备注</a-button>
            </a-space>

            <a-space>
              <a-popconfirm
                :title="`确定要${record.status === 1 ? '冻结' : '解冻'}吗`"
                ok-text="确定"
                cancel-text="取消"
                @confirm="freeze({ user_id: record.id, type: record.status })"
              >
                <a-button type="link">{{ record.status === 1 ? '冻结' : '解冻' }}</a-button>
              </a-popconfirm>
              <a-button type="link" @click="showFinancialModal(record.id)"> 账变 </a-button>
            </a-space>
            <a-space>
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
