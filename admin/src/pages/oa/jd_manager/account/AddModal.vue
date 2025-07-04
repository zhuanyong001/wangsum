<template>
  <div>
    <a-modal :title="'批量导入'" v-model:visible="showModal" @ok="submit" :width="600" :confirmLoading="loading">
      <a-form ref="formModel" :model="formData" :labelCol="{ span: 5 }" :wrapperCol="{ span: 16 }">
        <a-form-item label="whatsapp账户" name="accounts" required>
          <a-textarea
            v-model:value="formData.accounts"
            placeholder="一行一个"
            allow-clear
            :autosize="{ minRows: 6, maxRows: 26 }"
          />
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
    accounts: '',
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
    let url = '/oa/jd_manager/accounts_add';
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
