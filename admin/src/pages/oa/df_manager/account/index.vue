<script setup>
  import { useTable } from './table';
  import { computed, ref } from 'vue';
  import AddModal from './AddModal.vue';
  import EditModal from './EditModal.vue';
  import SelectAgentModal from './SelectAgentModal.vue';
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

  const select_agent_modal = ref(null);
  const setAgent = () => {
    select_agent_modal.value.Init(selectedRowKeys.value);
  };
</script>
<template>
  <SelectAgentModal ref="select_agent_modal" @success="getData"></SelectAgentModal>

  <a-table
    :row-selection="{ selectedRowKeys: selectedRowKeys, onChange: onSelectChange }"
    :columns="columns"
    :dataSource="dataSource"
    :loading="loading"
    :pagination="pagination"
    :rowKey="(record) => record.id"
    @change="changeTable"
  >
    <template #title>
      <div class="flex pr-4">
        <!-- <div>
          <a-button type="primary" @click="add(null)" :loading="loading" style="margin-right: 20px">
            <template #icon>
              <PlusOutlined />
            </template>
            新增
          </a-button>
        </div> -->
        <div>
          <a-button
            type="primary"
            @click="setAgent(null)"
            :loading="loading"
            style="margin-right: 20px"
            :disabled="hasSelected == 0"
          >
            ({{ hasSelected }}) 分配给打粉专员
          </a-button>
        </div>
      </div>
    </template>
    <template #bodyCell="{ text, record, column }">
      <template v-if="column.dataIndex === 'action'">
        <!-- <a-button class="text-xs ml-base" type="primary" size="small" @click="edit(record)">编辑</a-button> -->
        <!-- <a-button class="text-xs ml-base" type="primary" size="small" @click="edit(record)">权限管理</a-button> -->
      </template>
      <template v-else-if="column.dataIndex === 'status'">
        <a-tag color="success" v-if="text == 1">可用</a-tag>
        <a-tag color="error" v-if="text == 0">不可用</a-tag>
      </template>
    </template>
  </a-table>
</template>
<style lang="scss" scoped></style>
