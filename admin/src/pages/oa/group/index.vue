<script setup>
  import { useTable } from './table';
  import { ref } from 'vue';
  import AddModal from './AddModal.vue';
  const { columns, dataSource, getData, params, loading, pagination, changeTable, delRow } = useTable();
  const add_modal = ref();
  const edit = (form) => {
    add_modal.value.Init(form);
  };
  getData();
</script>
<template>
  <AddModal ref="add_modal" @success="getData"></AddModal>
  <a-table
    :columns="columns"
    :dataSource="dataSource"
    :loading="loading"
    :pagination="pagination"
    :rowKey="(record) => record.id"
    @change="changeTable"
  >
    <template #title>
      <div class="flex justify-between pr-4">
        <div>
          <a-button type="primary" @click="edit(null)" :loading="loading" style="margin-right: 20px">
            <template #icon>
              <PlusOutlined />
            </template>
            新增
          </a-button>
        </div>
      </div>
    </template>
    <template #bodyCell="{ text, record, column }">
      <template v-if="column.dataIndex === 'action'">
        <a-button class="text-xs ml-base" type="primary" size="small" @click="edit(record)">编辑</a-button>
        <!-- <a-button class="text-xs ml-base" type="primary" size="small" @click="edit(record)">成员管理</a-button> -->
        <!-- <a-button class="text-xs ml-base" type="primary" size="small" @click="edit(record)">权限管理</a-button> -->
        <!-- <a-popconfirm title="确认删除？" @confirm="remove(record)">
          <a-button class="text-xs ml-base" v-auth:delete danger size="small">删除</a-button>
        </a-popconfirm> -->
      </template>
      <template v-else-if="column.dataIndex === 'icon'">
        <component :is="record.icon" />
      </template>
    </template>
  </a-table>
</template>
<style lang="scss" scoped></style>
