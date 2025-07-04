<template>
  <div ref="modalContainer" class="SunModal" id="SunModal">
    <div class="modal-overlay" @click="handleCancel"></div>
    <div :class="['modal-content', { centered: centered }]">
      <div class="modal-backdrop">
        <div :class="['modal-header', { centered: centered }]">
          <h3 class="modal-title">{{ title || attrs?.title }}</h3>
          <CloseOutlined class="modal-close" @click="handleCancel" />
        </div>
        <div class="modal-body">
          <slot>
            <div>{{ attrs?.content }}</div>
          </slot>
        </div>
        <div class="modal-footer" v-if="showFooter">
          <button v-if="attrs?.showCancelButton !== false" class="btn close" @click="handleCancel">{{ $t('index.Cancel_11') }}</button>
          <button class="btn confirm" @click="handleOk">{{ $t('index.confirm_11') }}</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script lang="ts" setup>
import { ref, defineProps, defineEmits, onMounted } from 'vue'
import { CloseOutlined } from '@ant-design/icons-vue'
const props = defineProps({
  title: {
    type: String,
    required: true,
  },
  attrs: {
    type: Object,
  },
  showFooter: {
    type: Boolean,
    default: true,
  },
  centered: {
    type: Boolean,
    default: false,
  },
  content: {
    type: String,
    default: '',
  },
  handleOk: {
    type: Function,
  },
  handleCancel: {
    type: Function,
  }
})
console.log(props)
const emit = defineEmits(['ok', 'cancel'])

const handleOk = async() => {
  emit('ok')
  if (typeof props.handleOk === 'function') {
    await props.handleOk()
  }
}

const handleCancel = () => {
  emit('cancel')
  if (typeof props.handleCancel === 'function') {
    props.handleCancel()
  }
}

function isOldiOSDevice() {
  const ua = navigator.userAgent

  // 判断是否为 iPhone 设备
  const isIphone = /iPhone/i.test(ua)

  // 返回是否为 iPhone 设备
  return isIphone
}

const modalContainer = ref()

onMounted(() => {})
</script>

<style lang="scss" scoped>
.SunModal {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 99;
  box-sizing: border-box;
  -webkit-overflow-scrolling: touch;
  overflow-y: auto;
  
  .modal-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
    pointer-events: none;
  }
  .centered {
    // display: flex;
    // flex-direction: column;
    // align-items: center;
  }
  .modal-content {
    position: relative;
    z-index: 1001;
    width: 28.75rem;
    // max-width: 80vw;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease;
    @include box-box;
    padding: 0;
    .modal-backdrop{
      @include greenL-background;
      padding: 2rem 1.67rem 0;
    }
    
    .modal-header {
      width: 100%;
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 1rem;
      .modal-title {
        color: var(--textColor30);
        font-size: 1.5rem;
        margin: 0;
        font-weight: 600;
        display: flex;
        justify-content: flex-start;
      }

      .modal-close {
        position: absolute;
        top: 1.25rem;
        right: 1.25rem;
        background: none;
        border: none;
        font-size: 1.67rem;
        cursor: pointer;
        color: var(--textColor2);
        font-weight: 900;
      }
    }

    .modal-body {
      margin: 1.25rem 0;
      width: 100%;
    }

    .modal-footer {
      display: flex;
      width: 100%;
      justify-content: space-between;
      column-gap: 2rem;
      margin-bottom: 2rem;
      .btn {
        cursor: pointer;
        flex: 1;
        text-align: center;
        padding: 1.33rem 0;
        font-size: 1.33rem;
        font-weight: 600;
      }

      .close {
        color: var(--textColor2);
        @include metal-color();
        border-radius: 4rem;
      }

      .confirm {
        color: var(--textColor);
        font-weight: 600;
        @include colour-background-2;
        color: var(--textColor4);
        border-radius: 4rem;
      }
    }
  }
}
</style>
