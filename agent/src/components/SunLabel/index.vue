<template>
    <div :class="['sun-label', positionClass]">
      <div class="sun-label-title">{{ label }}</div>
      <div class="sun-label-content">
        <slot></slot>
      </div>
    </div>
  </template>
  
  <script setup>
  import { computed } from 'vue';
  import { defineProps } from 'vue';
  
  const props = defineProps({
    label: {
      type: String,
      required: true
    },
    position: {
      type: String,
      default: 'left', // 'left' or 'top'
    }
  });
  
  const positionClass = computed(() => {
    return props.position === 'top' ? 'sun-label-top' : 'sun-label-left';
  });
  </script>
  
  <style scoped>
  .sun-label {
    display: flex;
    margin-bottom: 16px;
  }
  
  .sun-label-left {
    flex-direction: row;
  }
  
  .sun-label-top {
    flex-direction: column;
  }
  
  .sun-label-title {
    font-weight: bold;
    margin-right: 8px;
    margin-bottom: 8px;
  }
  
  .sun-label-content {
    flex: 1;
  }
  </style>
  