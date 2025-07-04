<template>
  <div>
    <a-select ref="select" v-model:value="value" :style="{ width: width }" allowClear>
      <a-select-option :value="item.id" v-for="(item, i) in my_df_agents" :key="i"
        >{{ item.username }}
      </a-select-option>
    </a-select>
  </div>
</template>
<script setup>
  import { ref, computed } from 'vue';
  import { storeToRefs } from '@/store';
  import { useFormItemStore } from './store.js';
  const { getMyDFAgent } = useFormItemStore();
  getMyDFAgent();
  const { my_df_agents } = storeToRefs(useFormItemStore());
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
</script>
<style lang="scss" scoped></style>
