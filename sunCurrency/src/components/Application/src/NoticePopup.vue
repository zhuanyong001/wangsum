<template>
  <sun-modal :title="$t(`notice.${attrs.title}`)" @ok="handleOk" @cancel="handleOk" :show-footer="false">
    <div class="sunModal">
      <div v-html="$t(`notice.${attrs.content}`)"></div>
    </div>
    <!-- <SunButton class="onbtn" type="primary" @click="handleOk" shape="round" size="large" block> {{ $t('index.read_confirm_11') }} </SunButton> -->
  </sun-modal>
</template>

<script setup lang="ts">
import { ref, defineProps, defineEmits, onMounted } from 'vue'

const props = defineProps({
  attrs: {
    type: Object,
    required: true,
  },
  handleOk: {
    type: Function,
  },
  handleCancel: {
    type: Function,
  },
})

const emits = defineEmits(['ok', 'cancel'])

const inputRef = ref(null) // 定义一个 ref 来引用输入框

const handleOk = () => {
  if (typeof props.handleOk === 'function') {
    props.handleOk()
  }
}

const handleCancel = () => {
  if (typeof props.handleCancel === 'function') {
    props.handleCancel()
  }
}

onMounted(() => {
  // 页面挂载后自动聚焦到输入框
  if (inputRef.value) {
    console.log('聚焦')
    inputRef.value.focus()
  }
})
</script>

<style scoped lang="scss">
.sunModal {
  @include box-box;
  overflow: scroll;
  max-height: 60vh;
  min-height: 200px;
  .onbtn {
    margin-top: 20px;
  }
}
:deep(.modal-header) {
  justify-content: space-around !important;
}
</style>
