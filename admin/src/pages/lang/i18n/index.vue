<script setup>
  import { useI18nTable } from './table';
  import ImportModal from './ImportModal.vue';
  import { onMounted, ref, reactive, watch } from 'vue';
  import { cloneDeep } from 'lodash-es';
  import { usePost } from '@/utils/useHttp';
  import { message } from 'ant-design-vue';
  const { show_arr, langlist, columns, dataSource, getData, params, pagesOptions, loading, pagination, delRow } =
    useI18nTable();
  let newRecord = reactive({});
  const addRow = (record) => {
    record.loading = true;
    usePost('/i18n/save', { ...record })
      .then((res) => {
        if (record.id) {
          delete editableData[record.id];
        } else {
          getData();
          newRecord = reactive({});
        }
        message.success('操作成功');
      })
      .catch((err) => {
        console.log(err);
        message.error('异常，请重试');
      })
      .finally(() => {
        record.loading = false;
      });
  };
  const editableData = reactive({});
  const setEdit = (id) => {
    editableData[id] = cloneDeep(dataSource.value.filter((item) => id == item.id)[0]);
    console.log(editableData);
  };
  const save = (id) => {
    Object.assign(dataSource.value.filter((item) => id === item.id)[0], editableData[id]);
    addRow(editableData[id]);
  };
  const cancel = (key) => {
    delete editableData[key];
  };
  const handleChangePage = (e) => {
    params.page = e;
  };
  const onSearch = (e) => {
    params.page = e;
  };
  getData();
  const handleShowLangChange = (e) => {
    langlist.value.forEach((item) => {
      if (e.includes(item.id)) {
        item.show = true;
      } else {
        item.show = false;
      }
    });
  };
  const changeTable = (p, filters, sorter, extra) => {
    pagination.current = p.current;
    pagination.pageSize = p.pageSize;
  };
  const import_modal = ref(null);
  const ImportModalShow = () => {
    import_modal.value.Init(langlist.value);
  };
</script>
<template>
  <ImportModal ref="import_modal"></ImportModal>
  <a-table
    :columns="columns"
    :dataSource="dataSource"
    :loading="loading"
    :pagination="pagination"
    :rowKey="(record) => record.id"
    @change="changeTable"
    sticky
    :scroll="{ x: 1500, y: 400 }"
  >
    <template #title>
      <div class="flex justify-between pr-4">
        <div>
          <a-space>
            页面筛选:
            <a-select :allowClear="true" style="width: 220px" v-model:value="params.page">
              <a-select-option v-bind:key="item.page" v-for="item in pagesOptions" :value="item.page">{{
                item.page
              }}</a-select-option>
            </a-select>
            <a-input-search
              v-model:value="params.search"
              style="margin-left: 10px; width: 280px"
              placeholder="搜索文字"
              @search="getData"
              enterButton
            />
            <a-select
              v-model:value="show_arr"
              style="margin-left: 10px"
              mode="multiple"
              placeholder="Please select"
              @change="handleShowLangChange"
            >
              <a-select-option v-bind:key="item.id" v-for="item in langlist" :value="item.id">{{
                item.name
              }}</a-select-option>
            </a-select>
          </a-space>
        </div>
      </div>
      <div class="flex justify-between pr-4 mt-4">
        <a-button type="primary" @click="ImportModalShow(null)" :loading="loading" style="margin-right: 20px">
          <template #icon>
            <PlusOutlined />
          </template>
          导入
        </a-button>
      </div>
    </template>
    <template #summary>
      <a-table-summary :fixed="'top'">
        <a-table-summary-row>
          <a-table-summary-cell v-for="(col, index) in columns" :index="index" :key="index">
            <template v-if="col.dataIndex == 'action'">
              <a-button type="link" @click="addRow(newRecord)">新增</a-button>
            </template>
            <template v-else>
              <a-input v-model:value="newRecord[col.dataIndex]" :placeholder="col.title"></a-input>
            </template>
          </a-table-summary-cell>
        </a-table-summary-row>
      </a-table-summary>
    </template>
    <template #bodyCell="{ column, text, record }">
      <template v-if="column.is_edit">
        <div>
          <a-input
            v-if="editableData[record.id]"
            v-model:value="editableData[record.id][column.dataIndex]"
            style="margin: -5px 0"
          />
          <template v-else>
            {{ text }}
          </template>
        </div>
      </template>
      <template v-if="column.dataIndex === 'action'">
        <a-space>
          <template v-if="editableData[record.id]">
            <a-button type="link" @click="save(record.id)" :loading="editableData[record.id].loading">保存</a-button>
            <a-popconfirm title="确定取消吗" @confirm="cancel(record.id)">
              <a>取消</a>
            </a-popconfirm>
          </template>
          <template v-else>
            <a-button type="link" @click="setEdit(record.id)">编辑</a-button>
            <a-popconfirm title="确定要删除吗" ok-text="确定" cancel-text="取消" @confirm="delRow(record)">
              <a-button type="link" :loading="record.loading">删除</a-button>
            </a-popconfirm>
          </template>
        </a-space>
      </template>
    </template>
  </a-table>
</template>
