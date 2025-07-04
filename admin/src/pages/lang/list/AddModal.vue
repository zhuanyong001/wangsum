<template>
  <div>
    <a-modal :title="formData.id ? '新增作者' : '编辑作者'" v-model:visible="showModal" @ok="submit">
      <a-form ref="formModel" :model="formData" :labelCol="{ span: 5 }" :wrapperCol="{ span: 16 }">
        <a-form-item label="名称" required name="name">
          <a-input v-model:value="formData.name" />
        </a-form-item>
        <a-form-item label="key" required name="key">
          <a-input v-model:value="formData.key" />
        </a-form-item>
        <a-form-item label="状态" required name="status">
          <a-switch v-model:checked="formData.status" checked-children="开" un-checked-children="关" />
        </a-form-item>
      </a-form>
    </a-modal>
  </div>
</template>
<script setup>
  import { ref, computed, reactive } from 'vue';
  import { usePost } from '@/utils/useHttp';
  const showModal = ref(false);
  let formData = ref({
    id: 0,
    name: '',
    key: '',
    status: true,
  });
  const Init = (form) => {
    if (!form) {
      formData.value = {
        id: 0,
        name: '',
        key: '',
        status: true,
      };
    } else {
      formData.value = { ...form, status: form.status == 1 };
    }
    showModal.value = true;
  };
  defineExpose({ Init });
  const emit = defineEmits(['success']);
  const submit = async () => {
    const params = {
      ...formData.value,
      status: formData.value.status ? 1 : 0,
    };

    const res = await usePost('/i18n/langsave', params);
    if (res) {
      showModal.value = false;
      emit('success');
    }
  };
</script>
<style lang="scss" scoped></style>
