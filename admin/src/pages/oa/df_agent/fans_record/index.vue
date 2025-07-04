<script setup>
  import { useTable } from './table';
  import { computed, ref } from 'vue';
  const { banned, columns, dataSource, getData, params, loading, pagination, changeTable } = useTable();

  getData();
</script>
<template>
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
        <div>
          <a-button type="primary" @click="getData" :loading="loading" style="margin-right: 20px"> 刷新 </a-button>
        </div>
      </div>
    </template>
    <template #bodyCell="{ text, record, column }">
      <template v-if="column.dataIndex === 'status'">
        <a-tag color="success" v-if="text == 1">成功</a-tag>
        <a-tag color="error" v-if="text == -1">不可用</a-tag>
        <a-tag v-if="text == 0">待校验</a-tag>
        <span v-if="text == -1">{{ record.fail_reason || '' }}</span>
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
