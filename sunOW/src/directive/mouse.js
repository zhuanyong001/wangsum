// mouse.js
import { ref, onMounted, onUnmounted } from 'vue'

export function useMouse() {
  // 被组合式函数封装和管理的状态
  const x = ref(0)
  const y = ref(0)

  // 组合式函数可以随时更改其状态。
  function update(event) {
    x.value = event.movementX
    y.value = event.movementY
  }

  // 一个组合式函数也可以挂靠在所属组件的生命周期上
  // 来启动和卸载副作用
  onMounted(() => window.addEventListener('mousemove', update))
  onUnmounted(() => window.removeEventListener('mousemove', update))

  // 通过返回值暴露所管理的状态
  return { x, y }
}

export const mouseListener = {
  mounted(el, binding) {
    // 鼠标按下事件
    const handleMouseDown = (event) => {
      // 添加鼠标松开事件监听
      
      const handleMouseMove = (event) => {
        if(event.movementX<0&&el.style.width === '100%'){

        }else if(Math.abs(event.movementX)<50){
          const width = el.offsetWidth-event.movementX;
          const resultValue = Math.max(binding.value.minValue, Math.min(width, binding.value.maxValue));
          el.style.width = resultValue+'px'
        }else if(event.movementX>50){
          el.style.width = binding.value.minValue+'px'
        }else if(event.movementX<-50){
          el.style.width = '100%'
        }
        
      };
      const handleMouseUp = (event) => {
        // 移除鼠标松开事件监听
        document.removeEventListener('mouseup', handleMouseUp);
        document.removeEventListener('mousemove', handleMouseMove);
      };
      document.addEventListener('mouseup', handleMouseUp);
      document.addEventListener('mousemove', handleMouseMove);
    };

    // 添加鼠标按下事件监听
    el.addEventListener('mousedown', handleMouseDown);

    // 将处理函数保存在元素上，方便在unbind时移除事件监听
    el.__handleMouseDown = handleMouseDown;
  }
}