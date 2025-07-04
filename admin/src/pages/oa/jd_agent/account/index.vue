<script setup>
  import { useTable } from './table';
  import { computed, ref } from 'vue';
  import EditModal from './EditModal.vue';
  const { banned, columns, dataSource, getData, params, loading, pagination, changeTable } = useTable();
  const add_modal = ref();
  const edit_modal = ref();
  const edit = (form) => {
    edit_modal.value.Init(form);
  };
  const add = (form) => {
    add_modal.value.Init(form);
  };
  getData();

  const selectedRowKeys = ref([]);
  const onSelectChange = (RowKeys) => {
    selectedRowKeys.value = RowKeys;
  };
  const hasSelected = computed(() => {
    return selectedRowKeys.value.length;
  });
</script>
<template>
  <EditModal ref="edit_modal" @success="getData"></EditModal>

  <a-table
    :columns="columns"
    :dataSource="dataSource"
    :loading="loading"
    :pagination="pagination"
    :rowKey="(record) => record.id"
    @change="changeTable"
  >
    <template #title>
      <div class="flex pr-4">
        <div></div>
      </div>
    </template>
    <template #bodyCell="{ text, record, column }">
      <template v-if="column.dataIndex === 'action'">
        <a-button class="text-xs ml-base" type="primary" size="small" @click="edit(record)">编辑</a-button>
        <!-- <a-button class="text-xs ml-base" type="primary" size="small" @click="edit(record)">权限管理</a-button> -->
      </template>
      <template v-else-if="column.dataIndex === 'status'">
        <a-tag color="success" v-if="text == 1">可用</a-tag>
        <a-tag color="error" v-if="text == 0">不可用</a-tag>
      </template>
      <template v-else-if="column.dataIndex === 'fans_stat'">
        <dl>
          <dt>总数：{{ text.count }}</dt>
          <dd>待接待：{{ text.wait_count }}</dd>
          <dd>开户成功：{{ text.kaihu_count }}</dd>
        </dl>
      </template>
    </template>
  </a-table>
</template>
<style lang="scss" scoped></style>
