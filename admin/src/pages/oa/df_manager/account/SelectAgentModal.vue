<template>
  <div>
    <a-modal :title="'选择业务'" v-model:visible="showModal" @ok="submit" :width="600" :confirmLoading="loading">
      <a-form ref="formModel" :model="formData" :labelCol="{ span: 5 }" :wrapperCol="{ span: 16 }">
        <a-form-item label="分配给打粉专员" name="agent_id" required>
          <DFAgentSelect v-model:modelValue="formData.agent_id"></DFAgentSelect>
        </a-form-item>
      </a-form>
    </a-modal>
  </div>
</template>
<script setup>
  import { ref, computed, reactive } from 'vue';
  import { usePost } from '@/utils/useHttp';
  import DFAgentSelect from '@/components/FormItem/DFAgentSelect.vue';
  import { message } from 'ant-design-vue';
  const showModal = ref(false);
  const formModel = ref(null);

  let formData = reactive({
    agent_id: '',
  });
  const form = ref(null);
  const selected_ids = ref([]);
  const Init = (data) => {
    showModal.value = true;
    console.log(data);
    selected_ids.value = data;
  };
  defineExpose({ Init });
  const loading = ref(false);
  const emit = defineEmits(['success']);
  const submit = async () => {
    let url = '/oa/df_manager/set_agent';
    const form = await formModel.value?.validate();
    const params = {
      ...formData,
      account_ids: selected_ids.value,
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
</script>
<style lang="scss" scoped></style>
