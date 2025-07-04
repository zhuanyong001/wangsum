<template>
  <sun-modal :title="attrs.title" @ok="handleOk" @cancel="handleCancel">
    <div class="sunModal">
      <div class="sun-a-number-input">
        <a-input v-model:value="reChangeParams" :bordered="false" size="large" :placeholder="$t('index.enter_88')" ref="inputRef"></a-input>
      </div>
    </div>
  </sun-modal>
</template>

<script setup lang="ts">
import { ref, defineProps, defineEmits, onMounted, nextTick } from 'vue'

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

const reChangeParams = ref<number>()
const inputRef = ref(null) // 定义一个 ref 来引用输入框

const handleOk = () => {
  if (typeof props.handleOk === 'function') {
    props.handleOk(reChangeParams.value)
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
}
</style>
