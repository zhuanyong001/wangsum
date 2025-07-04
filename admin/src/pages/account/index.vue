<script setup>
  import { useTable } from '@/composables/useTable';
  import { useEdit,roleList } from './useEdit';
  import GgModel from './GgModel.vue'

  const GgModelRef = ref()
  const isModalVisible = ref(false);


  const { columns, dataSource, getData, params, loading, pagination, changeTable, delRow } = useTable({
    url: '/web3/roles/admin_list',
    del_url: '/web3/roles/admin_list',
    cols: [
  { title: '用户 ID', dataIndex: 'user_id' },
  { title: '关联角色', dataIndex: 'role_id' },
  { title: '是否超级账号', dataIndex: 'is_super' },
  { title: '用户名', dataIndex: 'username' },
  { title: '创建时间', dataIndex: 'created_at' },
  { title: '更新时间', dataIndex: 'updated_at' },
  { title: '操作', dataIndex: 'action' }
],
  });
  const { addNew } = useEdit({ getData });
  getData();
</script>
<template>
  <GgModel
  ref="GgModelRef"
  v-model:visible="isModalVisible" 
  title="谷歌密钥操作台"
  ></GgModel>
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
      <template v-if="column.dataIndex === 'is_super'">
        {{ text===1?'是':'否' }}
      </template>
      <template v-if="column.dataIndex === 'role_id'">
        {{ roleList.find(item=>item.value===text).label }}
      </template>
      <template v-if="column.dataIndex === 'action'">
        <a-space>
          <a-button type="link" @click="addNew(record)">编辑</a-button>
          <!-- <a-popconfirm title="确定要删除吗" ok-text="确定" cancel-text="取消" @confirm="delRow(record.id)">
            <a-button type="link">删除</a-button>
          </a-popconfirm> -->
          <a-button type="link" @click="GgModelRef.showModel(record)">谷歌</a-button>
        </a-space>
      </template>
    </template>
  </a-table>
</template>
