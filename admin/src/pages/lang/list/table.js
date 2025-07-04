import { ref, reactive } from 'vue';
import { usePost } from '@/utils/useHttp';

import dayjs from 'dayjs';
import { message } from 'ant-design-vue';

const URL = '/i18n/langlist';
const DELURL = '/i18n/deletelang';
export function useTable() {
  const pagination = reactive({
    pageSize: 20,
    current: 1,
    total: 0,
  });
  const columns = [
    { title: '语言', dataIndex: 'name' },
    {
      title: '标识',
      dataIndex: 'key',
    },
    {
      title: '状态',
      dataIndex: 'status',
      customRender: ({ text }) => (text == 1 ? '开启' : '关闭'),
    },
    { title: '操作', dataIndex: 'action' },
  ];
  const dataSource = ref([]);
  const params = reactive({
    page: 1,
    size: 20,
    name: '',
    type: 0,
  });
  const loading = ref(false);
  const getData = async () => {
    loading.value = true;
    const res = await usePost(URL, params).catch((err) => {
      loading.value = false;
    });

    loading.value = false;
    pagination.total = res.langlist.length;
    dataSource.value = res.langlist || [];
  };
  const changeTable = (page) => {
    const { current, pageSize } = page;
    pagination.current = current;
    pagination.pageSize = pageSize;
    params.page = current;
    params.size = pageSize;
    getData();
  };
  const delRow = async (id) => {
    const res = await usePost(DELURL, { id });
    if (res.code === 0) {
      message.success('删除成功');
      getData();
    }
  };
  return {
    columns,
    dataSource,
    getData,
    params,
    loading,
    pagination,
    changeTable,
    delRow,
  };
}
