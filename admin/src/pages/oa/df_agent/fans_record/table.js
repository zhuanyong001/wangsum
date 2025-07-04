import { ref, reactive } from 'vue';
import { usePost, useGet } from '@/utils/useHttp';

const tag = 'df_agent';
const LIST_URL = `/oa/${tag}/fans_records`;
export function useTable() {
  const pagination = reactive({
    pageSize: 20,
    current: 1,
    total: 4,
  });
  const columns = [
    { title: '客户号', dataIndex: 'whatsapp' },
    {
      title: '状态',
      dataIndex: 'status',
      customRender: ({ text }) => {
        return { 1: '成功', '-1': '失败', 0: '待校验' }[text];
      },
    },
    {
      title: '备注',
      dataIndex: 'remark',
    },
    {
      title: '推送时间',
      dataIndex: 'created_at',
      //customRender: ({ text }) => dayjs(Number(text) * 1000).format('YYYY-MM-DD HH:mm:ss'),
    },
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
        dataSource.value = res.list || [];
        pagination.total = res.total;
      })
      .catch((err) => {})
      .finally(() => {
        loading.value = false;
      });
  };
  const changeTable = (page) => {
    const { current, pageSize } = page;
    pagination.current = current;
    pagination.pageSize = pageSize;
    params.page = current;
    params.size = pageSize;
    getData();
  };

  return {
    columns,
    dataSource,
    getData,
    params,
    loading,
    pagination,
    changeTable,
  };
}
