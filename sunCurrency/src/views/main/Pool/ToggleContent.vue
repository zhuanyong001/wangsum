<template>
<div class="toggle-content">
    <button @click="toggle">
    <template v-if="isOpen">
        {{$t('pool.open')}} <UpOutlined />
    </template>
    <template v-else>
        {{$t('pool.PutItAway')}} <DownOutlined />
    </template>
    </button>
    <div v-if="isOpen" class="content">
        <slot></slot>
    </div>
</div>
</template>
  
  <script>
  import { ref } from 'vue';
  
  export default {
    name: 'ToggleContent',
    setup() {
      const isOpen = ref(false);
  
      const toggle = () => {
        isOpen.value = !isOpen.value;
      };
  
      return {
        isOpen,
        toggle,
      };
    },
  };
  </script>
  
  <style scoped>
  .toggle-content {
    margin-top: 1.25rem;
    display: flex;
    flex-direction: column;
    align-items: center;
    width: 100%;
  }
  
  button {
    border: none;
    cursor: pointer;
    /* margin-bottom: 10px; */
  }
  
  button:hover {

  }
  
  .content {
    /* border: 1px solid #e8e8e8; */
    padding: 10px;
    width: 100%;
  }
  </style>
  