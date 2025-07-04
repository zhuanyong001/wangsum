<template>
  <sun-label :label="item.label" :position="item.position" :style="{ width: item.type === 'table' ? '100%' : 'auto' }">
    <div v-if="item.type === 'text'">{{ value }}</div>
    <div v-else-if="item.type === 'table'">
      <a-table :dataSource="value" :columns="item.columns" style="width: 100%" :pagination="false" />
    </div>
    <div v-else-if="item.type === 'descriptions'">
      <a-descriptions title="" :column="2">
        <a-descriptions-item v-for="deItem in item.children" :label="deItem.label">
          {{ value[deItem.key] }}
        </a-descriptions-item>
      </a-descriptions>
    </div>
    <div v-else-if="item.type === 'booleanText'">{{ value === item.yes ? item.yesText : item.noText }}</div>
    <div v-else-if="item.type === 'seniorText'">{{ item.method(value) }}</div>
    <div v-else-if="item.type === 'custom'">
      <component :is="item.render(value)" />
    </div>
  </sun-label>
</template>
<script setup>
  import { onMounted } from 'vue';
  import SunLabel from '@/components/SunLabel/index.vue';
  const props = defineProps({
    item: Object,
    modelValue: [Array, Object, String, Number, Boolean],
  });
  const value = computed(() => props.modelValue);
  onMounted(() => {
    if (props.modelValue === void 0 && props.item.default !== void 0) value.value = props.item.default;
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
