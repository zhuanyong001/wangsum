<template>
  <div>
    <a-select ref="select" v-model:value="value" placeholder="请选择业务类型" :style="{ width: width }" allowClear>
      <a-select-option :value="item.id" v-for="(item, i) in list" :key="i">{{ item.name }} </a-select-option>
    </a-select>
  </div>
</template>
<script setup>
  import { useStoreSelectStore } from './store.js';

  const { list } = storeToRefs(useStoreSelectStore());

  const props = defineProps({
    modelValue: {
      type: [Number, String],
      default: '',
    },
    width: {
      type: [Number, String],
      default: '100%',
    },
  });
  const emit = defineEmits(['update:modelValue']);
  const value = computed({
    get: () => props.modelValue,
    set: (val) => emit('update:modelValue', val),
  });
  // const list = ref([]);
  // const getData = () => {
  //   usePost('/recycle/store/list', { page: 1, size: 200, pid: -1, type: 2 }).then((res) => {
  //     list.value = res.data;
  //   });
  // };
  useStoreSelectStore().getData();
</script>
<style lang="scss" scoped></style>
