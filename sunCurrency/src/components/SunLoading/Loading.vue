<template>
  <div v-if="visible" class="loading-mask">
    <div class="loading-spinner"></div>
  </div>
</template>

<script>
export default {
  name: 'Loading',
  data() {
    return {
      visible: false,
    }
  },
  methods: {
    show() {
      this.visible = true
    },
    hide() {
      this.visible = false
    },
  },
}
</script>

<style scoped>
/* 整个遮罩层样式 */
.loading-mask {
  position: fixed;
  top: 0;
  left: 0;
  width: 100vw;
  height: 100vh;
  background-color: rgba(0, 0, 0, 0.5); /* 半透明背景 */
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 9999;
}

/* 转圈动画样式 */
.loading-spinner {
  width: 50px;
  height: 50px;
  border: 5px solid rgba(255, 255, 255, 0.3); /* 边框颜色较浅 */
  border-top: 5px solid white; /* 顶部边框颜色 */
  border-radius: 50%;
  animation: spin 1s linear infinite; /* 旋转动画 */
}

/* 定义旋转动画 */
@keyframes spin {
  0% {
    transform: rotate(0deg);
  }
  100% {
    transform: rotate(360deg);
  }
}
</style>
