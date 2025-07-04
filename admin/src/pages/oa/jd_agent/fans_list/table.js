import { ref, reactive } from 'vue';
import { usePost, useGet } from '@/utils/useHttp';
import { message } from 'ant-design-vue';

const tag = 'jd_agent';
const LIST_URL = `/oa/${tag}/my_fans_list`;
const SET_STATUS_URL = `/oa/${tag}/set_fans_status`;
export function useTable() {
  const pagination = reactive({
    pageSize: 20,
    current: 1,
    total: 4,
  });
  const columns = [
    { title: '客户号', dataIndex: 'whatsapp' },
    { title: '接待号', dataIndex: 'jd_account', customRender: ({ text }) => text.whatsapp },
    {
      title: '状态',
      dataIndex: 'kaihu_status',
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
    { title: '操作', dataIndex: 'action' },
  ];
  const dataSource = ref([]);
  const params = reactive({
    page: 1,
    size: 20,
    jd_account: '',
    kaihu_status: '',
    whatsapp: '',
  });
  const resetParams = () => {
    params.jd_account = '';
    params.kaihu_status = '';
    params.whatsapp = '';
  };

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

  const setStatus = (item, status) => {
    item.loading = true;
    usePost(SET_STATUS_URL, { id: item.id, status })
      .then((res) => {
        message.success('设置成功');
        getData();
      })
      .catch((err) => {
        console.log(err);
      })
      .finally(() => {
        item.loading = false;
      });
  };

  return {
    columns,
    dataSource,
    getData,
    params,
    loading,
    pagination,
    changeTable,
    setStatus,
  };
}
