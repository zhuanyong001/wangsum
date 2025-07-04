import { defineStore } from 'pinia';
export const useBizStore = defineStore('biz_store', () => {
  const list = ref([]);
  const getData = () => {
    usePost('/biz/list', { page: 1, size: 200 }).then((res) => {
      list.value = res.data;
    });
  };
  return {
    list,
    getData,
  };
});
