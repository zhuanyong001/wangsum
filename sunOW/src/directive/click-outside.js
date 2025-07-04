// src/directives/click-outside.js
export default {
    beforeMount(el, binding) {
      // 定义事件处理函数
      el.__clickOutsideHandler__ = (event) => {
        // 如果点击的目标不是 el 或 el 的子元素
        if (!(el === event.target || el.contains(event.target))) {
          // 调用传入的回调函数
          binding.value(event);
        }
      };
      // 添加事件监听器到 document
      document.addEventListener('click', el.__clickOutsideHandler__);
    },
    unmounted(el) {
      // 移除事件监听器
      document.removeEventListener('click', el.__clickOutsideHandler__);
      el.__clickOutsideHandler__ = null;
    },
  };
  