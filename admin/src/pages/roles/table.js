import { ref, reactive } from 'vue';
import { usePost, useGet } from '@/utils/useHttp';

const tag = 'roles';
const LIST_URL = `/web3/roles`;
const DEL_URL = `/web3/roles/save/`;
export function useTable() {
  const pagination = reactive({
    pageSize: 20,
    current: 1,
    total: 4,
  });
  const columns = [
    { title: '标题', dataIndex: 'name' },
    {
      title: '添加时间',
      dataIndex: 'created_at',
      //customRender: ({ text }) => dayjs(Number(text) * 1000).format('YYYY-MM-DD HH:mm:ss'),
    },
    { title: '操作', dataIndex: 'action' },
  ];
  const dataSource = ref([]);
  const params = reactive({
    page: 1,
    size: 20,
  });
  const loading = ref(false);
  const getData = async () => {
    loading.value = true;
    useGet(LIST_URL, params)
      .then((res) => {
        dataSource.value = res.data.list || [];
        pagination.total = res.total;
      })
      .catch((err) => {});
    loading.value = false;
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
    const res = await usePost(`${DEL_URL}${id}`);
    if (res.code === 200) {
      // dataSource.value = dataSource.value.filter((item) => item.id !== id);
      getData()
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
