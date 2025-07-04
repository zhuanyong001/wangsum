<template>
  <a-date-picker v-model:value="value" :picker="mode" />
</template>
<script setup>
  const props = defineProps({
    modelValue: {
      type: [String, Number, Object],
      default: null,
    },
    mode: {
      type: String,
      default: 'date',
    },
  });
  const emit = defineEmits(['update:modelValue']);
  const value = computed({
    get: () => {
      if (props.modelValue) return dayjs(props.modelValue * 1000);
      return null;
    },
    set: (val) => {
      let res;
      if (val) res = dayjs(val).unix();
      else res = null;
      console.log('====>>res', res);
      emit('update:modelValue', res);
    },
  });
</script>
<style lang="scss" scoped></style>
