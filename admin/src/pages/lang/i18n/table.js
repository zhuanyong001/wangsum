import { ref, reactive, computed } from 'vue';
import { usePost, useHttp } from '@/utils/useHttp';
import { message } from 'ant-design-vue';
const URL = '/i18n/list';
export function useI18nTable() {
  const params = reactive({
    page: '',
    search: '',
  });
  const pagination = reactive({
    pageSize: 20,
    current: 1,
    total: 0,
  });

  const columns = computed(() => {
    const cols = [
      {
        title: '页面',
        dataIndex: 'page',
        is_edit: 1,
        fixed: 'left',
        width: 100,
      },
      {
        title: '字段',
        dataIndex: 'i18nkey',
        is_edit: 1,
        width: 150,
        fixed: 'left',
      },
      {
        title: '操作',
        dataIndex: 'action',
        fixed: 'right',
        width: 150,
      },
    ];
    langlist.value.forEach((element) => {
      if (element.show == true) {
        cols.splice(2, 0, {
          title: element.name,
          dataIndex: element.key,
          is_edit: 1,
          width: 150,
        });
      }
    });
    return cols;
  });
  const loading = ref(false);
  const langlist = ref([]);
  const dataSource = ref([]);
  const pagesOptions = ref([]);
  const show_arr = ref([]);
  const getData = () => {
    loading.value = true;
    usePost(URL, params)
      .then((res) => {
        loading.value = false;
        pagination.total = res.total || 0;
        pagination.current = 1;
        dataSource.value = res.list || [];
        pagesOptions.value = res.page;
        if (langlist.value.length == 0) {
          langlist.value = res.langlist.map((item) => {
            item.show = true;
            return item;
          });
          show_arr.value = langlist.value.map((item) => {
            return item.id;
          });
        }
      })
      .catch((err) => {
        loading.value = false;
      })
      .finally(() => {});
  };
  const delRow = (record) => {
    const { id } = record;
    record.loading = true;
    useHttp(`/i18n/${id}`, 'DELETE')
      .then(() => {
        dataSource.value = dataSource.value.filter((item) => item.id != id);
      })
      .catch((err) => {
        message.error('删除失败');
      })
      .finally(() => {
        record.loading = false;
      });
  };

  return {
    getData,
    columns,
    dataSource,
    pagination,
    loading,
    delRow,
    pagesOptions,
    params,
    langlist,
    show_arr,
  };
}
