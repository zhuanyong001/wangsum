<template>
  <a-modal v-model:visible="computedVisible" :title="title" @ok="handleOk" centered :footer="null">
    <div class="modal-content">
      <!-- 第一个卡片区域：生成谷歌密钥 -->
      <a-card title="生成谷歌密钥" class="action-card">
        <a-button type="primary" @click="createGGKeyMethod" :loading="keyCodeLoading" block>生成密钥</a-button>
        <p v-if="keyCode" class="result-text">密钥：{{ keyCode }}</p>
      </a-card>

      <!-- 第二个卡片区域：获取谷歌二维码 -->
      <a-card title="获取绑定密钥的二维码" class="action-card">
        <a-button type="primary" @click="getGGQrcodeMethod" :loading="svgContentLoading" block>获取二维码</a-button>
        <div v-if="svgContent" v-html="svgContent" class="qr-code"></div>
      </a-card>

      <!-- 第三个卡片区域：重置谷歌密钥 -->
      <a-button type="primary" @click="clearGGKeyMethod" :loading="clearKeyLoading" block danger>重置密钥</a-button>
    </div>
  </a-modal>
</template>

<script setup>
import { ref } from 'vue';
import { createGGKey, getGGQrcode, clearGGKey } from '@/api/GG.js';

// Props
const props = defineProps({
  title: {
    type: String,
    default: '操作面板',
  },
  visible: {
    type: Boolean,
    default: false,
  }
});

const params = ref({});
// Emits
const emit = defineEmits(['update:visible', 'ok', 'cancel']);

const computedVisible = ref(false);
// Handlers
const handleOk = () => {
  emit('ok');
  computedVisible.value = false;
};

const handleCancel = () => {
  emit('cancel');
  computedVisible.value = false;
};

// Methods
const keyCode = ref('');
const keyCodeLoading = ref(false);
const createGGKeyMethod = async () => {
  keyCodeLoading.value = true;
  try {
    const response = await createGGKey({ id: params.value.id });
    keyCode.value = response.data.secret;
  } catch (error) {
    console.error('生成谷歌密钥失败', error);
  }
  keyCodeLoading.value = false;
};

const svgContent = ref('');
const svgContentLoading = ref(false);
const getGGQrcodeMethod = async () => {
  svgContentLoading.value = true;
  try {
    const response = await getGGQrcode({ id: params.value.id });
    svgContent.value = response.data.qr_code_svg;
    console.log('获取谷歌二维码成功', response);
  } catch (error) {
    console.error('获取谷歌二维码失败', error);
  }
  svgContentLoading.value = false;
};

const clearKeyLoading = ref(false);
const clearGGKeyMethod = async () => {
  clearKeyLoading.value = true;
  try {
    const response = await clearGGKey({ id: params.value.id });
    console.log('清除谷歌密钥成功', response);
  } catch (error) {
    console.error('清除谷歌密钥失败', error);
  }
  clearKeyLoading.value = false;
};

const showModel = (item) => {
  params.value = item;
  computedVisible.value = true;
};

defineExpose({
  showModel
});
</script>

<style scoped>
.modal-content {
  display: flex;
  flex-direction: column;
  gap: 16px;
}

.action-card {
  margin-bottom: 16px;
}

.result-text {
  margin-top: 8px;
  font-weight: bold;
}

.qr-code {
  margin-top: 16px;
  text-align: center;
}
</style>
