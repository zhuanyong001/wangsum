<template>
  <div>
    <a-modal title="导入语言" v-model:visible="showModal" @ok="submit" :width="800" :confirmLoading="loading">
      <a-form ref="formModel" :model="formData" :labelCol="{ span: 5 }" :wrapperCol="{ span: 16 }">
        <a-form-item label="语言" name="title">
          <a-select :allowClear="true" style="width: 220px" v-model:value="formData.lang_id">
            <a-select-option v-bind:key="item.id" v-for="item in langlist" :value="item.id">
              {{ item.name }}||{{ item.key }}</a-select-option
            >
          </a-select>
        </a-form-item>
        <a-form-item label="JOSN格式数据" name="desc">
          <a-textarea :rows="15" v-model:value="formData.jsondata"> </a-textarea>
        </a-form-item>
      </a-form>
    </a-modal>
  </div>
</template>
<script setup>
  import { ref, computed, reactive } from 'vue';
  import { usePost } from '@/utils/useHttp';
  import { message } from 'ant-design-vue';

  const showModal = ref(false);
  const loading = ref(false);

  let formData = reactive({});
  const langlist = ref([]);
  const Init = (lang) => {
    langlist.value = lang;
    showModal.value = true;
  };
  defineExpose({ Init });
  const emit = defineEmits(['success']);
  const submit = async () => {
    try {
      JSON.parse(formData.jsondata);
    } catch (error) {
      message.error('JSON格式错误');
      return;
    }
    if (!formData.lang_id) {
      message.error('请选择语言');
      return;
    }

    const params = {
      ...formData,
    };

    loading.value = true;
    const res = await usePost('/i18n/addlang', params);
    loading.value = false;
    if (res) {
      showModal.value = false;
      emit('success');
    }
  };
</script>
<style lang="scss" scoped></style>
