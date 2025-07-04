<template>
  <div>
    <a-select ref="select" v-model:value="value" placeholder="请选择站点" :style="{ width: width }" allowClear>
      <a-select-option :value="item.id" v-for="(item, i) in data_list" :key="i">{{ item.name }} </a-select-option>
    </a-select>
  </div>
</template>
<script setup>
  import { useBizStore } from './store.js';
  const { getData } = useBizStore();
  const { list } = storeToRefs(useBizStore());

  const props = defineProps({
    modelValue: {
      type: [Number, String],
      default: '',
    },
    width: {
      type: [Number, String],
      default: '100%',
    },
    biz_type: {
      type: [Number, String],
      default: '99',
    },
  });
  const emit = defineEmits(['update:modelValue']);
  const value = computed({
    get: () => props.modelValue,
    set: (val) => emit('update:modelValue', val),
  });

  const data_list = computed(() => {
    if (props.biz_type == 99) return list.value;
    return list.value.filter((item) => item.biz_types.indexOf(props.biz_type) > -1);
  });
  watch(
    () => props.biz_type,
    () => {
      if (value.value) {
        if (data_list.value.findIndex((item) => item.id == value.value) == -1) {
          value.value = '';
        }
      }
    }
  );

  // const list = ref([]);
  // const getData = () => {
  //   usePost('/biz/list', { page: 1, size: 200 }).then((res) => {
  //     list.value = res.data;
  //   });
  // };
  getData();
</script>
<style lang="scss" scoped></style>
