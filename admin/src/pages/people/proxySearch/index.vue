<template>
    <a-space direction="vertical">
      <a-input-search
        v-model:value="value"
        placeholder="input search text"
        enter-button="Search"
        size="large"
        @search="onSearch"
      />
    </a-space>
  </template>
  <script setup>
    import { defineComponent, ref } from 'vue';
    const value = ref('');
    const onSearch = searchValue => {
    console.log('use value', searchValue);
    console.log('or use this.value', value.value);
    };
  </script>