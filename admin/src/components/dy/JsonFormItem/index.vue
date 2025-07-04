<template>
  <a-form-item :label="item.label" :rules="item.rules">
    <a-switch v-if="item.type === 'switch'" v-model:checked="value" :checkedValue="1" :unCheckedValue="0" />
    <a-input v-else-if="item.type === 'text'" v-model:value="value" />
    <a-textarea v-else-if="item.type === 'textarea'" v-model:value="value" :rows="4" />
    <a-select v-else-if="item.type === 'select'" v-model:value="value" allowClear :mode="item.mode || 'combobox'">
      <a-select-option v-for="opt in item.opts" :key="opt.value" :value="opt.value">
        {{ opt.label }}
      </a-select-option>
    </a-select>
    <a-input-number v-else-if="item.type === 'number'" v-model:value="value" style="width: 200px" />
    <a-date-picker
      v-else-if="item.type === 'datePicker'"
      show-time
      valueFormat="YYYY-MM-DD HH:mm:ss"
      v-model:value="value"
    />
    <a-range-picker v-else-if="item.type === 'datePickerRange'" v-model:value="value" :picker="item.picker" valueFormat="YYYY-MM-DD HH:mm:ss"/>
    <UploadImgs v-model:img="value" v-else-if="item.type === 'uploadimg'"></UploadImgs>

    <template v-else-if="item.type == 'custom'">
      <component :is="item.component" v-model:value="value" />
    </template>

    <div class="desc">{{ item.desc }}</div>
  </a-form-item>
</template>
<script setup>
  import { onMounted } from 'vue';
  import UploadImgs from '@/components/UploadImgs/index.vue';

  const props = defineProps({
    item: Object,
    modelValue: [Array, Object, String, Number, Boolean],
  });
  const emit = defineEmits(['update:modelValue']);
  const value = computed({
    get: () => props.modelValue,
    set: (val) => emit('update:modelValue', val),
  });
  onMounted(() => {
    if (props.modelValue===void 0 && props.item.default !== void 0) value.value = props.item.default;
  });
</script>
<style lang="less" scoped>
  .desc {
    color: #999;
    margin-top: 5px;
    font-size: 12px;
    line-height: 1.5;
    margin-bottom: 0;
    display: block;
  }
</style>
