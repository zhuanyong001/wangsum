
import { ref } from 'vue';
export function usePagination(
  props,
  emit,
  LIMIT_SIZE = 10
) {
  const loading = ref(true);
  const isLoading = ref(false);
  const finished = ref(false);
  const error = ref(false);
  const empty = ref(false)
  const YuPage = ref(1);
  const Yulimit = ref(LIMIT_SIZE);

  const init = () => {
    YuPage.value = 1;
    finished.value = false;
    loading.value = true;
    error.value = false;
    loadData();
  };

  const reload = () => {
    init();
  };

  const loadData = () => {
    if (isLoading.value || finished.value) return;
    const { page, limit, ...others } = props.otherParams;
    isLoading.value = true;
    emit('query', { page: YuPage.value, limit: Yulimit.value, ...others });
  };

  const complete = (data) => {
    isLoading.value = false;
    if (data === false) {
      error.value = true;
      loading.value = false;
      return;
    }

    if (data.length < LIMIT_SIZE) {
      loading.value = false;
      if(data.length === 0&&YuPage.value === 1){
        empty.value = true
      }else{
        finished.value = true;
      }
    }

    if (YuPage.value === 1) {
      emit('update:modelValue', data);
    } else {
      const newDataList = [...props.modelValue, ...data];
      emit('update:modelValue', newDataList);
    }

    YuPage.value += 1;
  };
  const endStatus = computed(()=>{
    return error.value || empty.value || finished.value
  })

  init()
  return {
    loading,
    isLoading,
    endStatus,
    finished,
    error,
    empty,
    reload,
    loadData,
    complete
  };
}
