import { defineStore } from 'pinia';
import { ref, reactive } from 'vue';
import { usePost, useGet } from '@/utils/useHttp';

export const useFormItemStore = defineStore('form_item_store', () => {
  const groups = ref([]);
  const getAllGroup = () => {
    useGet('/oa/group/list').then((res) => {
      groups.value = res.list || [];
      console.log(res);
    });
  };

  //我管理的业务员
  const my_jd_agents = ref([]);
  const getMyJDAgent = () => {
    useGet('/oa/jd_manager/get_my_jd_agents').then((res) => {
      console.log(res);
      my_jd_agents.value = res || [];
    });
  };

  //我管理的df业务员
  const my_df_agents = ref([]);
  const getMyDFAgent = () => {
    useGet('/oa/df_manager/get_my_df_agents').then((res) => {
      console.log(res);
      my_df_agents.value = res || [];
    });
  };

  return {
    getAllGroup,
    groups,
    my_jd_agents,
    getMyJDAgent,
    my_df_agents,
    getMyDFAgent,
  };
});
