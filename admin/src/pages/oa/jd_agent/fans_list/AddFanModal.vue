<template>
  <div>
    <a-modal :title="'备注'" v-model:visible="showModal" @ok="submit" :width="600" :confirmLoading="loading">
      <a-form ref="formModel" :model="formData" :labelCol="{ span: 5 }" :wrapperCol="{ span: 16 }">
        <a-form-item label="客户whatsapp" name="whatsapp">
          {{ curr_item.whatsapp }}
        </a-form-item>
        <a-form-item label="备注" name="remark">
          <a-textarea v-model:value="formData.remark" />
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

  let formData = reactive({
    whatsapp: '',
    remark: '',
  });
  const formModel = ref(null);
  const curr_item = ref(null);
  const Init = (data) => {
    formModel.value?.resetFields();
    curr_item.value = data;
    showModal.value = true;
  };
  defineExpose({ Init });
  const loading = ref(false);
  const emit = defineEmits(['success']);
  const submit = async () => {
    let url = '/oa/jd_agent/set_remark';
    const form = await formModel.value?.validate();
    const params = {
      id: curr_item.value.id,
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
