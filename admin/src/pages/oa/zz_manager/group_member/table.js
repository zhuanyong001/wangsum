import { ref, reactive } from 'vue';
import { usePost, useGet } from '@/utils/useHttp';

const tag = 'zz_manager';
const LIST_URL = `/oa/${tag}/group_member_list`;
const BAN_URL = `/oa/${tag}/banned`;
export function useTable() {
  const pagination = reactive({
    pageSize: 20,
    current: 1,
    total: 4,
  });
  const columns = [
    { title: '用户名', dataIndex: 'username' },
    {
      title: '用户类型',
      dataIndex: 'role_type',
      customRender: ({ text }) => {
        return { zz: '组长管理员', jd: '接待管理员', df: '打粉管理员', agent_jd: '接待业务员', agent_df: '打粉业务员' }[
          text
        ];
      },
    },
    {
      title: '分组',
      dataIndex: 'oa_group',
      customRender: ({ text }) => {
        return text?.name || '无';
      },
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
  const banned = async (item) => {
    const res = await usePost(BAN_URL, { id: item.id });
    item.status = item.status === 1 ? 0 : 1;
  };

  return {
    columns,
    dataSource,
    getData,
    params,
    loading,
    pagination,
    changeTable,
    banned,
  };
}
