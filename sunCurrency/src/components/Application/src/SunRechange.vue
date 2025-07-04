<template>
  <sun-modal :title="attrs.title" @ok="handleOk" @cancel="handleCancel" centered>
    <div class="sunModal">
      <div class="sun-a-number-input">
        <a-input-number v-model:value="reChangeParams" :bordered="false" size="large" :placeholder="$t('index.amount_98')" :controls="false">
          <template #addonAfter v-if="attrs.max">
            <div class="addonAfter" @click="onMax">Max</div>
          </template>
        </a-input-number>
      </div>
    </div>
  </sun-modal>
</template>
  
<script setup lang="ts">
import { ref, defineProps, defineEmits } from 'vue'

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

const onMax = () =>{
  reChangeParams.value = props.attrs.max
}
</script>
  
<style scoped lang="scss">
.sunModal {
  padding: 2.08rem 0;
}
</style>
  