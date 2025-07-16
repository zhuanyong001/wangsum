<script setup>
  import { useTable } from '@/composables/useTable';
  import { useEdit } from './useEdit';
  import { freezes } from '../freeze.js';
  import SunLabel from '@/components/SunLabel/index.vue';
  import QueryFrom from '@/components/dy/QueryFrom/index.vue';
  import { Recharge } from '../rechange.js';
  import { calculateTotalValue } from '@/utils';
  import { useUserDetail } from '@/pages/people/userDetails.js';
  const { details, renderUser } = useUserDetail();
  const { queryParams, columns, dataSource, getData, params, loading, pagination, changeTable, delRow } = useTable(
    {
      url: '/web3/members/get_down_agents',
      del_url: '/web3/members/get_down_agents',
      cols: [
        {
          title: '用户',
          dataIndex: ['invitee', 'share_code'],
          customRender: (arg) => renderUser(arg, ['invitee', 'id']),
        },
        { title: '层级', dataIndex: 'level' },
        { title: '余额', dataIndex: 'balance' },
        { title: '会员等级', dataIndex: ['invitee', 'membership', 'name'] },
        { title: '直属下级', dataIndex: 'l1_count' },
        { title: '冻结状态', dataIndex: 'status' },

        // { title: '操作', dataIndex: 'action' },
      ],
    },
    null,
    'POST_JSON'
  );
  const props = defineProps({
    user_id: String | Number,
  });
  params.size = undefined;
  queryParams.value.level = 1;
  watch(
    () => props.user_id,
    (newValue) => {
      params.user_id = newValue;
      queryParams.value.user_id = newValue;
      getData(queryParams.value);
    },
    { immediate: true }
  );

  const getChildren = (expanded, record) => {
    console.log('====>>expanded', expanded, record);
    usePost('/web3/members/get_down_agents', { user_id: record.invitee_id, level: 1 }).then((res) => {
      record.children = res.data.list.map((item) => {
        item.level = record.level + 1;
        return item;
      });
    });
  };
  const emit = defineEmits(['close']);
  const freezeAll = () => {
    usePost('/web3/members/freeze_all_down_agent', { user_id: props.user_id }).then((res) => {
      if (res.code === 200) {
        message.success('冻结成功');
        getData(queryParams.value);
      }
      emit('close');
    });
  };
</script>
<template>
  <div>
    <!-- <query-from :formConfig="formConfig" v-model="queryParams" @finish="finish"></query-from> -->
    <div style="margin-bottom: 10px; display: flex; justify-content: space-between; align-items: center">
      <sun-label label="总数">{{ pagination.total }}</sun-label>
      <a-button @click="freezeAll">冻结整条线的下级</a-button>
    </div>
    <a-table
      size="small"
      :columns="columns"
      :dataSource="dataSource"
      :loading="loading"
      :pagination="false"
      :rowKey="(record) => record.id"
      @change="changeTable"
      @expand="getChildren"
    >
      <template #bodyCell="{ column, text, record }">
        <template v-if="column.dataIndex === 'balance'">
          {{ calculateTotalValue(record.assets) }}
        </template>
        <template v-if="column.dataIndex === 'status'">
          <a-tag :color="record.invitee?.status === 1 ? 'green' : 'red'">{{
            record.invitee?.status === 1 ? '正常' : '冻结'
          }}</a-tag>
        </template>
        <template v-if="column.dataIndex === 'action'">
          <a-space>
            <!-- <a-button type="link" @click="recharge(record)">充值</a-button>
            <a-popconfirm :title="`确定要${record.status===1?'冻结':'解冻'}吗`" ok-text="确定" cancel-text="取消" @confirm="freeze({user_id:record.id,type:record.status})">
              <a-button type="link" :disabled="record.status!==1">{{record.status===1?'冻结':'解冻'}}</a-button>
            </a-popconfirm> -->
            <!-- <a-button type="link" @click="LooksubordinateProxy(record.id)">下级</a-button> -->
          </a-space>
        </template>
      </template>
    </a-table>
  </div>
</template>
