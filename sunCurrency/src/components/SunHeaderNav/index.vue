<template>
  <div class="header-nav" :style="{ backgroundColor: backgroundColor }">
    <div class="content">
      <SvgIcon class="svg" @click="handleBack" name="leftJt" size="2rem"></SvgIcon>
      <div class="title">{{ title }}</div>
      <div @click="handleRight" class="right">
        <slot name="right">
          {{ rightText }}
        </slot>
      </div>
    </div>
  </div>
  <div class="gap"></div>
</template>

<script setup lang="ts">
import { defineProps, defineEmits } from 'vue'
import { useRouter } from 'vue-router'
import { LeftOutlined } from '@ant-design/icons-vue'

const props = defineProps({
  title: {
    type: String,
    default: 'App Title',
  },
  backgroundColor: {
    type: String,
    default: 'transparent',
  },
  rightText: {
    type: String,
    default: '',
  },
})

const router = useRouter()

const handleBack = () => {
  router.back()
}
const emit = defineEmits(['handleRight'])
const handleRight = () => {
  emit('handleRight')
}
</script>

<style lang="scss" scoped>
.header-nav {
  /* Adjust the height as needed */

  position: fixed;
  width: 100%;
  top: 0;
  z-index: 1000;

  .content {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0 1rem;
    height: 4rem;

    .back-icon {
      font-size: 1.5rem;
      cursor: pointer;
    }

    .title {
      flex: 1;
      text-align: center;
      font-size: 1.5rem;
      font-weight: bold;
    }

    .right {
      position: absolute;
      right: 1.25rem;
      flex: 0;
      font-size: 1.33rem;
      white-space: nowrap;
    }
  }
  

}
.gap{
  height: 4rem;
}

</style>