export function useTable({ url, del_url = '', cols = [] }, method = 'DELETE', list_method = 'GET') {
  // POST_JSON
  const pagination = reactive({
    pageSize: 20,
    current: 1,
    total: 0,
    showTotal: (total) => `共 ${total} 条`,
  });

  const LIST_URL = url;
  const DEL_URL = del_url;

  const columns = ref(cols);
  const dataSource = ref([]);
  const params = reactive({
    page: 1,
    size: 20,
  });
  const queryParams = ref({});

  const loading = ref(false);
  const getData = async (query) => {
    loading.value = true;
    useHttp(LIST_URL, list_method, { ...params, ...queryParams.value, ...query })
      .then((res) => {
        const { data } = res;
        dataSource.value = data.list || [];
        pagination.total = data.count || data.total;
      })
      .catch((err) => {
        loading.value = false;
      })
      .finally(() => {
        loading.value = false;
      });
    //  pagination.total = res.count;
    //  dataSource.value = res.data || [];
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
    const res = await useHttp(`${DEL_URL}/${id}`, method);
    if (res.code === 200) {
      dataSource.value = dataSource.value.filter((item) => item.id !== id);
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
    queryParams,
  };
}
