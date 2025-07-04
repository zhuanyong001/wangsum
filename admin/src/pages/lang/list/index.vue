<script setup>
  import { useTable } from './table';
  import { onMounted, ref } from 'vue';
  import AddModal from './AddModal.vue';
  const { columns, dataSource, getData, params, loading, pagination, changeTable, delRow } = useTable();
  const add_modal = ref();
  const addNew = (form) => {
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
          <a-button type="primary" @click="addNew(null)" :loading="loading" style="margin-right: 20px">
            <template #icon>
              <PlusOutlined />
            </template>
            新增
          </a-button>
        </div>
      </div>
    </template>
    <template #bodyCell="{ column, text, record }">
      <template v-if="column.dataIndex === 'action'">
        <a-space>
          <a-button type="link" @click="addNew(record)">编辑</a-button>
          <a-popconfirm title="确定要删除吗" ok-text="确定" cancel-text="取消" @confirm="delRow(record.id)">
            <a-button type="link">删除</a-button>
          </a-popconfirm>
        </a-space>
      </template>
    </template>
  </a-table>
</template>
