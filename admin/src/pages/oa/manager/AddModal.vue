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
        <a-form-item label="用户名" name="username" required>
          <a-input v-model:value="formData.username" />
        </a-form-item>
        <a-form-item label="密码" name="password">
          <a-input v-model:value="formData.password" type="password" />
        </a-form-item>
        <a-form-item label="分组" name="group_id">
          <GroupSelect v-model:modelValue="formData.group_id" :width="'100%'"></GroupSelect>
        </a-form-item>
        <a-form-item label="身份" name="role_type">
          <a-select ref="select" v-model:value="formData.role_type" allowClear>
            <a-select-option value="zz">组长管理员</a-select-option>
            <a-select-option value="jd">接待管理员</a-select-option>
            <a-select-option value="df">打粉管理员</a-select-option>
            <a-select-option value="agent_jd">接待业务员</a-select-option>
            <a-select-option value="agent_df">打粉业务员</a-select-option>
          </a-select>
        </a-form-item>
      </a-form>
    </a-modal>
  </div>
</template>
<script setup>
  import { ref, computed, reactive } from 'vue';
  import { usePost } from '@/utils/useHttp';
  import GroupSelect from '@/components/FormItem/GroupSelect.vue';
  import { message } from 'ant-design-vue';
  const showModal = ref(false);
  const formModel = ref(null);

  let formData = reactive({
    name: '',
    group_id: '',
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
    let url = '/oa/manager/save';
    const form = await formModel.value?.validate();
    const params = {
      ...formData,
    };
    loading.value = true;
    try {
      const res = await usePost(url, params);
      loading.value = false;
      if (res) {
        showModal.value = false;
        emit('success');
      }
    } catch (error) {
      loading.value = false;
    }
  };

  const updateData = async () => {};
</script>
<style lang="scss" scoped></style>
