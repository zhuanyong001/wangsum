<script setup>
  import { useTable } from './table';
  import { computed, ref } from 'vue';
  import AddFanModal from './AddFanModal.vue';
  const { setStatus, columns, dataSource, getData, params, loading, pagination, changeTable } = useTable();

  getData();
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
        <div>
          <a-space style="margin-right: 20px">
            客户号:
            <a-input ref="select" placeholder="客户号" v-model:value="params.whatsapp" style="width: 120px"> </a-input>
            接待号:
            <a-input ref="select" placeholder="接待号" v-model:value="params.jd_account" style="width: 120px">
            </a-input>
            客户号状态:
            <a-select
              ref="select"
              placeholder="客户号状态"
              v-model:value="params.kaihu_status"
              style="width: 120px"
              allowClear
            >
              <a-select-option value="0">待接待</a-select-option>
              <a-select-option value="1">开户待审核</a-select-option>
              <a-select-option value="2">开户成功</a-select-option>
              <a-select-option value="-1">接待失败</a-select-option>
              <a-select-option value="-2">开户失败</a-select-option>
            </a-select>
          </a-space>
          <a-space>
            <a-button type="primary" @click="getData()" :loading="loading"> 查询 </a-button>
            <a-button @click="resetParams()" :loading="loading"> 重置 </a-button>
          </a-space>
        </div>
      </div>
    </template>
    <template #bodyCell="{ text, record, column }">
      <template v-if="column.dataIndex === 'kaihu_status'">
        <a-tag color="success" v-if="text == 2">开户成功</a-tag>
        <a-tag color="success" v-if="text == 1">开户待审核</a-tag>
        <a-tag color="error" v-if="text == -1">接待失败</a-tag>
        <a-tag color="error" v-if="text == -2">开户失败</a-tag>
        <a-tag v-if="text == 0">待接待</a-tag>
      </template>
      <template v-else-if="column.dataIndex === 'fans_stat'">
        <dl>
          <dt>推送总数：{{ text.count }}</dt>
          <dd>推送成功：{{ text.success_count }}</dd>
          <dd>开户成功：{{ text.kaihu_count }}</dd>
        </dl>
      </template>
      <template v-else-if="column.dataIndex === 'action'">
        <a-button class="text-xs ml-base" type="primary" size="small" @click="addFans(record)">备注</a-button>
        <a-button
          v-if="record.kaihu_status == 1"
          class="text-xs ml-base"
          type="primary"
          size="small"
          @click="setStatus(record, 2)"
          :loading="record.loading"
          >通过</a-button
        >
        <a-popconfirm title="确认？" @confirm="setStatus(record, -2)" v-if="record.kaihu_status == 1">
          <a-button class="text-xs ml-base" type="" size="small" danger :loading="record.loading">拒绝</a-button>
        </a-popconfirm>

        <!-- <a-button class="text-xs ml-base" type="primary" size="small" @click="edit(record)">权限管理</a-button> -->
      </template>
    </template>
  </a-table>
</template>
<style lang="scss" scoped></style>
