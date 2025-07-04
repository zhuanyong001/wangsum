<template>
  <div>重置密码</div>
  <a-form ref="formModel" :model="formData" :labelCol="{ span: 5 }" :wrapperCol="{ span: 16 }">
    <a-form-item label="旧密码" name="old_password" required>
      <a-input-password v-model:value="formData.old_password" />
    </a-form-item>
    <a-form-item label="新密码" name="password" required>
      <a-input-password v-model:value="formData.password" />
    </a-form-item>
    <a-form-item :wrapper-col="{ offset: 8, span: 16 }">
      <a-button type="primary" @click="submit" :loading="loading">修改密码</a-button>
    </a-form-item>
  </a-form>
</template>

<script setup>
  import { ref, computed, reactive } from 'vue';
  import { usePost } from '@/utils/useHttp';
  import { message } from 'ant-design-vue';
  const formData = reactive({
    old_password: '',
    password: '',
  });

  const formModel = ref(null);
  const loading = ref(false);
  const submit = async () => {
    let url = '/oa/manager/reset_password';
    const params = {
      ...formData,
    };
    const form = await formModel.value?.validate();
    loading.value = true;
    try {
      const res = await usePost(url, params);
      loading.value = false;

      if (res) {
        message.success('修改成功');
      }
      formModel.value?.resetFields();
    } catch (error) {
      loading.value = false;
    }
  };
</script>
