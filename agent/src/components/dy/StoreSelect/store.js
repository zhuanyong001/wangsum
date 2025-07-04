import { defineStore } from 'pinia';
export const useStoreSelectStore = defineStore('StoreSelect', () => {
  const list = ref([]);
  const getData = () => {
    if (list.value.length > 0) return;
    usePost('/recycle/store/list', { page: 1, size: 200, pid: -1, type: 2 }).then((res) => {
      if (res.code == 200) {
        list.value = res.data;
      }
    });
  };
  return {
    list,
    getData,
  };
});
