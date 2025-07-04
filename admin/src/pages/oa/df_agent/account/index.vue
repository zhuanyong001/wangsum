<script setup>
  import { useTable } from './table';
  import { computed, ref } from 'vue';
  import AddFanModal from './AddFanModal.vue';
  const { banned, columns, dataSource, getData, params, loading, pagination, changeTable } = useTable();

  getData();

  const selectedRowKeys = ref([]);
  const onSelectChange = (RowKeys) => {
    selectedRowKeys.value = RowKeys;
  };
  const hasSelected = computed(() => {
    return selectedRowKeys.value.length;
  });
  const add_fans_modal = ref();
  const addFans = (item) => {
    add_fans_modal.value.Init(item);
  };
</script>
<template>
  <AddFanModal ref="add_fans_modal" @success="getData"></AddFanModal>
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
        <a-button class="text-xs ml-base" type="primary" size="small" @click="addFans(record)">推送客户</a-button>
        <!-- <a-button class="text-xs ml-base" type="primary" size="small" @click="edit(record)">权限管理</a-button> -->
      </template>
      <template v-else-if="column.dataIndex === 'status'">
        <a-tag color="success" v-if="text == 1">可用</a-tag>
        <a-tag color="error" v-if="text == 0">不可用</a-tag>
      </template>
      <template v-else-if="column.dataIndex === 'fans_stat'">
        <dl>
          <dt>推送总数：{{ text.count }}</dt>
          <dd>推送成功：{{ text.success_count }}</dd>
          <dd>开户成功：{{ text.kaihu_count }}</dd>
        </dl>
      </template>
    </template>
  </a-table>
</template>
<style lang="scss" scoped></style>
