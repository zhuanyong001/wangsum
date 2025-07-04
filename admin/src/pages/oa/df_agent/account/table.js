import { ref, reactive } from 'vue';
import { usePost, useGet } from '@/utils/useHttp';

const tag = 'df_agent';
const LIST_URL = `/oa/${tag}/account_list`;
export function useTable() {
  const pagination = reactive({
    pageSize: 20,
    current: 1,
    total: 4,
  });
  const columns = [
    { title: '接待号', dataIndex: 'whatsapp' },
    {
      title: '状态',
      dataIndex: 'status',
      customRender: ({ text }) => {
        return ['不可用', '可用'][text];
      },
    },
    {
      title: '添加者',
      dataIndex: 'admin',
      customRender: ({ text }) => {
        return text?.username || '';
      },
    },
    {
      title: '接待业务员',
      dataIndex: 'jd_agent',
      customRender: ({ text }) => {
        return text?.username || '';
      },
    },
    {
      title: '客户信息',
      dataIndex: 'fans_stat',
    },
    {
      title: '备注',
      dataIndex: 'remark',
    },
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
        dataSource.value = res.list || [];
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
