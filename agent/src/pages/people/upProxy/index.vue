<script setup>
  import { useTable } from '@/composables/useTable';
  import { useEdit } from './useEdit';
  import SunLabel from '@/components/SunLabel/index.vue';
  import { Recharge } from '../rechange.js';
  import { calculateTotalValue } from '@/utils';
  import { useUserDetail } from '@/pages/people/userDetails.js';
  const { details, renderUser } = useUserDetail();
  const { queryParams, columns, dataSource, getData, params, loading, pagination, changeTable, delRow } = useTable(
    {
      url: '/web3/members/get_up_agents',

      del_url: '/web3/members/get_down_agents',
      cols: [
        { title: '地址', dataIndex: ['inviter', 'tron_address'] },
        { title: '层级', dataIndex: 'level' },
        {
          title: '用户',
          dataIndex: ['inviter', 'share_code'],
          customRender: (arg) => renderUser(arg, ['inviter', 'id']),
        },
        { title: '余额', dataIndex: 'balance' },
        { title: '会员等级', dataIndex: ['inviter', 'membership_level'] },

        // { title: '操作', dataIndex: 'action' },
      ],
    },
    null,
    'POST_JSON'
  );
  const { LooksubordinateProxy } = useEdit({ getData });
  const { recharge } = Recharge({ getData });
  const props = defineProps({
    user_id: String | Number,
  });

  watch(
    () => props.user_id,
    (newValue) => {
      queryParams.value.user_id = newValue;
      getData(queryParams.value);
    },
    { immediate: true }
  );
</script>
<template>
  <div>
    <!-- <query-from
      :formConfig="formConfig"
      v-model="queryParams"
      @finish="finish"
    ></query-from> -->
    <sun-label label="总数">{{ pagination.total }}</sun-label>
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
        <template v-if="column.dataIndex === 'action'">
          <a-space>
            <a-button type="link" @click="details(record.inviter)">详情</a-button>
            <!-- <a-button type="link" @click="recharge(record)">充值</a-button> -->
            <!-- <a-button type="link" @click="LooksubordinateProxy(record.id)">下级</a-button> -->
          </a-space>
        </template>
      </template>
    </a-table>
  </div>
</template>
