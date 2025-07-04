<template>
  <div>
    <a-select ref="select" v-model:value="value" :style="{ width: width }" allowClear>
      <a-select-option :value="item.id" v-for="(item, i) in groups" :key="i">{{ item.name }} </a-select-option>
    </a-select>
  </div>
</template>
<script setup>
  import { ref, computed } from 'vue';
  import { storeToRefs } from '@/store';
  import { useFormItemStore } from './store.js';
  const { getAllGroup } = useFormItemStore();
  getAllGroup();
  const { groups } = storeToRefs(useFormItemStore());
  const props = defineProps({
    modelValue: {
      type: [Number, String],
      default: '',
    },
    width: {
      type: [Number, String],
      default: '320px',
    },
  });
  const emit = defineEmits(['update:modelValue']);
  const value = computed({
    get: () => props.modelValue,
    set: (val) => emit('update:modelValue', val),
  });
</script>
<style lang="scss" scoped></style>
