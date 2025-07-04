<template>
  <div>
    <a-popover title="币种详情" trigger="hover">
      <template #content>
        <p v-for="item in list" style="display: flex; justify-content: space-between">
          <strong>{{ item.currency.name }}:</strong>
          <span>{{ item.amount }}</span>
        </p>
      </template>
      <span type="link">{{ title }}</span>
    </a-popover>
  </div>
</template>

<script setup>
  import { ref } from 'vue';
  const props = defineProps({
    title: String,
    list: Array,
  });
</script>
<style lang="scss" scoped></style>
