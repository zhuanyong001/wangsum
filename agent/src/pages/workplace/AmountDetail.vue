<template>
  <div>
    <a-collapse v-model:activeKey="activeKey" ghost>
      <a-collapse-panel key="1">
        <template #header>
          <slot></slot>
        </template>
        <a-row v-for="item in AmountData">
          <a-col :span="4">{{ item.currency.name }}</a-col>
          <a-col :span="10">{{ item.amount }}</a-col>
          <a-col :span="10">${{ (item.amount * item.currency.price).toFixed(2) }}</a-col>
        </a-row>
      </a-collapse-panel>
    </a-collapse>
  </div>
</template>
<script setup>
  const activeKey = ref(['1']);
  const props = defineProps({
    AmountData: Array,
  });
</script>
<style lang="scss" scoped></style>
