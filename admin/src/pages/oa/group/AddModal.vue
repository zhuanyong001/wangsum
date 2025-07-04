<template>
  <div>
    <a-modal
      :title="formData.id ? '编辑' : '新增'"
      v-model:visible="showModal"
      @ok="submit"
      :width="600"
      :confirmLoading="loading"
    >
      <a-form ref="formModel" :model="formData" :labelCol="{ span: 5 }" :wrapperCol="{ span: 16 }">
        <a-form-item label="分组名" name="op_status">
          <a-input v-model:value="formData.name" />
        </a-form-item>
      </a-form>
    </a-modal>
  </div>
</template>
<script setup>
  import { ref, computed, reactive } from 'vue';
  import { usePost } from '@/utils/useHttp';
  const showModal = ref(false);

  let formData = reactive({
    name: '',
  });
  const form = ref(null);
  const Init = (data) => {
    form.value?.resetFields();
    if (form) {
      formData = reactive({
        ...data,
      });
    }
    showModal.value = true;
  };
  defineExpose({ Init });
  const loading = ref(false);
  const emit = defineEmits(['success']);
  const submit = async () => {
    let url = '/oa/group/save';
    const params = {
      ...formData,
    };
    loading.value = true;
    const res = await usePost(url, params);
    loading.value = false;
    if (res) {
      showModal.value = false;
      emit('success');
    }
  };
</script>
<style lang="scss" scoped></style>
